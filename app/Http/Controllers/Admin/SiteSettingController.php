<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\Admin\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $settings = $this->service->getSiteSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::first();

        $request->validate([
            'site_name' => 'required|string|max:255',
            'max_upload_size' => 'required|integer',
            'allowed_video_types' => 'required|string',
            'default_video_quality' => 'required|string',
            'ads_enabled' => 'boolean',
            'monetization_enabled' => 'boolean',
            'logo' => 'nullable|image|max:2048'
        ]);

        $this->service->updateSiteSettings($request, $settings);

        $notify[] = ['success', 'System settings updated successfully.'];
        return back()->withNotify($notify);
    }
}
