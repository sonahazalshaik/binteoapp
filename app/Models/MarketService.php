<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketService extends Model
{
    use HasFactory;

    protected $table = 'market_services';

    protected $fillable = [
        'marketplace_id',
        'service_img',
        'service_name',
        'service_brief',
    ];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'marketplace_id');
    }

    public function photoUrl()
    {
        if (!$this->service_img) return null;
        if (str_starts_with($this->service_img, 'http')) {
            return \App\Helpers\ImageHelper::getPhotoUrl($this->service_img);
        }
        return getImage(getFilePath('marketplaceService') . '/' . $this->service_img, getFileSize('marketplaceService'));
    }
}

