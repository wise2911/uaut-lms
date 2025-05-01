<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
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

        $videos = Video::with('course')->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $request->validate([
            'new_course.title' => 'required|string|max:255',
            'new_course.description' => 'required|string',
            'new_course.department' => 'required|in:COBA,COEIT',
            'new_course.instructor_name' => 'required|string|max:255',
            'new_course.learning_outcomes' => 'required|array|min:1',
            'new_course.learning_outcomes.*' => 'required|string',
            'new_course.thumbnail' => 'nullable|image|mimes:jpeg,png|max:5120', // 5MB max
            'new_course.topics' => 'required|array|min:1',
            'new_course.topics.*.title' => 'required|string',
            'new_course.topics.*.duration' => 'required|string',
            'new_course.topics.*.lessons' => 'required|array|min:1',
            'new_course.topics.*.lessons.*' => 'required|string',
            'new_course.topics.*.videos' => 'required|array|min:1',
            'new_course.topics.*.videos.*.title' => 'required|string|max:255',
            'new_course.topics.*.videos.*.file' => 'required|file|mimes:mp4,mov,avi|max:102400', // 100MB max
            'new_course.topics.*.videos.*.order' => 'required|integer|min:1',
        ]);

        // Upload thumbnail to Cloudinary if provided
        $thumbnailUrl = null;
        if ($request->hasFile('new_course.thumbnail')) {
            $uploadedThumbnail = Cloudinary::upload($request->file('new_course.thumbnail')->getRealPath(), [
                'folder' => 'lms_thumbnails',
                'resource_type' => 'image',
            ]);
            $thumbnailUrl = $uploadedThumbnail->getSecurePath();
        }

        // Create course
        $course = Course::create([
            'title' => $request->new_course['title'],
            'description' => $request->new_course['description'],
            'department' => $request->new_course['department'],
            'instructor_name' => $request->new_course['instructor_name'],
            'learning_outcomes' => json_encode($request->new_course['learning_outcomes']),
            'topics' => json_encode(array_map(function ($topic) {
                return [
                    'title' => $topic['title'],
                    'duration' => $topic['duration'],
                    'lessons' => $topic['lessons'],
                ];
            }, $request->new_course['topics'])),
            'thumbnail_url' => $thumbnailUrl,
        ]);

        // Upload videos to Cloudinary and create video records
        foreach ($request->new_course['topics'] as $topicIndex => $topic) {
            foreach ($topic['videos'] as $videoData) {
                $uploadedVideo = Cloudinary::uploadVideo($videoData['file']->getRealPath(), [
                    'folder' => 'lms_videos',
                    'resource_type' => 'video',
                ]);

                $publicId = $uploadedVideo->getPublicId();
                $videoInfo = Cloudinary::getResource($publicId, ['resource_type' => 'video']);
                $duration = $videoInfo['duration'] ?? 0;

                $video = new Video([
                    'course_id' => $course->id,
                    'title' => $videoData['title'],
                    'cloudinary_url' => $uploadedVideo->getSecurePath(),
                    'topic_index' => $topicIndex,
                    'order' => $videoData['order'],
                    'duration' => $duration,
                ]);
                $video->segments = $video->generateSegments(1200); // 20 minutes = 1200 seconds
                $video->save();
            }
        }

        return redirect()->route('admin.videos.index')->with('success', 'Course and videos created successfully.');
    }

    public function destroy(Video $video)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        // Delete video from Cloudinary
        $publicId = basename($video->cloudinary_url, '.' . pathinfo($video->cloudinary_url, PATHINFO_EXTENSION));
        Cloudinary::destroy($publicId, ['resource_type' => 'video']);

        // Delete video record
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}