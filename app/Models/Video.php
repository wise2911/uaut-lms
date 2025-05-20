<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';

    protected $fillable = ['course_id', 'title', 'url', 'is_preview'];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }
}