<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reported_user_id',
        'video_id',
        'comment_id',
        'reason',
        'description',
        'status',
        'admin_feedback',
        'is_notified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
