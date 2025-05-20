<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'instructor_name', 'department', 'thumbnail', 'downloadable_file'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_course')->withPivot('progress');
    }
}