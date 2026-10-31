<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedPlan extends Model
{

    protected $fillable = [
        'user_id',
        'plan_id',
        'owner_id',
        'price',
        'trx',
        'expired_date'
    ];

    protected $casts = [
        'expired_date' => 'datetime'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
