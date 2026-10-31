<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use GlobalStatus;

    protected $fillable = ['name', 'slug', 'status'];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a slug if missing when saving
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name ?: 'category') . '-' . \Illuminate\Support\Str::random(6);
            }
        });

        // Global Scope: Never retrieve categories without slugs to prevent route errors
        static::addGlobalScope('has_slug', function ($builder) {
            $builder->whereNotNull('slug')->where('slug', '!=', '');
        });
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }

    public function reels()
    {
        return $this->hasMany(Reel::class);
    }
}
