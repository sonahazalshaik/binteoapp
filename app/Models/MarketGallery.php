<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketGallery extends Model
{
    use HasFactory;

    protected $table = 'market_galleries';

    protected $fillable = [
        'marketplace_id',
        'market_gallery',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function photoUrl()
    {
        if (!$this->market_gallery) return null;
        if (str_starts_with($this->market_gallery, 'http')) {
            if (!\App\Models\MarketPlace::r2Configured() && \App\Helpers\ImageHelper::isR2EndpointUrl($this->market_gallery)) {
                return null;
            }
            return \App\Helpers\ImageHelper::getPhotoUrl($this->market_gallery);
        }
        return getImage(getFilePath('marketplaceGallery') . '/' . $this->market_gallery, getFileSize('marketplaceGallery'));
    }
}
