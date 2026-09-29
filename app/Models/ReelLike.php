<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelLike extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'user_id', 'is_like'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    // ── Scopes ──

    public function scopeLike($query)
    {
        return $query->where('is_like', 1);
    }

    public function scopeDislike($query)
    {
        return $query->where('is_like', 0);
    }
}
