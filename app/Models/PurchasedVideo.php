<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedVideo extends Model
{


    public function video(){
        return $this->belongsTo(Video::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
}
