<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'slug', 'avatar', 'banner', 'description', 'social_links', 'subscribers_count', 'is_active'];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($channel) {
            if (empty($channel->slug) || $channel->isDirty('name')) {
                $channel->slug = static::generateUniqueSlug($channel->name, $channel->id);
            }
        });

        // Global Scope: Never retrieve channels without slugs to prevent route errors
        static::addGlobalScope('has_slug', function ($builder) {
            $builder->whereNotNull('channels.slug')->where('channels.slug', '!=', '');
        });
    }

    public static function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = str()->slug($name);
        $query = static::where('slug', 'LIKE', "{$slug}%");
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $count = $query->count();
        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }

    /**
     * Get the user who owns the channel.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'user_id', 'user_id');
    }

    public function reels()
    {
        return $this->hasMany(Reel::class, 'user_id', 'user_id');
    }

    /**
     * Get the subscribers for the channel.
     */
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'channel_id', 'user_id')->withPivot('notification_preference')->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Delete channel along with all its related data.
     */
    public function deleteWithAssets(): ?bool
    {
        // Delete avatar
        if ($this->avatar) {
            $path = getFilePath('channelAvatar') . '/' . $this->avatar;
            if (file_exists($path)) @unlink($path);
        }

        // Delete banner
        if ($this->banner) {
            $path = getFilePath('channelBanner') . '/' . $this->banner;
            if (file_exists($path)) @unlink($path);
        }

        // 1. Delete Relations
        $this->subscribers()->detach();
        $this->memberships()->delete();
        
        // Note: We typically DON'T delete the user's videos/reels here 
        // because they belong to the User, not necessarily exclusively to the channel 
        // (though in this app they are linked).

        return $this->delete();
    }

    public function avatarUrl()
    {
        return getImage(getFilePath('channelAvatar') . '/' . $this->avatar);
    }
}
