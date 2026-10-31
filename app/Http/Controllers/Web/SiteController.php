<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Lib\Captcha;
use App\Services\Frontend\SiteService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected SiteService $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function captchaReload()
    {
        return $this->siteService->captchaReload();
    }

    public function placeholderImage($size = null)
    {
        return $this->siteService->placeholderImage($size);
    }

    public function policyPages($id, $slug)
    {
        $data = $this->siteService->policyPages($id, $slug);

        return view('frontend.policy', $data);
    }

    public function pages($slug)
    {
        $data = $this->siteService->pages($slug);

        return view('frontend.pages', $data);
    }

    public function bannerRedirect($slug)
    {
        $link = $this->siteService->bannerRedirect($slug);

        if (!$link) {
            return back()->withNotify([['error', 'This ad does not have a valid link.']]);
        }

        return redirect()->away($link);
    }

    public function pwaManifest()
    {
        return response()->json($this->siteService->pwaManifest());
    }
}
