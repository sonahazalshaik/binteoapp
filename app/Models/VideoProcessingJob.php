<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoProcessingJob extends Model
{
    protected $fillable = [
        'video_id',
        'resolution',
        'status',
        'error_message',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
