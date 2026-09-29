<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['detail' => 'object'];

    public function gateway()
    {
        return $this->hasOneThrough(Gateway::class, GatewayCurrency::class, 'method_code', 'id', 'method_code', 'gateway_id');
    }

    public function gatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function plan()
    {
        return $this->belongsTo(UserPlan::class, 'plan_id');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', \App\Constants\Status::PAYMENT_SUCCESS);
    }

    public function scopePending($query)
    {
        return $query->where('status', \App\Constants\Status::PAYMENT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', \App\Constants\Status::PAYMENT_REJECT);
    }
}
