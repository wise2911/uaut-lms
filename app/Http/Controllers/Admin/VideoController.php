<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $courses = Course::with('videos')->get();
        return view('admin.videos.index', compact('courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.videos.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'new_course.title' => 'required|string|max:255',
            'new_course.description' => 'required|string',
            'new_course.department' => 'required|in:COBA,COEIT',
            'new_course.instructor_name' => 'required|string|max:255',
            'new_course.thumbnail' => 'nullable|image|mimes:jpeg,png|max:2048',
            'new_course.videos.preview.url' => 'required|string|max:255',
            'new_course.videos.segments.*.title' => 'required|string|max:255',
            'new_course.videos.segments.*.url' => 'required|string|max:255',
            'new_course.videos.segments.*.order' => 'required|integer|min:1',
        ]);

        // Handle thumbnail upload
        $thumbnailPath = $request->file('new_course.thumbnail')
            ? $request->file('new_course.thumbnail')->store('thumbnails', 'public')
            : null;

        // Create the Course
        $course = Course::create([
            'title' => $validated['new_course']['title'],
            'description' => $validated['new_course']['description'],
            'department' => $validated['new_course']['department'],
            'instructor_name' => $validated['new_course']['instructor_name'],
            'thumbnail' => $thumbnailPath,
        ]);

        // Create the Preview Video
        $previewVideo = $course->videos()->create([
            'title' => $course->title . ' Preview',
            'url' => $validated['new_course']['videos']['preview']['url'],
            'is_preview' => true,
        ]);

        // Create Video Segments
        if (isset($validated['new_course']['videos']['segments'])) {
            foreach ($validated['new_course']['videos']['segments'] as $segmentData) {
                $previewVideo->segments()->create([
                    'title' => $segmentData['title'],
                    'url' => $segmentData['url'],
                    'order' => $segmentData['order'],
                ]);
            }
        }

        return redirect()->route('admin.videos.index')->with('success', 'Course created successfully.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'is_preview' => 'boolean',
        ]);

        $video->update($request->all());

        return redirect()->route('admin.videos.edit', $video)->with('success', 'Video updated successfully.');
    }

    public function storeSegment(Request $request, Video $video)
    {
        $request->validate([
            'segments' => 'required|array',
            'segments.*.title' => 'required|string|max:255',
            'segments.*.video' => 'required|mimes:mp4|max:102400', // 100MB max
            'segments.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->file('segments') as $index => $segment) {
            $path = $segment['video']->store('videos/html', 'public');

            Segment::create([
                'video_id' => $video->id,
                'title' => $request->input("segments.$index.title"),
                'url' => $path,
                'order' => $request->input("segments.$index.order"),
            ]);
        }

        return redirect()->route('admin.videos.edit', $video)->with('success', 'Segments added successfully.');
    }

    public function destroySegment(Video $video, Segment $segment)
    {
        Storage::disk('public')->delete($segment->url);
        $segment->delete();

        return redirect()->route('admin.videos.edit', $video)->with('success', 'Segment deleted successfully.');
    }

    public function destroy(Video $video)
    {
        foreach ($video->segments as $segment) {
            Storage::disk('public')->delete($segment->url);
            $segment->delete();
        }
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}