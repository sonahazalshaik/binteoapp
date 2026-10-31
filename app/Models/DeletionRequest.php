<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'marketplace_id',
        'reason',
        'custom_reason',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }
}
