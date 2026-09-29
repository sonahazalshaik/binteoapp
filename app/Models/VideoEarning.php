<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'date',
        'ad_impressions',
        'ad_clicks',
        'estimated_revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'estimated_revenue' => 'decimal:4',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
