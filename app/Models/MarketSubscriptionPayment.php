<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketSubscriptionPayment extends Model
{
    protected $table = 'market_subscription_payments';

    protected $fillable = [
        'marketplace_id',
        'plan_id',
        'amount',
        'gateway_code',
        'trx',
        'status',
        'detail',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function plan()
    {
        return $this->belongsTo(UserPlan::class, 'plan_id');
    }

    // Assuming a Gateway system exists or will be implemented
    public function gateway()
    {
        return $this->hasOne(Model::class, 'code', 'gateway_code'); // Stub
    }
}
