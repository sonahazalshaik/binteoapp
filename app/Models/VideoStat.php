<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoStat extends Model
{
    protected $fillable = [
        'video_id',
        'views',
        'likes',
        'comments_count',
        'watch_time'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
