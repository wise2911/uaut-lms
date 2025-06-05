<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'name',
        'email',
        'password',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')->withPivot('progress');
    }

    public function segmentProgress()
    {
        return $this->hasMany(UserSegmentProgress::class);
    }

    public function completedSegments(Course $course)
    {
        return $this->segmentProgress()
            ->where('course_id', $course->id)
            ->where('completed', true);
    }

    public function calculateCourseProgress(Course $course)
    {
        $course->load('video.segments');
        $totalSegments = $course->video->flatMap->segments->count();
        if ($totalSegments === 0) {
            return 0;
        }

        $completedSegments = $this->completedSegments($course)->count();
        $progress = ($completedSegments / $totalSegments) * 100;

        $this->courses()->updateExistingPivot($course->id, ['progress' => round($progress, 2)]);

        return round($progress, 2);
    }
}