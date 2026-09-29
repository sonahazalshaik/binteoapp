<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerAd;
use App\Services\Admin\MarketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BannerAdController extends Controller
{
    protected $validSlots = ['slot1', 'slot2', 'slot3', 'slot4'];

    protected $service;

    public function __construct(MarketingService $service)
    {
        $this->service = $service;
    }

    public function index($slot)
    {
        if (!in_array($slot, $this->validSlots)) abort(404);

        $pageTitle = strtoupper($slot) . ' Ads Management';
        $banners = $this->service->getBannersBySlot($slot);
        return view('admin.banners.index', compact('pageTitle', 'banners', 'slot'));
    }

    public function create($slot)
    {
        if (!in_array($slot, $this->validSlots)) abort(404);

        $pageTitle = 'Deploy New ' . strtoupper($slot) . ' Ad';
        return view('admin.banners.create', compact('pageTitle', 'slot'));
    }

    public function store(Request $request, $slot)
    {
        if (!in_array($slot, $this->validSlots)) abort(404);

        $request->validate([
            'banner_imgs'   => 'required|array',
            'banner_imgs.*' => 'required|image|max:5120',
            'link'          => 'nullable|url',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'nullable|boolean',
        ]);

        $deployedCount = $this->service->createBanner($slot, $request->all(), $request->file());

        $notify[] = ['success', $deployedCount . ' banner ads deployed successfully to ' . strtoupper($slot)];
        return to_route('admin.banners.index', $slot)->withNotify($notify);
    }

    public function edit($slot, $id)
    {
        if (!in_array($slot, $this->validSlots)) abort(404);

        $banner = BannerAd::where('slot', $slot)->findOrFail($id);
        $pageTitle = 'Refine Campaign: ' . strtoupper($slot);
        return view('admin.banners.edit', compact('pageTitle', 'banner', 'slot'));
    }

    public function update(Request $request, $slot, $id)
    {
        if (!in_array($slot, $this->validSlots)) abort(404);

        $request->validate([
            'banner_imgs'   => 'nullable|array',
            'banner_imgs.*' => 'required|image|max:5120',
            'link'          => 'nullable|url',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'nullable|boolean',
        ]);

        $banner = $this->service->updateBanner($slot, $id, $request->all(), $request->file());

        $notify[] = ['success', 'Banner campaign updated successfully'];
        return to_route('admin.banners.index', $slot)->withNotify($notify);
    }

    public function status($id)
    {
        $this->service->toggleBannerStatus($id);

        $notify[] = ['success', 'Banner status toggled successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $this->service->deleteBanner($id);

        $notify[] = ['success', 'Banner campaign purged from archives'];
        return back()->withNotify($notify);
    }
}
