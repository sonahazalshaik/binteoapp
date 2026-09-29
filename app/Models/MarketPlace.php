<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MarketPlace extends Model
{
    use HasFactory;

    protected $table = 'market_places';
    protected $fillable = [
        'type',
        'name',
        'image',
        'cover_image',
        'number',
        'email',
        'location',
        'rating',
        'projects_count',
        'satisfaction_rate',
        'more_info',
        'years_of_experience',
        'skills',
        'portfolio_url',
        'website_url',
        'business_name',
        'business_type',
        'status',
        'password',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'is_featured',
        'featured_until',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
        'skills' => 'json',
        'years_of_experience' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a slug if missing when saving
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $slug = \Illuminate\Support\Str::slug($model->name ?: 'talent');
                if (empty($slug)) {
                    $slug = 'talent';
                }
                
                $originalSlug = $slug;
                
                // Only append a random string if the slug already exists
                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $originalSlug . '-' . strtolower(\Illuminate\Support\Str::random(4));
                }
                
                $model->slug = $slug;
            }
        });
    }

    /**
     * Default attribute values
     */
    protected $attributes = [
        'status' => 1, // 1 = active, 0 = inactive
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    protected $hidden = [
        'password',
    ];

    public function galleries()
    {
        return $this->hasMany(MarketGallery::class, 'marketplace_id');
    }

    public function services()
    {
        return $this->hasMany(MarketService::class, 'marketplace_id');
    }

    public function portfolios()
    {
        return $this->hasMany(MarketPortfolio::class, 'marketplace_id');
    }

    public function contacts()
    {
        return $this->hasMany(MarketContact::class, 'marketplace_id');
    }

    public function ratings()
    {
        return $this->hasMany(MarketplaceRating::class, 'marketplace_id');
    }

    public function getAverageRatingAttribute()
    {
        if (array_key_exists('ratings_avg_rating', $this->attributes)) {
            return round((float) $this->attributes['ratings_avg_rating'] ?: 0, 1);
        }
        return round($this->ratings()->average('rating') ?: 0, 1);
    }

    public function getTotalRatingsAttribute()
    {
        if (array_key_exists('ratings_count', $this->attributes)) {
            return (int) $this->attributes['ratings_count'];
        }
        return $this->ratings()->count();
    }

    public function getRatingBreakdownAttribute()
    {
        $breakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $ratings = $this->ratings()->selectRaw('rating, count(*) as count')->groupBy('rating')->pluck('count', 'rating')->toArray();
        foreach ($ratings as $stars => $count) {
            if (isset($breakdown[$stars])) {
                $breakdown[$stars] = $count;
            }
        }
        return $breakdown;
    }

    public function photoUrl()
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) {
            if (!self::r2Configured() && \App\Helpers\ImageHelper::isR2EndpointUrl($this->image)) {
                return null;
            }
            return \App\Helpers\ImageHelper::getPhotoUrl($this->image);
        }
        return getImage(getFilePath('uploads') . '/' . $this->image, getFileSize('uploads'));
    }

    public function coverUrl()
    {
        if (!$this->cover_image) return null;
        if (str_starts_with($this->cover_image, 'http')) {
            if (!self::r2Configured() && \App\Helpers\ImageHelper::isR2EndpointUrl($this->cover_image)) {
                return null;
            }
            return \App\Helpers\ImageHelper::getPhotoUrl($this->cover_image);
        }
        return getImage(getFilePath('uploads') . '/' . $this->cover_image, getFileSize('uploads'));
    }

    /**
     * Whether R2 storage is configured in the environment.
     */
    public static function r2Configured(): bool
    {
        return (bool) (config('filesystems.disks.r2.bucket') && config('filesystems.disks.r2.endpoint'));
    }



    public function getInitials()
    {
        $name = trim($this->name);
        if (empty($name)) return '??';
        
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    public function getAvatarColor()
    {
        $hash = abs(crc32($this->email . $this->name));
        $hue = $hash % 360;
        return "hsl({$hue}, 65%, 45%)";
    }

    public function subscriptions()
    {
        return $this->hasMany(MarketSubscription::class, 'marketplace_id');
    }

    public function activeSubscription()
    {
        $active = $this->subscriptions->filter(function ($sub) {
            return $sub->end_date === null || $sub->end_date > now();
        })->sortByDesc('created_at')->first();

        return $active;
    }


    public function hasContactAccess()
    {
        $active = $this->activeSubscription();
        if (!$active) return false;
        
        return $active->plan ? $active->plan->contact_access : false;
    }

    public function hasFeaturedAccess()
    {
        if ($this->is_featured) return true;
        
        $active = $this->activeSubscription();
        if (!$active) return false;
        
        return $active->plan ? $active->plan->is_featured_plan : false;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function deletionRequest()
    {
        return $this->hasOne(DeletionRequest::class, 'marketplace_id');
    }

    /**
     * Delete marketplace record along with all its related data and assets.
     */
    public function deleteWithAssets(): ?bool
    {
        // 1. Delete Profile Image
        if ($this->image) {
            $path = getFilePath('uploads') . '/' . $this->image;
            if (file_exists($path)) @unlink($path);
        }

        // 2. Delete Gallery Images
        foreach ($this->galleries as $gallery) {
            if ($gallery->image) {
                $path = getFilePath('uploads') . '/' . $gallery->image;
                if (file_exists($path)) @unlink($path);
            }
        }

        // 3. Delete Relations
        $this->galleries()->delete();
        $this->services()->delete();
        $this->contacts()->delete();
        $this->subscriptions()->delete();
        $this->deletionRequest()->delete();

        return $this->delete();
    }
}
