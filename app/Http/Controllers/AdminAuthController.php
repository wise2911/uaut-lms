<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        Log::info('Attempting to load view', ['view' => 'auth.admin-login', 'paths' => config('view.paths')]);
        try {
            return view('auth.admin-login');
        } catch (\Exception $e) {
            Log::error('View load failed', ['error' => $e->getMessage(), 'view' => 'auth.admin-login']);
            throw $e;
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->route('admin.videos.index')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
    }
}