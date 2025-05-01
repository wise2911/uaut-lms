<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    protected function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $users = User::all();
        $courses = Course::with('users')->get();
        return view('admin.users', compact('users', 'courses'));
    }

    public function unenroll(Request $request, Course $course, User $user)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $course->users()->detach($user->id);
        return redirect()->route('admin.users')->with('success', 'User unenrolled successfully.');
    }

    public function delete(User $user)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Cannot delete an admin user.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}