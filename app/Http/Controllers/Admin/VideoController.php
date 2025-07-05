<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    public function index()
    {
        $courses = Course::with('video.segments')->paginate(10);
        return view('admin.videos.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'new_course.title' => 'required|string|max:255',
            'new_course.description' => 'required|string',
            'new_course.department' => 'required|string|max:255',
            'new_course.instructor_name' => 'required|string|max:255',
            'new_course.price' => 'required|numeric|min:0',
            'new_course.thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'new_course.video.preview.url' => 'required|string|max:255',
            'new_course.video.segments.*.title' => 'required|string|max:255',
            'new_course.video.segments.*.url' => 'required|string|max:255',
            'new_course.video.segments.*.order' => 'required|integer|min:1',
        ]);

        try {
            $courseData = [
                'title' => $request->input('new_course.title'),
                'description' => $request->input('new_course.description'),
                'department' => $request->input('new_course.department'),
                'instructor_name' => $request->input('new_course.instructor_name'),
                'price' => $request->input('new_course.price'),
            ];

            if ($request->hasFile('new_course.thumbnail')) {
                $courseData['thumbnail'] = $request->file('new_course.thumbnail')->store('thumbnails', 'public');
            }

            $course = Course::create($courseData);

            $video = Video::create([
                'course_id' => $course->id,
                'title' => 'Preview',
                'url' => $request->input('new_course.video.preview.url'),
                'is_preview' => true,
            ]);

            foreach ($request->input('new_course.video.segments', []) as $segmentData) {
                Segment::create([
                    'video_id' => $video->id,
                    'title' => $segmentData['title'],
                    'url' => $segmentData['url'],
                    'order' => $segmentData['order'],
                ]);
            }

            return redirect()->route('admin.videos.index')->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            Log::error('Course creation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create course. Please try again.'])->withInput();
        }
    }

    public function edit($id)
    {
        $course = Course::with('video.segments')->findOrFail($id);
        return view('admin.videos.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'instructor_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $course = Course::findOrFail($id);
            $courseData = [
                'title' => $request->title,
                'description' => $request->description,
                'department' => $request->department,
                'instructor_name' => $request->instructor_name,
                'price' => $request->price,
                'thumbnail' => $course->thumbnail,
            ];

            if ($request->hasFile('thumbnail')) {
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $courseData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $course->update($courseData);

            return redirect()->route('admin.videos.index')->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            Log::error('Course update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update course. Please try again.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->video()->delete();
            $course->delete();
            return redirect()->route('admin.videos.index')->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Course deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete course. Please try again.']);
        }
    }

    public function storeSegment(Request $request, $videoId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
            'is_preview' => 'nullable|boolean',
        ]);

        try {
            Segment::create([
                'video_id' => $videoId,
                'title' => $request->title,
                'url' => $request->url,
                'order' => $request->order,
                'is_preview' => $request->boolean('is_preview', false),
            ]);
            return redirect()->back()->with('success', 'Segment added successfully.');
        } catch (\Exception $e) {
            Log::error('Segment creation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to add segment. Please try again.']);
        }
    }

    public function destroySegment($videoId, $segmentId)
    {
        try {
            $segment = Segment::where('video_id', $videoId)->findOrFail($segmentId);
            $segment->delete();
            return redirect()->back()->with('success', 'Segment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Segment deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete segment. Please try again.']);
        }
    }
}