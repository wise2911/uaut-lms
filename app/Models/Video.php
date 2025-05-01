<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'course_id', 'title', 'cloudinary_url', 'topic_index', 'order', 'duration', 'segments'
    ];

    protected $casts = [
        'segments' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Generate segments for the video (e.g., 20-minute segments).
     * @param int $segmentLength Length of each segment in seconds (default: 20 minutes)
     * @return array
     */
    public function generateSegments($segmentLength = 1200)
    {
        if (!$this->duration) {
            return [];
        }

        $segments = [];
        $totalSegments = ceil($this->duration / $segmentLength);

        for ($i = 0; $i < $totalSegments; $i++) {
            $start = $i * $segmentLength;
            $end = min(($i + 1) * $segmentLength, $this->duration);
            $segments[] = ['start' => $start, 'end' => $end];
        }

        return $segments;
    }
}