<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoProcessingOption extends Model
{
    protected $fillable = ['resolution', 'bitrate', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
