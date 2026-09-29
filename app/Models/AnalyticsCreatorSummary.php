<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsCreatorSummary extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
