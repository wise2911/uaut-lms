<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title', 'description', 'department', 'video_url',
        'instructor_name', 'topics', 'learning_outcomes', 'thumbnail_url'
    ];

    protected $casts = [
        'topics' => 'array',
        'learning_outcomes' => 'array'
    ];

    // Accessor to safely get topics
    public function getTopicsAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    // Accessor to safely get learning outcomes
    public function getLearningOutcomesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_course')
                   ->withPivot('progress')
                   ->withTimestamps();
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}