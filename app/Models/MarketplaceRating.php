<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceRating extends Model
{
    protected $fillable = ['marketplace_id', 'user_id', 'rating'];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
