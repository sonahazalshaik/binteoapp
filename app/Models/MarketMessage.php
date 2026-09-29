<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketMessage extends Model
{
    protected $fillable = [
        'marketplace_id',
        'user_id',
        'sender_marketplace_id',
        'sender_type',
        'message',
        'is_read',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function senderMarketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'sender_marketplace_id');
    }
}
