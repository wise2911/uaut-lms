<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSegmentProgress extends Model
{
    protected $fillable = ['user_id', 'course_id', 'video_id', 'segment_id', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}