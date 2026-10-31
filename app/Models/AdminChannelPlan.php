<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminChannelPlan extends Model
{
    use HasFactory;

    protected $table = 'admin_channel_plans';

    protected $fillable = [
        'name',
        'duration_type',
        'duration_days',
        'duration_months',
        'default_price',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'duration_days' => 'integer',
        'duration_months' => 'integer',
        'default_price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
