<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayCurrency extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['gateway_parameter' => 'object'];

    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }
}
