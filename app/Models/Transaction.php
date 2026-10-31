<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'video_id', 'amount', 'post_balance', 'charge',
        'trx_type', 'details', 'trx', 'remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

}
