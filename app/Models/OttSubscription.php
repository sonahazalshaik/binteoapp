<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OttSubscription extends Model
{
    protected $fillable = ['user_id', 'ott_plan_id', 'plan_name', 'price', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ottPlan()
    {
        return $this->belongsTo(OttPlan::class, 'ott_plan_id');
    }
}
