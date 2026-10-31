<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketContact extends Model
{
    use HasFactory;

    protected $table = 'market_contacts';

    protected $fillable = [
        'marketplace_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }
}
