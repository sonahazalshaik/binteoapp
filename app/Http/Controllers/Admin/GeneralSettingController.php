<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\RequiredConfig;
use App\Models\Frontend;
use App\Models\Holiday;
use App\Rules\FileTypeValidate;
use App\Services\Admin\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralSettingController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }
    public function systemSetting()
    {
        $pageTitle = 'System Settings';
        $settings  = $this->service->getSystemSettingsJson();
        return view('admin.setting.system', compact('pageTitle', 'settings'));
    }
    public function general()
    {
        $pageTitle = 'General Setting';
        $data      = $this->service->getGeneralSettings();
        return view('admin.setting.general', array_merge(compact('pageTitle'), $data));
    }

    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name'           => 'required|string|max:40',
            'cur_text'            => 'required|string|max:40',
            'cur_sym'             => 'required|string|max:40',
            'minimum_subscribe'   => 'required|integer',
            'minimum_views'       => 'required|integer',
            'watch_hours'         => 'required|integer',
            'title'               => 'required|string',
            'description'         => 'required|string',
            'monetization_amount' => 'required|numeric|gte:0',
            'logo'                => ['nullable', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'logo_dark'           => ['nullable', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon'             => ['nullable', 'image', new \App\Rules\FileTypeValidate(['png'])],
            'max_upload_size'      => 'required|integer',
            'allowed_video_types'  => 'required|string',
            'default_video_quality'=> 'required|string',
            'bunny_api_key'        => 'nullable|string',
            'bunny_cdn_hostname'   => 'nullable|string',
            'bunny_video_library_id' => 'nullable|string',
            'bunny_reel_library_id'  => 'nullable|string',
            'bunny_video_collection_id' => 'nullable|string',
            'bunny_reel_collection_id'  => 'nullable|string',
            'firebase_apiKey'           => 'nullable|string',
            'firebase_authDomain'       => 'nullable|string',
            'firebase_projectId'        => 'nullable|string',
            'firebase_storageBucket'    => 'nullable|string',
            'firebase_messagingSenderId'=> 'nullable|string',
            'firebase_appId'            => 'nullable|string',
            'firebase_measurementId'    => 'nullable|string',
            'firebase_vapidKey'         => 'nullable|string',
            'razorpay_key'              => 'nullable|string',
            'razorpay_secret'           => 'nullable|string',
            'email1'                    => 'nullable|email',
            'email2'                    => 'nullable|email',
            'number'                    => 'nullable|string',
            'google_client_id'          => 'nullable|string',
            'google_client_secret'      => 'nullable|string',
            'google_redirect_uri'       => 'nullable|string',
            'google_analytics_id'        => 'nullable|string|max:100',
            'google_analytics_embed_url' => 'nullable|string',
            'giphy_api_key'             => 'nullable|string',
            'brevo_api_key'             => 'nullable|string',
            'brevo_sender_email'        => 'nullable|email',
            'brevo_sender_name'         => 'nullable|string',
            'zeptomail_api_key'         => 'nullable|string',
            'mail_provider'             => 'nullable|in:brevo,zepto',
            'app_version'               => 'nullable|string|max:20',
            'ppv_creator_commission_percent' => 'required|numeric|min:0|max:100',
            'ppv_teaser_duration_seconds' => 'required|integer|min:0',
            'mini_ott_status' => 'required|in:0,1',
        ]);

        $this->service->updateGeneralSettings($request);

        $notify[] = ['success', 'Master system configuration updated successfully'];
        return back()->withNotify($notify);
    }

    public function systemConfiguration()
    {
        $pageTitle = 'System Configuration';
        return view('admin.setting.configuration', compact('pageTitle'));
    }

    public function systemConfigurationSubmit(Request $request)
    {
        $this->service->updateSystemConfiguration($request);
        $notify[] = ['success', 'System configuration updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'logo_dark' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon'  => ['nullable', 'image', new FileTypeValidate(['png'])],
            'splash_logo' => ['nullable', 'image', new FileTypeValidate(['png', 'jpg', 'jpeg'])],
        ]);

        $this->service->updateLogoIcon($request);

        $notify[] = ['success', 'Logo & favicon updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCss()
    {
        $pageTitle   = 'Custom CSS';
        $file        = activeTemplate(true) . 'css/custom.css';
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);
        } else {
            $fileContent = null;
        }
        return view('admin.setting.custom_css', compact('pageTitle', 'fileContent'));
    }

    public function customCssSubmit(Request $request)
    {
        $this->service->updateCustomCss($request->css);
        $notify[] = ['success', 'CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function sitemap()
    {
        $pageTitle   = 'Sitemap XML';
        $file        = 'sitemap.xml';
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);
        } else {
            $fileContent = null;
        }
        return view('admin.setting.sitemap', compact('pageTitle', 'fileContent'));
    }

    public function sitemapSubmit(Request $request)
    {
        $this->service->updateSitemap($request->sitemap);
        $notify[] = ['success', 'Sitemap updated successfully'];
        return back()->withNotify($notify);
    }

    public function robot()
    {
        $pageTitle   = 'Robots TXT';
        $file        = 'robots.txt';
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);
        } else {
            $fileContent = null;
        }
        return view('admin.setting.robots', compact('pageTitle', 'fileContent'));
    }

    public function robotSubmit(Request $request)
    {
        $this->service->updateRobotsTxt($request->robots);
        $notify[] = ['success', 'Robots txt updated successfully'];
        return back()->withNotify($notify);
    }

    public function maintenanceMode()
    {
        $pageTitle   = 'Maintenance Mode';
        $maintenance = $this->service->getMaintenanceSettings();
        return view('admin.setting.maintenance', compact('pageTitle', 'maintenance'));
    }

    public function maintenanceModeSubmit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'image'       => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $this->service->updateMaintenanceMode($request);

        $notify[] = ['success', 'Maintenance mode updated successfully'];
        return back()->withNotify($notify);
    }

    public function cookie()
    {
        $pageTitle = 'GDPR Cookie';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        return view('admin.setting.cookie', compact('pageTitle', 'cookie'));
    }

    public function cookieSubmit(Request $request)
    {
        $request->validate([
            'short_desc'  => 'required|string|max:255',
            'description' => 'required',
        ]);

        $this->service->updateCookie($request);

        $notify[] = ['success', 'Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }

    public function socialiteCredentials()
    {
        $pageTitle = 'Social Login Credentials';
        return view('admin.setting.social_credential', compact('pageTitle'));
    }

    public function updateSocialiteCredentialStatus($key)
    {
        try {
            $this->service->toggleSocialiteCredentialStatus($key);
        } catch (\Throwable $th) {
            abort(404);
        }

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function updateSocialiteCredential(Request $request, $key)
    {
        try {
            $this->service->updateSocialiteCredential($key, $request->client_id, $request->client_secret);
        } catch (\Throwable $th) {
            abort(404);
        }

        $notify[] = ['success', ucfirst($key) . ' credential updated successfully'];
        return back()->withNotify($notify);
    }

    public function adSetting()
    {
        $pageTitle = 'Ads Settings';
        return view('admin.setting.ad', compact('pageTitle'));
    }

    public function adSettingUpdate(Request $request)
    {
        $request->validate([
            'per_minute'           => 'required|numeric|gte:0',
            'ad_views'             => 'required|numeric|gte:0',
            'per_impression_spent' => 'nullable|numeric|gte:0',
            'per_click_spent'      => 'nullable|numeric|gte:0',
            'per_click_earn'       => 'required|numeric|gte:0',
            'per_impression_earn'  => 'required|numeric|gte:0',
            'ads_module'           => 'required|in:0,1',
            'ad_reach'             => 'nullable|numeric|gte:0',
            'ad_engagement'        => 'nullable|numeric|gte:0',
        ]);

        $this->service->updateAdSettings($request);

        $notify[] = ['success', 'Ads settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function holiday()
    {
        $holidays  = Holiday::paginate(getPaginate());
        $pageTitle = 'Holidays';
        return view('admin.setting.holiday', compact('holidays', 'pageTitle'));
    }

    public function offDaySubmit(Request $request)
    {
        try {
            $this->service->updateOffDays($request->off_days);
        } catch (\RuntimeException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Weekly Holiday Setting Updated'];
        return back()->withNotify($notify);
    }

    public function holidaySubmit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date'  => 'required|date',
        ]);

        $this->service->addHoliday($request->title, $request->date);

        $notify[] = ['success', 'Holiday added successfully'];
        return back()->withNotify($notify);
    }

    public function charge()
    {
        $pageTitle = 'Charge Setting';
        return view('admin.setting.charge', compact('pageTitle'));
    }

    public function chargeSetting(Request $request)
    {
        $request->validate([
            'video_sell_charge'    => 'required|numeric|gte:0|lt:100',
            'playlist_sell_charge' => 'required|numeric|gte:0|lt:100',
            'plan_sell_charge'     => 'required|numeric|gte:0|lt:100',
        ]);

        $this->service->updateChargeSettings($request->video_sell_charge, $request->playlist_sell_charge, $request->plan_sell_charge);

        $notify[] = ['success', 'Charge setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $this->service->deleteHoliday($id);
        $notify[] = ['success', 'Holiday deleted successfully'];
        return back()->withNotify($notify);
    }

    public function checkFFmpegInstallation()
    {
        $status = $this->service->checkFfmpeg();
        return response()->json(['status' => $status]);
    }

    public function keywordBlacklist()
    {
        $pageTitle = 'Keyword Blacklist';
        $keywords  = $this->service->getKeywordBlacklist();
        return view('admin.setting.keyword_blacklist', compact('pageTitle', 'keywords'));
    }

    public function storeKeyword(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:50|unique:keyword_blacklists,word'
        ]);

        $this->service->addKeyword($request->word);

        $notify[] = ['success', 'Keyword added to blacklist.'];
        return back()->withNotify($notify);
    }

    public function deleteKeyword($id)
    {
        $this->service->deleteKeyword($id);

        $notify[] = ['success', 'Keyword removed from blacklist.'];
        return back()->withNotify($notify);
    }
}

