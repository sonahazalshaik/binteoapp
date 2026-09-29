<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsDailyRollup extends Model
{
    protected $fillable = [
        'date',
        'total_uploads',
        'mau',
        'avg_watch_time_seconds',
        'avg_session_duration_seconds',
        'avg_vcr_percentage',
    ];

    protected $casts = [
        'date' => 'date',
        'avg_vcr_percentage' => 'decimal:2',
    ];
}
