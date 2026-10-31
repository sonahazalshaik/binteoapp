<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'mail_config' => 'object',
        'sms_config' => 'object',
        'global_shortcodes' => 'object',
        'socialite_credentials' => 'object',
        'firebase_config' => 'object',
        'config_progress' => 'object',
        'vc_warning' => 'object',
        'ad_config' => 'object',
        'off_days' => 'array',
        'ftp'                   => 'object',
        'wasabi'                => 'object',
        'digital_ocean'         => 'object',
        'cloudflare_config'     => 'object',
        'razorpay_config'       => 'object',
        'support_config'        => 'object',
        'brevo_config'          => 'object',
        'zeptomail_config'      => 'object'
    ];


    protected $hidden = ['email_template','mail_config','sms_config','system_info'];

    public function scopeSiteName($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function(){
            \Cache::forget('GeneralSetting');
        });
    }

    /**
     * Handle Bunny API Key Encryption/Decryption
     */
    protected function bunnyApiKey(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : null,
            set: fn ($value) => $value ? encrypt($value) : null,
        );
    }
}
