<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',
        'channel_plan_id',
        'plan_name',
        'amount',
        'starts_at',
        'expires_at',
        'payment_method',
        'trx',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function plan()
    {
        return $this->belongsTo(ChannelPlan::class, 'channel_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->where(function ($q) {
            $q->where('expires_at', '>', now())->orWhereNull('expires_at');
        });
    }

    public function isActive(): bool
    {
        return (bool) $this->status && ($this->expires_at === null || now()->lt($this->expires_at));
    }
}
