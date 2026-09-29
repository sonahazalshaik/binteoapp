<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory, \App\Traits\GlobalStatus;

    protected $fillable = [
        'user_id', 'category_id', 'music_id', 'title', 'slug', 'description',
        'video_path', 'compressed_video_path', 'thumbnail_path', 'duration',
        'views_count', 'likes_count', 'comments_count', 'shares_count',
        'visibility', 'status', 'moderation_status', 'is_age_restricted',
        'is_trending', 'location', 'audio_name', 'audio_path',
        'allow_comments', 'allow_duet', 'allow_stitch',
        'is_compressed', 'compression_status',
        'bunny_id', 'bunny_status', 'processing_bunny_id',
        'parent_id', 'music_source', 'music_start_time', 'original_reel_id',
        'global_music_url', 'global_music_title', 'global_music_artist', 'global_music_thumbnail',
        'language', 'storage_path'
    ];

    protected static function boot()
    {
        parent::boot();

        // 1. Automatically generate a slug if missing when saving
        static::saving(function ($reel) {
            if (empty($reel->slug)) {
                $reel->slug = \Illuminate\Support\Str::slug($reel->title ?: 'reel') . '-' . \Illuminate\Support\Str::random(6);
            }
        });

        // 2. Global Scope: Never retrieve reels without slugs to prevent route errors
        static::addGlobalScope('has_slug', function ($builder) {
            $builder->whereNotNull('slug')->where('slug', '!=', '');
        });
    }


    protected $casts = [
        'is_age_restricted' => 'boolean',
        'is_trending'       => 'boolean',
        'allow_comments'    => 'boolean',
        'allow_duet'        => 'boolean',
        'allow_stitch'      => 'boolean',
        'is_compressed'     => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function isBunnyReel(): bool
    {
        return !empty($this->bunny_id);
    }

    public function getBunnyEmbedUrl(bool $autoplay = false): ?string
    {
        if (!$this->isBunnyReel()) return null;
        return app(\App\Services\BunnyStreamService::class)->useReelLibrary()->getEmbedUrl($this->bunny_id, $autoplay);
    }

    /**
     * Get the playable video URL — Bunny CDN or local fallback.
     */
    public function getVideoUrl(): string
    {
        if (!empty($this->storage_path)) {
            $pullZone = gs('bunny_reels_pull_zone') ?: 'reelscdn.b-cdn.net';
            return "https://{$pullZone}/{$this->storage_path}";
        }

        if ($this->isBunnyReel()) {
            $cdnHostname = gs('bunny_cdn_hostname');
            return "https://{$cdnHostname}/{$this->bunny_id}/play_1080p.mp4";
        }

        $path = $this->compressed_video_path ?: $this->video_path;
        return asset(getFilePath('reel') . '/' . $path);
    }

    /**
     * Get the Dynamic Thumbnail URL.
     */
    public function thumbnailUrl()
    {
        if ($this->isBunnyReel()) {
            return app(\App\Services\BunnyStreamService::class)->useReelLibrary()->getThumbnailUrl($this->bunny_id);
        }
        return getImage(getFilePath('reelThumbnail') . '/' . $this->thumbnail_path, getFileSize('reelThumbnail'));
    }

    /**
     * Get the HLS Play URL for Bunny Stream.
     */
    public function getPlayUrl()
    {
        if (!empty($this->storage_path)) {
            $pullZone = gs('bunny_reels_pull_zone') ?: 'reelscdn.b-cdn.net';
            return "https://{$pullZone}/{$this->storage_path}";
        }

        if ($this->isBunnyReel()) {
            $cdnHostname = gs('bunny_cdn_hostname');
            return "https://{$cdnHostname}/{$this->bunny_id}/playlist.m3u8";
        }
        
        // Fallback for local reels (HLS not supported locally unless you implement it)
        $path = $this->compressed_video_path ?? $this->video_path;
        if ($path) {
            return asset(getFilePath('reel') . '/' . $path);
        }

        return null;
    }

    /**
     * Get the thumbnail URL for the reel.
     * Bunny Stream auto-generates thumbnail.jpg; Bunny Storage does NOT.
     * Worker Storage reels MUST have thumbnail_path uploaded from browser canvas,
     * otherwise falls back to default.png and logs for audit.
     */
    public function getThumbnailUrl()
    {
        if ($this->thumbnail_path) {
            return getImage(getFilePath('reelThumbnail') . '/' . $this->thumbnail_path);
        }

        if ($this->isBunnyReel()) {
            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname', 'iframe.mediadelivery.net');
            if ($cdnHostname) {
                return "https://{$cdnHostname}/{$this->bunny_id}/thumbnail.jpg";
            }
        }

        if (!empty($this->storage_path)) {
            $pullZone = gs('bunny_reels_pull_zone') ?: 'reelscdn.b-cdn.net';
            return "https://{$pullZone}/{$this->storage_path}#t=0.5";
        }

        return asset('assets/images/default.png');
    }



    /**
     * Delete reel along with all its assets (Bunny Stream, Local Files).
     */
    public function deleteWithAssets(): ?bool
    {
        // 1. Delete Bunny Stream Asset
        if ($this->isBunnyReel() && $this->bunny_id) {
            try {
                app(\App\Services\BunnyStreamService::class)->useReelLibrary()->deleteVideo($this->bunny_id);
            } catch (\Exception $e) {
                \Log::error("Failed to delete Bunny Reel: " . $e->getMessage());
            }
        }

        // 1b. Delete Bunny Storage Asset
        if (!empty($this->storage_path)) {
            try {
                $storageZone = gs('bunny_reels_storage_zone');
                $accessKey = gs('bunny_reels_storage_access_key');
                $region = gs('bunny_reels_storage_region');
                $regionClean = strtolower(trim($region ?? ''));
                $host = (empty($regionClean) || in_array($regionClean, ['de', 'main', 'falkenstein'])) 
                    ? 'storage.bunnycdn.com' 
                    : "{$regionClean}.storage.bunnycdn.com";

                $deleteUrl = "https://{$host}/{$storageZone}/{$this->storage_path}";

                \Illuminate\Support\Facades\Http::withHeaders([
                    'AccessKey' => $accessKey,
                ])
                ->timeout(10)
                ->delete($deleteUrl);

                \Log::info("[REEL-DELETE] Deleted storage video from Bunny Storage", ['reel_id' => $this->id, 'path' => $this->storage_path]);
            } catch (\Exception $e) {
                \Log::error("Failed to delete Bunny Storage Reel asset: " . $e->getMessage());
            }
        }

        // 2. Delete Local Video Files
        if ($this->video_path) {
            $path = getFilePath('reel') . '/' . $this->video_path;
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        if ($this->compressed_video_path) {
            $path = getFilePath('reel') . '/' . $this->compressed_video_path;
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // 3. Delete Thumbnail
        if ($this->thumbnail_path) {
            $path = getFilePath('reelThumbnail') . '/' . $this->thumbnail_path;
            if (gs('is_storage')) {
                try {
                    \Illuminate\Support\Facades\Storage::disk('r2')->delete($path);
                } catch (\Exception $e) {
                    \Log::error("Failed to delete R2 Reel thumbnail: " . $e->getMessage());
                }
            }
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // 4. Delete Audio
        if ($this->audio_path) {
            $path = getFilePath('reel') . '/' . $this->audio_path;
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // 5. Delete Relations
        $this->comments()->delete();
        $this->likes()->delete();
        $this->hashtags()->delete();
        $this->mentions()->delete();
        $this->tags()->delete();
        $this->reports()->delete();
        $this->views()->delete();
        $this->shares()->delete();
        
        // Detach from playlists
        $this->playlists()->detach();

        return $this->delete();
    }

    public function getPreviewUrl()
    {
        if ($this->isBunnyReel()) {
            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname', 'iframe.mediadelivery.net');
            return "https://{$cdnHostname}/{$this->bunny_id}/preview.webp";
        }
        return $this->getThumbnailUrl();
    }



    // ── Relations ──

    public function notInterestedBy()
    {
        return $this->hasMany(NotInterestedReel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->hasOne(Channel::class, 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function music()
    {
        return $this->belongsTo(ReelMusic::class, 'music_id');
    }

    public function comments()
    {
        return $this->hasMany(ReelComment::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_reel');
    }

    public function likes()
    {
        return $this->hasMany(ReelLike::class);
    }

    public function hashtags()
    {
        return $this->hasMany(ReelHashtag::class);
    }

    public function mentions()
    {
        return $this->hasMany(ReelMention::class);
    }

    public function tags()
    {
        return $this->hasMany(ReelTag::class);
    }

    public function reports()
    {
        return $this->hasMany(ReelReport::class);
    }

    public function views()
    {
        return $this->hasMany(ReelView::class);
    }

    public function shares()
    {
        return $this->hasMany(ReelShare::class);
    }

    public function parent()
    {
        return $this->belongsTo(Reel::class, 'parent_id');
    }

    public function duets()
    {
        return $this->hasMany(Reel::class, 'parent_id')->where('is_duet', true);
    }

    // ── Scopes ──

    public function scopeAuthUser($query)
    {
        return $query->where($this->getTable() . '.user_id', auth()->id());
    }

    public function scopePublished($query)
    {
        return $query->whereIn($this->getTable() . '.status', [Status::PUBLISHED, 'published', 'ready'])
                     ->where(function($q) {
                          $q->whereNull('bunny_id') // Local reels
                            ->orWhere('bunny_status', 'ready'); // Bunny reels must be ready
                     });
    }

    public function scopeForUser($query)
    {
        $query->activeCreator()->published()->where(function($q) {
            $table = $this->getTable();
            $q->where($table . '.visibility', \App\Constants\Status::PUBLIC)
              ->orWhere($table . '.visibility', '0')
              ->orWhere($table . '.visibility', 'public');
        });

        if (auth()->check()) {
            return $query->whereDoesntHave('notInterestedBy', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        
        return $query;
    }

    public function scopeDraft($query)
    {
        return $query->where($this->getTable() . '.status', Status::DRAFT);
    }

    public function scopeLiked($query)
    {
        return $query->whereHas('likes', function($q) {
            $q->where('is_like', Status::YES);
        });
    }

    public function scopeRejected($query)
    {
        return $query->where($this->getTable() . '.status', Status::REJECTED);
    }

    public function scopePublic($query)
    {
        return $query->where($this->getTable() . '.visibility', Status::PUBLIC);
    }

    public function scopePrivate($query)
    {
        return $query->where($this->getTable() . '.visibility', Status::PRIVATE);
    }

    public function scopeTrending($query)
    {
        return $query->where($this->getTable() . '.is_trending', true);
    }

    // ── Accessors ──

    public function authLikes()
    {
        return $this->hasMany(ReelLike::class)->where('user_id', auth()->id())->where('is_like', Status::YES);
    }

    public function authPlaylists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_reel')
            ->where('playlists.user_id', auth()->id());
    }

    public function isLikedBy($user): bool
    {
        if (!$user) return false;
        if ($this->relationLoaded('authLikes')) {
            return $this->authLikes->isNotEmpty();
        }
        return $this->likes()->where('user_id', $user->id)->where('is_like', Status::YES)->exists();
    }
    public function isInWatchLater(?User $user)
    {
        if (!$user) {
            return false;
        }
        if ($this->relationLoaded('authPlaylists')) {
            return $this->authPlaylists->contains(function ($playlist) {
                return stripos($playlist->name, 'watch later') !== false;
            });
        }
        return $user->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->whereHas('reels', function($q) {
                $q->where('reel_id', $this->id);
            })
            ->exists();
    }
    public function isInAnyPlaylist(?User $user)
    {
        if (!$user) {
            return false;
        }
        if ($this->relationLoaded('authPlaylists')) {
            return $this->authPlaylists->isNotEmpty();
        }
        return $user->playlists()
            ->whereHas('reels', function($q) {
                $q->where('reel_id', $this->id);
            })
            ->exists();
    }
    public function getPlaylistMembershipIds(?User $user)
    {
        if (!$user) {
            return [];
        }
        if ($this->relationLoaded('authPlaylists')) {
            return $this->authPlaylists->pluck('id')->toArray();
        }
        return $user->playlists()
            ->whereHas('reels', function($q) {
                $q->where('reel_id', $this->id);
            })
            ->pluck('id')
            ->toArray();
    }

    /**
     * Check if the reel is restricted (Age Restricted or Reported)
     */
    public function isRestricted(): bool
    {
        return $this->is_age_restricted || ($this->relationLoaded('reports') ? $this->reports->isNotEmpty() : $this->reports()->exists());
    }
    public function reactionLikeCount(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn() => $this->likes->where('is_like', Status::YES)->count()
        );
    }

    public function isLikedByAuthUser(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn() => $this->likes->where('user_id', auth()->id())->where('is_like', Status::YES)->count()
        );
    }

    public function statusBadge(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(function () {
            if (!$this->status) {
                return '<span class="badge badge--warning">' . trans('Draft') . '</span>';
            } elseif ($this->status == Status::REJECTED) {
                return '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            }
            return '<span class="badge badge--success">' . trans('Published') . '</span>';
        });
    }

    /**
     * Extract #hashtags from description text.
     */
    public static function extractHashtags(?string $text): array
    {
        if (!$text) return [];
        preg_match_all('/#([\w\p{L}]+)/u', $text, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Extract @mentions from description text.
     */
    public static function extractMentions(?string $text): array
    {
        if (!$text) return [];
        preg_match_all('/@([\w]+)/', $text, $matches);
        return array_unique($matches[1] ?? []);
    }
    /**
     * Get dynamic badge HTML based on reel status/popularity.
     */
    public function getBadgeHtml()
    {
        // 1. Hot/Trending Badge
        if ($this->is_trending || $this->views_count > 1000) {
            return '<div class="absolute top-2 left-2 px-2.5 py-1 bg-gradient-to-r from-[#ff571a] to-rose-500 rounded-lg text-[8px] font-black text-white uppercase tracking-[0.15em] shadow-lg shadow-[#ff571a]/30 flex items-center gap-1 z-30">
                        <span class="material-symbols-rounded text-[10px]">local_fire_department</span>
                        Hot
                    </div>';
        }

        // 2. New Badge (Last 48 hours)
        if ($this->created_at > now()->subDays(2)) {
            return '<div class="absolute top-2 left-2 px-2.5 py-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-lg text-[8px] font-black text-white uppercase tracking-[0.15em] shadow-lg shadow-blue-500/30 flex items-center gap-1 z-30">
                        <span class="material-symbols-rounded text-[10px]">new_releases</span>
                        New
                    </div>';
        }

        // 3. Viral Badge (High view count threshold)
        if ($this->views_count > 5000) {
            return '<div class="absolute top-2 left-2 px-2.5 py-1 bg-gradient-to-r from-purple-600 to-pink-500 rounded-lg text-[8px] font-black text-white uppercase tracking-[0.15em] shadow-lg shadow-purple-500/30 flex items-center gap-1 z-30">
                        <span class="material-symbols-rounded text-[10px]">trending_up</span>
                        Viral
                    </div>';
        }

        return "";
    }

    public function scopeActiveCreator($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', Status::USER_ACTIVE)
              ->where(function ($sq) {
                  $sq->whereDoesntHave('copyrightStrikes', function ($ssq) {
                      $ssq->where('status', 'active');
                  })
                  ->orWhereHas('copyrightStrikes', function ($ssq) {
                      $ssq->where('status', 'active');
                  }, '<', 3);
              });
        });
    }
}
