<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoWatchLog extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'reel_id',
        'session_token',
        'watch_duration_seconds',
        'completion_percentage',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'completion_percentage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}
