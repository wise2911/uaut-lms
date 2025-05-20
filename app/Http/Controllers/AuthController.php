<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                Auth::logout(); // Log out to prevent admin access via user login
                return redirect()->route('admin.login')->with('error', 'Admins must log in at /admin/login.');
            }

            return redirect()->route('user.home')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/user/login')->with('success', 'Account created successfully! Please log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user/login')->with('success', 'You have been logged out successfully.');
    }
}