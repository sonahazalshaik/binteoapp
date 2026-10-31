<?php

namespace App\Services\Frontend;

use App\Models\Frontend;
use App\Models\Page;
use App\Models\BannerAd;

class SiteService
{
    public function captchaReload()
    {
        return loadCustomCaptcha();
    }

    public function placeholderImage($size = null)
    {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function policyPages($id, $slug): array
    {
        $policy = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return compact('policy', 'pageTitle');
    }

    public function pages($slug): array
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return compact('pageTitle', 'sections') + ['activeTemplate' => activeTemplate()];
    }

    public function bannerRedirect($slug)
    {
        $banner = BannerAd::where('slug', $slug)->firstOrFail();

        if (!$banner->link) {
            return null;
        }

        return $banner->link;
    }

    public function pwaManifest(): array
    {
        $general = gs();
        return [
            'name'             => $general->site_name,
            'short_name'       => $general->site_name,
            'start_url'        => route('home'),
            'background_color' => '#ffffff',
            'description'      => $general->site_name . ' Progressive Web App',
            'display'          => 'standalone',
            'theme_color'      => '#F97316',
            'icons'            => [
                [
                    'src'     => siteFavicon(),
                    'sizes'   => '512x512',
                    'type'    => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src'     => siteFavicon(),
                    'sizes'   => '192x192',
                    'type'    => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ]
        ];
    }
}


