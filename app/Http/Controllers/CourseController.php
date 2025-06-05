<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Segment;
use App\Models\UserSegmentProgress;
use App\Models\CourseRating;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\PDF;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $department = $request->query('department');

        $query = Course::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('instructor_name', 'LIKE', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        $courses = $query->paginate(9);
        $departments = Course::distinct()->pluck('department')->filter()->values();

        return view('courses.index', compact('courses', 'departments', 'search', 'department'));
    }

    public function show(Course $course)
    {
        $course->load('video.segments');
        $previewVideo = $course->video->firstWhere('is_preview', true);
        $isEnrolled = Auth::check() && Auth::user()->courses()->where('course_user.course_id', $course->id)->exists();
        $userProgress = $isEnrolled ? Auth::user()->calculateCourseProgress($course) : 0;

        return view('courses.show', compact('course', 'previewVideo', 'isEnrolled', 'userProgress'));
    }

    public function learn(Course $course)
    {
        if (!Auth::check() || !Auth::user()->courses()->where('course_user.course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to access this course.');
        }

        $course->load('video.segments');
        $userProgress = Auth::user()->calculateCourseProgress($course);

        return view('courses.learn', compact('course', 'userProgress'));
    }

    public function markWatched(Request $request, Course $course)
    {
        try {
            $request->validate([
                'video_id' => 'required|exists:video,id',
                'segment_id' => 'required|exists:segments,id',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            $videoId = $request->input('video_id');
            $segmentId = $request->input('segment_id');

            $video = Video::where('id', $videoId)->where('course_id', $course->id)->first();
            if (!$video) {
                return response()->json(['success' => false, 'message' => 'Invalid video for this course'], 422);
            }

            $segment = Segment::where('id', $segmentId)->where('video_id', $videoId)->first();
            if (!$segment) {
                return response()->json(['success' => false, 'message' => 'Invalid segment for this video'], 422);
            }

            if (!$user->courses()->where('course_user.course_id', $course->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'User not enrolled in this course'], 403);
            }

            $progress = UserSegmentProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'video_id' => $videoId,
                    'segment_id' => $segmentId,
                ],
                ['completed' => true]
            );

            if (!$progress->completed) {
                $progress->update(['completed' => true]);
            }

            $progressPercentage = $user->calculateCourseProgress($course);
            $completedCount = $user->completedSegments($course)->count();

            return response()->json([
                'success' => true,
                'progress' => $progressPercentage,
                'completedCount' => $completedCount,
                'message' => 'Segment marked as completed!',
            ]);
        } catch (\Exception $e) {
            Log::error('Mark watched error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'video_id' => $request->input('video_id'),
                'segment_id' => $request->input('segment_id'),
            ]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function certificate(Course $course)
    {
        if (!Auth::check() || !Auth::user()->courses()->where('course_user.course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to access this certificate.');
        }

        $progress = Auth::user()->calculateCourseProgress($course);
        if ($progress < 100) {
            return redirect()->route('courses.learn', $course->id)->with('error', 'Complete all segments to download your certificate.');
        }

        $data = [
            'user' => Auth::user(),
            'course' => $course,
            'date' => now()->format('F d, Y'),
        ];

        try {
            $pdf = PDF::loadView('courses.certificate', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => false,
                    'tempDir' => storage_path('app/temp'),
                    'chroot' => base_path(),
                ]);

            return $pdf->download("certificate-{$course->title}-{$data['user']->full_name}.pdf");
        } catch (\Exception $e) {
            Log::error('Certificate generation error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback: Return HTML view
            return view('courses.certificate', $data)->with('error', 'PDF generation failed. Displaying certificate preview.');
        }
    }

    public function showRatingForm(Course $course)
    {
        if (!Auth::check() || !Auth::user()->courses()->where('course_user.course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to rate this course.');
        }

        $progress = Auth::user()->calculateCourseProgress($course);
        if ($progress < 100) {
            return redirect()->route('courses.learn', $course->id)->with('error', 'Complete all segments to rate this course.');
        }

        if (CourseRating::where('user_id', Auth::id())->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->id)->with('info', 'You have already rated this course.');
        }

        $questions = [
            ['text' => 'How clear was the course content?', 'options' => ['Very Clear', 'Clear', 'Neutral', 'Unclear', 'Very Unclear']],
            ['text' => 'How engaging was the instructor?', 'options' => ['Very Engaging', 'Engaging', 'Neutral', 'Boring', 'Very Boring']],
            ['text' => 'How useful were the course materials?', 'options' => ['Very Useful', 'Useful', 'Neutral', 'Not Useful', 'Not Useful At All']],
            ['text' => 'How well-organized was the course?', 'options' => ['Very Organized', 'Organized', 'Neutral', 'Disorganized', 'Very Disorganized']],
            ['text' => 'How relevant was the content to your goals?', 'options' => ['Very Relevant', 'Relevant', 'Neutral', 'Irrelevant', 'Very Irrelevant']],
            ['text' => 'How effective were the assessments?', 'options' => ['Very Effective', 'Effective', 'Neutral', 'Ineffective', 'Very Ineffective']],
            ['text' => 'How accessible was the platform?', 'options' => ['Very Accessible', 'Accessible', 'Neutral', 'Inaccessible', 'Very Inaccessible']],
            ['text' => 'How would you rate the pace of the course?', 'options' => ['Too Fast', 'Just Right', 'Neutral', 'Too Slow', 'Much Too Slow']],
            ['text' => 'How likely are you to recommend this course?', 'options' => ['Very Likely', 'Likely', 'Neutral', 'Unlikely', 'Very Unlikely']],
            ['text' => 'How satisfied are you overall?', 'options' => ['Very Satisfied', 'Satisfied', 'Neutral', 'Dissatisfied', 'Very Dissatisfied']],
        ];

        return view('courses.rate', compact('course', 'questions'));
    }

    public function storeRating(Request $request, Course $course)
    {
        $request->validate([
            'responses' => 'required|array|size:10',
            'responses.*' => 'required|string',
            'feedback' => 'nullable|string|max:1000',
        ]);

        CourseRating::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'responses' => $request->input('responses'),
            'feedback' => $request->input('feedback'),
        ]);

        return redirect()->route('courses.show', $course->id)->with('success', 'Thank you for your feedback!');
    }
}