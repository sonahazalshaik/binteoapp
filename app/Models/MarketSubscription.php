<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketSubscription extends Model
{
    use HasFactory;

    protected $table = 'market_subscriptions';

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    protected $fillable = [
        'marketplace_id',
        'plan_id',
        'plan_name',
        'plan_price',
        'plan_duration',
        'start_date',
        'end_date',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function plan()
    {
        return $this->belongsTo(UserPlan::class, 'plan_id');
    }
}
