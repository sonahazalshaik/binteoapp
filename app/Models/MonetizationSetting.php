<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetizationSetting extends Model
{
    protected $fillable = ['is_monetization_enabled', 'platform_commission'];

    protected $casts = [
        'is_monetization_enabled' => 'boolean',
        'platform_commission' => 'decimal:2',
    ];
}
