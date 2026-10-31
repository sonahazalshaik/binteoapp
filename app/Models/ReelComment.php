<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelComment extends Model
{
    use HasFactory;

    protected $fillable = ['reel_id', 'user_id', 'parent_id', 'content', 'is_pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }

    public function parent()
    {
        return $this->belongsTo(ReelComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ReelComment::class, 'parent_id')->with('user');
    }

    public function mentions()
    {
        return $this->hasMany(ReelMention::class, 'comment_id');
    }

    public function likes()
    {
        return $this->hasMany(ReelCommentLike::class, 'comment_id');
    }
}
