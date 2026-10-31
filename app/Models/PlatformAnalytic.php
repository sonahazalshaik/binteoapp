<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformAnalytic extends Model
{
    protected $table = 'platform_analytics';

    protected $fillable = [
        'date',
        'total_views',
        'total_watch_time',
        'new_users',
        'new_videos',
        'total_revenue'
    ];

    protected $casts = [
        'date' => 'date',
        'total_revenue' => 'decimal:2',
    ];
}
