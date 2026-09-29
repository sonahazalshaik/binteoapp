<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory, \App\Traits\GlobalStatus;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description', 'video_path', 'hls_path',
        'thumbnail_path', 'views_count', 'duration', 'is_premium', 'price', 'pricing_tier', 'status', 'moderation_status',
        'visibility', 'scheduled_at', 'location', 'is_age_restricted', 'captions_path', 'is_featured', 'is_trending',
        'bunny_id', 'bunny_status', 'stock_video', 'is_shorts_video', 'language'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a slug if missing when saving
        // + safety net: guarantee uniqueness with random suffix only (no id / bunny_id exposed).
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->title ?: 'video') . '-' . \Illuminate\Support\Str::random(6);
            }
            $base = $model->slug;
            $attempts = 0;
            while ($attempts < 10 && static::withoutGlobalScope('has_slug')->where('slug', $model->slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                $model->slug = $base . '-' . \Illuminate\Support\Str::random(5);
                $base = $model->slug;
                $attempts++;
            }
        });

        // Global Scope: Never retrieve records without slugs to prevent route errors
        static::addGlobalScope('has_slug', function ($builder) {
            $builder->whereNotNull('slug')->where('slug', '!=', '');
        });
    }

    /**
     * Check if this video is hosted on Bunny Stream.
     */
    public function isBunnyVideo(): bool
    {
        return !empty($this->bunny_id);
    }

    /**
     * Check if the video is considered published.
     */
    public function isPublished(): bool
    {
        $publishedStatuses = [\App\Constants\Status::PUBLISHED, 'published', 'ready'];
        if (!in_array($this->status, $publishedStatuses)) {
            return false;
        }
        
        if ($this->scheduled_at && $this->scheduled_at->isFuture()) {
            return false;
        }
        
        if ($this->isBunnyVideo() && $this->bunny_status !== 'ready') {
            return false;
        }
        
        return true;
    }

    /**
     * Check if the video has an active copyright strike.
     */
    public function isStruck(): bool
    {
        return $this->copyrightStrikes()->where('status', 'active')->exists() ||
               ($this->user && $this->user->copyrightStrikes()->where('status', 'active')->count() >= 3);
    }

    /**
     * Get the Bunny Stream embed URL for this video.
     */
    public function getBunnyEmbedUrl(bool $autoplay = false): ?string
    {
        if (!$this->isBunnyVideo()) {
            return null;
        }

        return app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->getEmbedUrl($this->bunny_id, $autoplay);
    }

    /**
     * Get the direct video URL (Prioritizing 1080p).
     */
    public function getVideoUrl()
    {
        if ($this->isBunnyVideo()) {
            $cdnHostname = gs('bunny_cdn_hostname');
            return "https://{$cdnHostname}/{$this->bunny_id}/play_1080p.mp4";
        }
        return asset(getFilePath('video') . '/' . $this->video_path);
    }

    /**
     * Get the HLS Play URL for Bunny Stream.
     */
    public function getPlayUrl()
    {
        if ($this->isBunnyVideo()) {
            return app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->generateSignedUrl($this->bunny_id);
        }
        return null;
    }

    /**
     * Get the Dynamic Thumbnail URL.
     */
    public function thumbnailUrl()
    {
        if ($this->isBunnyVideo()) {
            return app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->getThumbnailUrl($this->bunny_id);
        }
        return getImage(getFilePath('thumbnail') . '/' . $this->thumbnail_path, getFileSize('thumbnail'));
    }

    /**
     * Get the animated preview URL.
     */
    public function previewUrl()
    {
        if ($this->isBunnyVideo()) {
            return app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->getPreviewUrl($this->bunny_id);
        }
        return $this->thumbnailUrl();
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl();
    }

    /**
     * Delete video along with all its assets (Bunny Stream, Local Files).
     */
    public function deleteWithAssets(): ?bool
    {
        // 1. Delete Bunny Stream Asset
        if ($this->isBunnyVideo() && $this->bunny_id) {
            try {
                app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->deleteVideo($this->bunny_id);
            } catch (\Exception $e) {
                \Log::error("Failed to delete Bunny Video: " . $e->getMessage());
            }
        }

        // 2. Delete Local Video/HLS
        if ($this->video_path) {
            $path = getFilePath('video') . '/' . $this->video_path;
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        if ($this->hls_path) {
            $path = getFilePath('video') . '/' . $this->hls_path;
            if (is_dir($path)) {
                \Illuminate\Support\Facades\File::deleteDirectory($path);
            }
        }

        // 3. Delete Thumbnail
        if ($this->thumbnail_path) {
            $path = getFilePath('thumbnail') . '/' . $this->thumbnail_path;
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // 4. Delete Relations
        $this->comments()->delete();
        $this->likes()->delete();
        $this->tags()->delete();
        $this->notInterestedBy()->delete();
        $this->copyrightStrikes()->delete();
        $this->viewLogs()->delete();
        $this->earnings()->delete();
        
        // Conditional Cleanup for tables that may be missing
        if (\Illuminate\Support\Facades\Schema::hasTable('user_reactions')) { $this->userReactions()->delete(); }
        if (\Illuminate\Support\Facades\Schema::hasTable('subtitles')) { $this->subtitles()->delete(); }
        if (\Illuminate\Support\Facades\Schema::hasTable('video_files')) { $this->videoFiles()->delete(); }
        if (\Illuminate\Support\Facades\Schema::hasTable('ad_play_durations')) { $this->adPlayDurations()->delete(); }

        // Detach from playlists
        $this->playlists()->detach();

        return $this->delete();
    }

    /**
     * Get the preview animation URL (WebP).
     */
    public function getPreviewUrl()
    {
        return $this->previewUrl();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->hasOne(Channel::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function authLikes()
    {
        return $this->hasMany(Like::class)->where('user_id', auth()->id());
    }

    public function authPlaylists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_video')
            ->where('playlists.user_id', auth()->id());
    }

    public function isLikedBy(?User $user)
    {
        if (!$user) {
            return false;
        }
        if ($this->relationLoaded('authLikes')) {
            return $this->authLikes->isNotEmpty();
        }
        return $this->likes()->where('user_id', $user->id)->exists();
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
            ->whereHas('videos', function($q) {
                $q->where('video_id', $this->id);
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
            ->whereHas('videos', function($q) {
                $q->where('video_id', $this->id);
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
            ->whereHas('videos', function($q) {
                $q->where('video_id', $this->id);
            })
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get formatted duration for display — always returns actual duration.
     * Falls back to Bunny length if stored duration is 00:00.
     */
    public function getFormattedDurationAttribute(): string
    {
        $d = trim((string)($this->attributes['duration'] ?? ''));
        if ($d !== '' && $d !== '00:00' && $d !== '0:00' && $d !== '00:0' && $d !== '0' && $d !== '00:00:00' && $d !== '--:--') {
            $parts = explode(':', $d);
            if (count($parts) === 3) {
                $hours = (int) $parts[0];
                if ($hours > 0) {
                    return $hours . ':' . $parts[1] . ':' . $parts[2];
                }
                return $parts[1] . ':' . $parts[2];
            }
            return $d;
        }
        return '--:--';
    }

    public static function formatSecondsToDuration(int|float $seconds): string
    {
        $seconds = (int) round($seconds);
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        if ($h > 0) {
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        }
        return sprintf('%02d:%02d', $m, $s);
    }

    /**
     * Single source view count — prefers synced Bunny views if available, else app count.
     * Use this everywhere to avoid app 5 vs Bunny 0 desync.
     */
    public function getSyncedViewsCountAttribute(): int
    {
        // If bunny_views column exists and is not null, prefer it; otherwise use views_count
        // This allows gradual migration to Bunny as source without extra API per request
        if (array_key_exists('bunny_views', $this->attributes) && $this->attributes['bunny_views'] !== null) {
            return max((int) $this->views_count, (int) $this->attributes['bunny_views']);
        }
        return (int) $this->views_count;
    }

    /**
     * Check if the video is restricted (Age Restricted or Reported)
     */
    public function isRestricted(): bool
    {
        return $this->is_age_restricted || ($this->relationLoaded('reports') ? $this->reports->isNotEmpty() : $this->reports()->exists());
    }

    /**
     * Check if the video is a new release (within 15 days).
     */
    public function isNewRelease(): bool
    {
        return $this->created_at->diffInDays() <= 15;
    }

    /**
     * Get the resolution badge label.
     */
    public function getResolutionBadge(): string
    {
        if ($this->isBunnyVideo()) {
            return $this->is_premium ? '4K HDR' : '1080p FHD';
        }
        return 'HD';
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'video_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_video');
    }

    public function formats()
    {
        return $this->hasMany(VideoFormat::class);
    }

    public function earnings()
    {
        return $this->hasMany(VideoEarning::class);
    }

    public function viewLogs()
    {
        return $this->hasMany(ViewLog::class);
    }

    public function watchHistory()
    {
        return $this->hasMany(WatchHistory::class);
    }

    // --- Legacy Binteo Relations & Scopes ---
    public function storage() {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function playlist() {
        return $this->belongsTo(Playlist::class);
    }

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_videos')->withTimestamps();
    }

    public function userReactions() {
        return $this->hasMany(UserReaction::class);
    }

    public function allComments() {
        return $this->hasMany(Comment::class);
    }

    public function videoFiles() {
        return $this->hasMany(VideoFile::class);
    }

    public function subtitles() {
        return $this->hasMany(Subtitle::class);
    }

    public function adPlayDurations() {
        return $this->hasMany(AdPlayDuration::class);
    }


    public function tags() {
        return $this->hasMany(VideoTag::class, 'video_id');
    }

    public function copyrightStrikes()
    {
        return $this->hasMany(CopyrightStrike::class);
    }

    public function getActiveStrikeId()
    {
        return $this->copyrightStrikes()->where('status', 'active')->first()?->id;
    }

    public function scopeAuthUser($query) {
        return $query->where($this->getTable() . '.user_id', auth()->id());
    }

    public function scopePublished($query) {
        return $query->whereIn($this->getTable() . '.status', [\App\Constants\Status::PUBLISHED, 'published', 'ready'])
                     ->where(function($q) {
                         $q->whereNull('bunny_id') // Local videos
                           ->orWhere('bunny_status', 'ready'); // Bunny videos must be ready
                     })
                     ->where(function($q) {
                         $q->whereNull('scheduled_at')
                           ->orWhere('scheduled_at', '<=', now());
                     });
    }

    public function scopeOnlyPlaylist($query) {
        return $query->where($this->getTable() . '.is_only_playlist', \App\Constants\Status::YES);
    }

    public function scopeWithoutOnlyPlaylist($query) {
        return $query->where($this->getTable() . '.is_only_playlist', \App\Constants\Status::NO);
    }

    public function scopePublic($query) {
        return $query->where($this->getTable() . '.visibility', \App\Constants\Status::PUBLIC);
    }

    public function scopePrivate($query) {
        return $query->where($this->getTable() . '.visibility', \App\Constants\Status::PRIVATE);
    }

    public function scopeStock($query) {
        return $query->where($this->getTable() . '.stock_video', \App\Constants\Status::YES);
    }

    public function scopeFree($query) {
        return $query->where($this->getTable() . '.stock_video', \App\Constants\Status::NO);
    }

    public function scopeRegular($query) {
        return $query->where($this->getTable() . '.is_shorts_video', \App\Constants\Status::NO);
    }

    public function scopeShorts($query) {
        return $query->where($this->getTable() . '.is_shorts_video', \App\Constants\Status::YES);
    }

    public function scopeDraft($query) {
        return $query->whereIn($this->getTable() . '.status', [\App\Constants\Status::DRAFT, 'draft']);
    }

    public function scopeTrending($query) {
        return $query->where($this->getTable() . '.is_trending', 1);
    }

    public function scopeFeatured($query) {
        return $query->where($this->getTable() . '.is_featured', 1);
    }

    public function scopeRejected($query) {
        return $query->where($this->getTable() . '.status', \App\Constants\Status::REJECTED);
    }

    public function reactionLikeCount(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn() => $this->userReactions->where('is_like', \App\Constants\Status::YES)->count()
        );
    }

    public function isLikedByAuthUser(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn() => $this->userReactions->where('user_id', auth()->id())->where('is_like', \App\Constants\Status::YES)->count()
        );
    }

    public function isUnlikedByAuthUser(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn() => $this->userReactions->where('user_id', auth()->id())->where('is_like', \App\Constants\Status::NO)->count()
        );
    }

    public function statusBadge(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(function () {
            $html = '';
            if (!$this->status || $this->status == 'draft') {
                $html = '<span class="badge badge--warning">' . trans(' Draft') . '</span>';
            } else if ($this->status == \App\Constants\Status::REJECTED) {
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans(' Published') . '</span>';
            }
            return $html;
        });
    }
    public function notInterestedBy()
    {
        return $this->hasMany(NotInterestedVideo::class);
    }

    public function scopeForUser($query)
    {
        $query->activeCreator()->published()
              ->whereDoesntHave('copyrightStrikes', function($q) {
                  $q->where('status', 'active');
              });

        // Visibility handling: Show ONLY public videos in public-facing feeds
        $query->where(function($q) {
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

    public function scopeForChannelFeed($query)
    {
        $query->activeCreator()
              ->whereIn($this->getTable() . '.status', [\App\Constants\Status::PUBLISHED, 'published', 'ready'])
              ->where(function($q) {
                  $q->whereNull('bunny_id') // Local videos
                    ->orWhere('bunny_status', 'ready'); // Bunny videos must be ready
              })
              ->whereDoesntHave('copyrightStrikes', function($q) {
                  $q->where('status', 'active');
              });

        // Visibility handling: Show ONLY public videos in public-facing feeds
        $query->where(function($q) {
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

    public function scopeActiveCreator($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', \App\Constants\Status::USER_ACTIVE)
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
    public static function extractMentions(?string $text): array
    {
        if (!$text) return [];
        preg_match_all('/@([\w]+)/', $text, $matches);
        return array_unique($matches[1] ?? []);
    }
}
