<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelTag extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'tag'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}
