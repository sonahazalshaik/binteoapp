<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;

class MonetizationController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Monetization Control Center';
        $ads = $this->service->getMonetizationAds();
        $settings = $this->service->getMonetizationSettings();
        $totalEarnings = $this->service->getTotalEarnings();
        $videoEarnings = $this->service->getVideoEarnings($request);
        
        return view('admin.monetization.index', compact('ads', 'settings', 'totalEarnings', 'videoEarnings', 'pageTitle'));
    }

    public function toggleGlobal(Request $request)
    {
        $this->service->toggleGlobalMonetization();

        $notify[] = ['success', 'Global monetization state updated.'];
        return back()->withNotify($notify);
    }

    public function storeAd(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:pre-roll,mid-roll,banner',
            'media_path' => 'nullable|string',
            'click_url' => 'required|url',
            'cpc' => 'numeric',
            'cpm' => 'numeric',
        ]);

        $this->service->createMonetizationAd($validated);

        $notify[] = ['success', 'Ad campaign created successfully.'];
        return back()->withNotify($notify);
    }

    public function toggleAd(Ad $ad)
    {
        $this->service->toggleMonetizationAd($ad);

        $notify[] = ['success', 'Ad status updated.'];
        return back()->withNotify($notify);
    }

    public function updateSettings(Request $request)
    {
        $this->service->updateMonetizationSettings($request);

        $notify[] = ['success', 'Monetization settings updated successfully.'];
        return back()->withNotify($notify);
    }

    public function destroyAd(Ad $ad)
    {
        $this->service->deleteMonetizationAd($ad);
        $notify[] = ['success', 'Ad campaign removed.'];
        return back()->withNotify($notify);
    }
}
