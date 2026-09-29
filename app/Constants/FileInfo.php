<?php

namespace App\Constants;

class FileInfo {

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
     */

    public function fileInfo() {
        $data['withdrawVerify'] = [
            'path' => 'assets/images/verify/withdraw',
        ];
        $data['depositVerify'] = [
            'path' => 'assets/images/verify/deposit',
        ];
        $data['verify'] = [
            'path' => 'assets/verify',
        ];
        $data['default'] = [
            'path' => 'assets/images/default.png',
        ];
        $data['ticket'] = [
            'path' => 'assets/support',
        ];
        $data['logoIcon'] = [
            'path' => 'assets/images/logo_icon',
        ];
        $data['favicon'] = [
            'size' => '128x128',
        ];
        $data['extensions'] = [
            'path' => 'assets/images/extensions',
            'size' => '36x36',
        ];
        $data['seo'] = [
            'path' => 'assets/images/seo',
            'size' => '1180x600',
        ];
        $data['userProfile'] = [
            'path' => 'assets/images/user/profile',
            'size' => '300x300',
        ];
        $data['cover'] = [
            'path' => 'assets/images/user/cover',
            'size' => '1688x367',
        ];
        $data['adminProfile'] = [
            'path' => 'assets/admin/images/profile',
            'size' => '400x400',
        ];
        $data['push'] = [
            'path' => 'assets/images/push_notification',
        ];
        $data['appPurchase'] = [
            'path' => 'assets/in_app_purchase_config',
        ];
        $data['maintenance'] = [
            'path' => 'assets/images/maintenance',
            'size' => '660x325',
        ];
        $data['language'] = [
            'path' => 'assets/images/language',
            'size' => '50x50',
        ];
        $data['gateway'] = [
            'path' => 'assets/images/gateway',
            'size' => '',
        ];
        $data['withdrawMethod'] = [
            'path' => 'assets/images/withdraw_method',
            'size' => '',
        ];
        $data['pushConfig'] = [
            'path' => 'assets/admin',
        ];
        $data['empty'] = [
            'path' => 'assets/images',
        ];

        $data['video'] = [
            'path' => 'assets/videos',
        ];
        $data['adVideo'] = [
            'path' => 'assets/ad_videos',
        ];

        $data['adLogo'] = [
            'path' => 'assets/images/ad_logo',
            'size' => '30x30',
        ];

        $data['subtitle'] = [
            'path' => 'assets/subtitle',
        ];

        $data['thumbnail'] = [
            'path'  => 'assets/images/thumbnail',
            'size'  => '1250x700',
            'thumb' => '365x215',
        ];

        $data['videoThumbnail'] = [
            'path'  => 'assets/images/thumbnail',
            'size'  => '1250x700',
            'thumb' => '365x215',
        ];

        $data['uploads'] = [
            'path' => 'assets/images/marketplace',
            'size' => '300x300'
        ];

        $data['reel'] = [
            'path' => 'assets/reels',
        ];
        $data['reelThumbnail'] = [
            'path'  => 'assets/images/reel_thumbnails',
            'size'  => '720x1280',
            'thumb' => '270x480',
        ];
        $data['reelMusic'] = [
            'path' => 'assets/reel_music',
        ];

        $data['channelAvatar'] = [
            'path' => 'assets/images/user/profile',
            'size' => '300x300',
        ];

        $data['channelBanner'] = [
            'path' => 'assets/images/user/cover',
            'size' => '1688x367',
        ];

        $data['marketplaceGallery'] = [
            'path' => 'assets/images/marketplace/gallery',
            'size' => '1200x1500',
        ];

        $data['marketplaceService'] = [
            'path' => 'assets/images/marketplace/service',
            'size' => '800x600',
        ];

        return $data;
    }

}
