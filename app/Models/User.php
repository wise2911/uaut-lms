<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'profile_pic',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_course')
                    ->withPivot('progress')
                    ->withTimestamps();
    }

    public function videoProgress()
    {
        return $this->hasMany(UserVideoProgress::class);
    }

    public function getEnrollmentDate($courseId)
    {
        $enrollment = $this->courses()->where('course_id', $courseId)->first();
        return $enrollment ? $enrollment->pivot->created_at->format('M d, Y') : null;
    }

    public function updateCourseProgress($courseId, $progress)
    {
        $this->courses()->updateExistingPivot($courseId, [
            'progress' => min(100, max(0, $progress))
        ]);
    }
}