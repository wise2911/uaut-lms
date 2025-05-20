<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $fillable = ['video_id', 'title', 'url', 'order'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}