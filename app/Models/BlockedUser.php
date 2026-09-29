<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'blocked_user_id', 'comment_text'];
    
    public function blocker() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function blocked() {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}
