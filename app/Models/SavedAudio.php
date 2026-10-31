<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedAudio extends Model
{
    protected $table = 'saved_audios';
    protected $fillable = ['user_id', 'reel_music_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reelMusic()
    {
        return $this->belongsTo(ReelMusic::class, 'reel_music_id');
    }
}
