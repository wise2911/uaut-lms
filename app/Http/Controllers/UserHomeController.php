<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
   
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get enrolled courses with progress (sorted by latest enrollment)
        $enrolledCourses = $user->courses()
            ->with('videos')
            ->orderByPivot('created_at', 'desc')
            ->get();
        
        // Get available courses with filtering
        $availableCourses = Course::whereDoesntHave('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('videos')
            ->when($request->department, function($query, $department) {
                return $query->where('department', $department);
            })
            ->latest()
            ->paginate(10);

        return view('user.home', [
            'user' => $user,
            'enrolledCourses' => $enrolledCourses,
            'availableCourses' => $availableCourses,
            'departments' => Course::distinct()->pluck('department'),
            'department' => $request->department,
            'success' => session('success'),
            'info' => session('info')
        ]);
    }

    public function enroll(Request $request, $courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return redirect()->route('courses.show', $courseId)
                   ->with('info', 'You are already enrolled in this course.');
        }

        $user->courses()->attach($courseId, ['progress' => 0]);

        return redirect()->route('courses.show', $courseId)
               ->with('success', 'Enrollment successful! You can now access all course materials.');
    }

    public function updateProgress(Request $request, $courseId)
    {
        $request->validate([
            'progress' => 'required|numeric|min:0|max:100'
        ]);

        Auth::user()->courses()->updateExistingPivot($courseId, [
            'progress' => $request->progress
        ]);

        return back()->with('success', 'Progress updated!');
    }
}