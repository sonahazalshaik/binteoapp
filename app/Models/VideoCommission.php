<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCommission extends Model
{
    protected $guarded = ['id'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchaser_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
