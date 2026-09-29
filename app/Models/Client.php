<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'email',
        'password',
        'image',
        'cover_photo',
        'bio',
        'channel_name',
        'country',
        'status',
        'ev',
        'sv',
        'kv',
        'is_monetized',
        'monetization_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullnameAttribute()
    {
        return trim($this->firstname . ' ' . $this->lastname) ?: $this->name;
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'user_id');
    }

    public function channel()
    {
        return $this->hasOne(Channel::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    /**
     * Get the channels the user is subscribed to
     */
    public function subscribedChannels()
    {
        return $this->belongsToMany(Channel::class, 'subscriptions', 'user_id', 'channel_id');
    }
}
