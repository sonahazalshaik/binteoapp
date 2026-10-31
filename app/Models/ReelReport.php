<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelReport extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'user_id', 'reason', 'description', 'status', 'admin_feedback', 'is_notified'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 1);
    }
}
