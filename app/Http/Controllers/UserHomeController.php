<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserHomeController extends Controller
{
    public function index()
    {
        $enrolledCourses = Auth::user()->courses()->paginate(9);
        return view('user.home', compact('enrolledCourses'));
    }

    public function enroll(Request $request, $courseId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('info', 'Please log in to enroll.');
            }

            $course = Course::findOrFail($courseId);

            if ($user->courses()->where('course_user.course_id', $courseId)->exists()) {
                return redirect()->route('courses.show', $courseId)->with('info', 'You are already enrolled in this course.');
            }

            $user->courses()->attach($courseId, ['progress' => 0]);

            return redirect()->route('courses.show', $courseId)->with('success', 'Successfully enrolled in the course!');
        } catch (\Exception $e) {
            Log::error('Enrollment error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'course_id' => $courseId,
            ]);
            return redirect()->route('courses.show', $courseId)->with('error', 'Failed to enroll in the course. Please try again.');
        }
    }
}