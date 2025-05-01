<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Get enrolled courses
        $enrolledCourses = Auth::user()->courses()->get();

        // Get available courses with department filter
        $department = $request->query('department', '');
        $query = Course::query();
        if ($department) {
            $query->where('department', $department);
        }
        $availableCourses = $query->paginate(9); // 9 courses per page for 3x3 grid

        return view('user.home', compact('enrolledCourses', 'availableCourses', 'department'));
    }

    public function show(Course $course)
    {
        if (!Auth::user()->courses->contains($course->id)) {
            return redirect()->route('user.home')->with('error', 'You are not enrolled in this course.');
        }

        return view('courses.show', compact('course'));
    }

    public function enroll(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        Auth::user()->courses()->attach($courseId);
        return redirect()->route('courses.show', $courseId)->with('success', 'Successfully enrolled in the course.');
    }

    public function updateProgress(Request $request, $courseId)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if (!$user->courses->contains($course->id)) {
            return response()->json(['error' => 'Not enrolled in this course.'], 403);
        }

        $user->videos()->syncWithoutDetaching([
            $request->video_id => ['progress' => $request->progress]
        ]);

        return response()->json(['success' => 'Progress updated.']);
    }
}