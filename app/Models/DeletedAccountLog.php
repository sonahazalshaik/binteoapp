<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedAccountLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'email',
        'phone',
        'role',
        'final_balance',
        'reason',
        'custom_reason',
        'joined_at',
        'deleted_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'deleted_at' => 'datetime',
        'final_balance' => 'decimal:8',
    ];
}
