<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_video');
    }

    public function reels()
    {
        return $this->belongsToMany(Reel::class, 'playlist_reel');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $base = \Illuminate\Support\Str::slug($model->name ?: 'playlist');
                if (empty($base)) $base = 'playlist';
                
                $slug = $base;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $base . '-' . $counter;
                    $counter++;
                }
                $model->slug = $slug;
            }
        });
    }

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

    // --- Legacy Binteo Relations & Scopes ---
    public function scopeAuthUser($query) {
        return $query->where('user_id', auth()->id());
    }

    public function scopePublic($query) {
        return $query->where('visibility', \App\Constants\Status::PUBLIC);
    }

    public function scopePlaylistForSell($query) {
        return $query->where('playlist_subscription', \App\Constants\Status::YES)->whereNotNull('price');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_playlists');
    }

    public function statusBadge(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(function () {
            $html = '';
            if ($this->visibility == \App\Constants\Status::PUBLIC) {
                $html = '<span class="badge badge--success">' . '<i class="las la-globe"></i>' . trans(' Public') . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . '<i class="las la-lock"></i>' . trans(' Private') . '</span>';
            }
            return $html;
        });
    }
}
