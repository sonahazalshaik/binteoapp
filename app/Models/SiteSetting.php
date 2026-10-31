<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo_path',
        'max_upload_size',
        'allowed_video_types',
        'default_video_quality',
        'ads_enabled',
        'monetization_enabled'
    ];

    protected $casts = [
        'ads_enabled' => 'boolean',
        'monetization_enabled' => 'boolean',
        'max_upload_size' => 'integer'
    ];
}
