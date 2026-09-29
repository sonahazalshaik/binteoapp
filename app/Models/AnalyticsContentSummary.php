<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsContentSummary extends Model
{
    protected $guarded = [];
    
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
