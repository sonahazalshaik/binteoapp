<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelView extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'user_id', 'ip', 'dwell_seconds'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
