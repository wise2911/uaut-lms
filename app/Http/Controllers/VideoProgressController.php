<?php

namespace App\Http\Controllers;

use App\Models\UserVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    public function completeSegment(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'segment_index' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $progress = UserVideoProgress::firstOrCreate([
            'user_id' => $user->id,
            'video_id' => $request->video_id,
            'segment_index' => $request->segment_index,
        ]);

        if (!$progress->completed_at) {
            $progress->completed_at = now();
            $progress->save();

            // Update course progress
            $video = \App\Models\Video::find($request->video_id);
            $course = $video->course;
            $totalSegments = $course->videos->sum(function ($video) {
                return count($video->segments);
            });
            $completedSegments = UserVideoProgress::where('user_id', $user->id)
                ->whereIn('video_id', $course->videos->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();
            $progressPercentage = ($completedSegments / $totalSegments) * 100;
            $user->updateCourseProgress($course->id, $progressPercentage);
        }

        return response()->json(['success' => true]);
    }
}