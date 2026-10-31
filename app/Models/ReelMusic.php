<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelMusic extends Model
{
    use HasFactory;

    protected $table = 'reel_music';

    protected $fillable = [
        'user_id', 'title', 'artist', 'file_path', 'duration',
        'cover_image', 'genre', 'usage_count', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($music) {
            if (empty($music->slug)) {
                $baseSlug = \Illuminate\Support\Str::slug($music->title ?: 'music');
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $music->id)->exists()) {
                    $slug = "{$baseSlug}-" . \Illuminate\Support\Str::random(4);
                    $counter++;
                    if ($counter > 10) {
                        $slug = $baseSlug . '-' . uniqid();
                    }
                }
                $music->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ── Relations ──

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reels()
    {
        return $this->hasMany(Reel::class, 'music_id');
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
