<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelHashtag extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'hashtag'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    // ── Scopes ──

    public function scopeByTag($query, string $tag)
    {
        return $query->where('hashtag', ltrim($tag, '#'));
    }
}
