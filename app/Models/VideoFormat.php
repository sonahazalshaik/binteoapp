<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFormat extends Model
{
    use HasFactory;

    protected $fillable = ['video_id', 'resolution', 'path'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
