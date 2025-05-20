<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with('videos')
            ->when($request->department, function ($query, $department) {
                return $query->where('department', $department);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(9);

        return view('courses.index', [
            'courses' => $courses,
            'departments' => Course::distinct()->pluck('department'),
            'department' => $request->department,
            'search' => $request->search,
        ]);
    }

    public function show(Course $course)
    {
        $course->load('videos');
        $isEnrolled = Auth::check() && Auth::user()->courses()->where('course_id', $course->id)->exists();
        return view('courses.show', compact('course', 'isEnrolled'));
    }

    public function enroll(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user();

        if (!$user->courses()->where('course_id', $course->id)->exists()) {
            $user->courses()->attach($course->id, ['progress' => 0]);
            return redirect()->route('courses.show', $course->id)
                           ->with('success', 'Successfully enrolled in the course!');
        }

        return redirect()->route('courses.show', $course->id)
                       ->with('info', 'You are already enrolled in this course.');
    }

    public function learn(Course $course)
    {
        $user = Auth::user();
        $course->load([
            'videos', // Removed incorrect orderBy('order')
            'users' => function ($query) use ($user) {
                $query->where('user_id', $user->id)->withPivot('progress');
            }
        ]);

        if (!$user->courses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->id)
                           ->with('info', 'Please enroll in the course to access the content.');
        }

        Log::info('Course data for ID ' . $course->id, [
            'videos' => $course->videos->toArray(),
            'user_progress' => $course->users->pluck('pivot.progress')->first() ?? 'Not enrolled'
        ]);

        return view('courses.learn', compact('course'));
    }

    public function markWatched(Request $request, Course $course)
    {
        $request->validate(['video_id' => 'required|exists:videos,id']);
        $user = Auth::user();
        $video = $course->videos()->findOrFail($request->video_id);
        $totalVideos = $course->videos->where('is_preview', false)->count();
        $currentProgress = $user->courses()->where('course_id', $course->id)->first()->pivot->progress ?? 0;
        $progressIncrement = $totalVideos > 0 ? (100 / $totalVideos) : 0;
        $newProgress = min(100, round($currentProgress + $progressIncrement));

        $user->courses()->updateExistingPivot($course->id, ['progress' => $newProgress]);

        return response()->json([
            'success' => 'Video marked as watched',
            'progress' => $newProgress
        ]);
    }
}