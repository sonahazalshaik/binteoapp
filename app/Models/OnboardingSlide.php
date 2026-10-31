<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingSlide extends Model
{
    use HasFactory;

    protected $table = 'onboarding_slides';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'sort_order',
    ];

    /**
     * Dynamically append the absolute URL of the slide image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            // Case 1: Stored in Cloudflare R2 (full HTTP URL)
            if (str_starts_with($this->image_path, 'http')) {
                return \App\Helpers\ImageHelper::getPhotoUrl($this->image_path);
            }

            // Case 2: Static asset reference for Flutter client (loaded locally)
            if (str_starts_with($this->image_path, 'assets/')) {
                return $this->image_path;
            }

            // Case 3: Standard local public storage path
            return \App\Helpers\ImageHelper::getPhotoUrl($this->image_path);
        }
        return null;
    }

    protected $appends = ['image_url'];
}
