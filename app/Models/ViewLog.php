<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'video_id', 'reel_id', 'device', 'country', 'watch_time', 'is_completed'];

    protected $dates = ['updated_at'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
