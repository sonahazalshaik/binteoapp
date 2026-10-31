<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OttPlan extends Model
{
    protected $fillable = ['name', 'price', 'description', 'duration', 'ott_access', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
        'ott_access' => 'boolean',
        'status' => 'boolean'
    ];
}
