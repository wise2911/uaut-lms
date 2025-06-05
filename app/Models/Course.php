<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'instructor_name',
        'department',
        'short_description',
        'thumbnail',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('progress');
    }

    public function video()
    {
        return $this->hasMany(Video::class);
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }
}