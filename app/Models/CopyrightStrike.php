<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopyrightStrike extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'reel_id',
        'reason',
        'status', // active, expired, appealed
        'is_read'
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
