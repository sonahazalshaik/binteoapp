<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelMention extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'mentioned_user_id', 'source', 'comment_id'];

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }

    public function comment()
    {
        return $this->belongsTo(ReelComment::class, 'comment_id');
    }
}
