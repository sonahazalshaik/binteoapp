<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['gateway_parameter' => 'object'];

    public function currencies()
    {
        return $this->hasMany(GatewayCurrency::class);
    }
}
