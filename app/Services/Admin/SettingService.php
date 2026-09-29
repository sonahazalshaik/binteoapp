<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Lib\RequiredConfig;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\CronSchedule;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Holiday;
use App\Models\KeywordBlacklist;
use App\Models\Language;
use App\Models\SiteSetting;
use App\Models\UpdateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SettingService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getGeneralSettings(): array
    {
        return [
            'timezones' => timezone_identifiers_list(),
            'currentTimezone' => array_search(config('app.timezone'), timezone_identifiers_list()),
            'holidays' => Holiday::orderBy('day_off', 'desc')->get(),
            'siteSetting' => SiteSetting::first() ?? new SiteSetting(),
        ];
    }

    public function updateGeneralSettings(Request $request): void
    {
        $general = gs();

        $general->site_name = $request->site_name;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->minimum_subscribe = $request->minimum_subscribe;
        $general->minimum_views = $request->minimum_views;
        $general->watch_hours = $request->watch_hours;
        $general->vc_warning = [
            'title' => $request->title,
            'description' => $request->description,
        ];
        $general->monetization_status = $request->monetization_status ? Status::ENABLE : Status::DISABLE;
        $general->monetization_amount = $request->monetization_amount;
        $general->registration = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->ev = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->kv = $request->kv ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->agree = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->multi_language = $request->multi_language ? Status::ENABLE : Status::DISABLE;
        $general->is_storage = $request->is_storage ? Status::ENABLE : Status::DISABLE;
        $general->is_playlist_sell = $request->is_playlist_sell ? Status::ENABLE : Status::DISABLE;
        $general->is_monthly_subscription = $request->is_monthly_subscription ? Status::ENABLE : Status::DISABLE;
        $general->ffmpeg_status = $request->ffmpeg_status ? Status::ENABLE : Status::DISABLE;
        $general->ads_auto_approve = $request->ads_auto_approve ? Status::ENABLE : Status::DISABLE;
        $general->bunny_api_key = $request->bunny_api_key;
        $general->bunny_cdn_hostname = $request->bunny_cdn_hostname;
        $general->bunny_video_library_id = $request->bunny_video_library_id;
        $general->bunny_reel_library_id = $request->bunny_reel_library_id;
        $general->bunny_video_collection_id = $request->bunny_video_collection_id;
        $general->bunny_reel_collection_id = $request->bunny_reel_collection_id;
        $general->bunny_reels_storage_zone = $request->bunny_reels_storage_zone;
        $general->bunny_reels_storage_access_key = $request->bunny_reels_storage_access_key;
        $general->bunny_reels_storage_region = $request->bunny_reels_storage_region;
        $general->bunny_reels_pull_zone = $request->bunny_reels_pull_zone;
        $general->reels_duration_limit = $request->reels_duration_limit;
        $general->reels_compression_size = $request->reels_compression_size;
        $general->reels_max_upload_size = $request->reels_max_upload_size;
        $general->firebase_config = [
            'apiKey' => $request->firebase_apiKey,
            'authDomain' => $request->firebase_authDomain,
            'projectId' => $request->firebase_projectId,
            'storageBucket' => $request->firebase_storageBucket,
            'messagingSenderId' => $request->firebase_messagingSenderId,
            'appId' => $request->firebase_appId,
            'measurementId' => $request->firebase_measurementId,
            'vapidKey' => $request->firebase_vapidKey,
        ];
        $general->cloudflare_config = [
            'access_key' => $request->r2_access_key,
            'secret_key' => $request->r2_secret_key,
            'bucket' => $request->r2_bucket,
            'endpoint' => $request->r2_endpoint,
            'url' => $request->r2_url,
        ];
        $general->razorpay_config = [
            'key' => $request->razorpay_key,
            'secret' => $request->razorpay_secret,
        ];
        $general->support_config = [
            'email1' => $request->email1,
            'email2' => $request->email2,
            'number' => $request->number,
        ];
        $general->google_client_id = $request->google_client_id;
        $general->google_client_secret = $request->google_client_secret;
        $general->google_redirect_uri = $request->google_redirect_uri;
        $general->google_analytics_id = $request->google_analytics_id;
        $general->google_analytics_embed_url = $request->google_analytics_embed_url;
        $general->giphy_api_key = $request->giphy_api_key;
        $general->brevo_config = [
            'api_key' => $request->brevo_api_key,
            'sender_email' => $request->brevo_sender_email,
            'sender_name' => $request->brevo_sender_name,
        ];
        $general->zeptomail_config = [
            'api_key' => $request->zeptomail_api_key,
        ];
        $general->mail_provider = $request->mail_provider ?? 'brevo';
        $general->app_version = $request->app_version;
        $general->ppv_creator_commission_percent = $request->ppv_creator_commission_percent;
        $general->ppv_teaser_duration_seconds = $request->ppv_teaser_duration_seconds;
        $general->force_update = $request->force_update ? Status::ENABLE : Status::DISABLE;
        $general->mini_ott_status = (int) $request->mini_ott_status;
        $general->save();
        Cache::forget('GeneralSetting');
        try {
            \Illuminate\Support\Facades\Artisan::call('queue:restart');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('SettingService: failed to restart queue: ' . $e->getMessage());
        }

        $siteSetting = SiteSetting::first() ?? new SiteSetting();
        $siteSetting->site_name = $request->site_name;
        $siteSetting->max_upload_size = $request->max_upload_size;
        $siteSetting->allowed_video_types = $request->allowed_video_types;
        $siteSetting->default_video_quality = $request->default_video_quality;
        $siteSetting->ads_enabled = $request->ads_enabled ? true : false;
        $siteSetting->monetization_enabled = $request->monetization_status ? true : false;

        $path = getFilePath('logoIcon');
        if ($request->hasFile('logo')) {
            $filename = fileUploader($request->logo, $path, filename: 'logo.png');
            $siteSetting->logo_path = 'logoIcon/' . $filename;
        }
        if ($request->hasFile('logo_dark')) {
            fileUploader($request->logo_dark, $path, filename: 'logo_dark.png');
        }
        if ($request->hasFile('favicon')) {
            fileUploader($request->favicon, $path, filename: 'favicon.png');
        }

        $siteSetting->save();

        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = "' . ($request->timezone ?? 'UTC') . '" ?>';
        file_put_contents($timezoneFile, $content);

        RequiredConfig::configured('general_setting');
        RequiredConfig::configured('logo_favicon');
    }

    public function updateSystemConfiguration(Request $request): void
    {
        $general = gs();
        $general->kv = $request->kv ? Status::ENABLE : Status::DISABLE;
        $general->ev = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->pn = $request->pn ? Status::ENABLE : Status::DISABLE;
        $general->ffmpeg_status = $request->ffmpeg_status ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->registration = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->agree = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->multi_language = $request->multi_language ? Status::ENABLE : Status::DISABLE;
        $general->is_storage = $request->is_storage ? Status::ENABLE : Status::DISABLE;
        $general->is_playlist_sell = $request->is_playlist_sell ? Status::ENABLE : Status::DISABLE;
        $general->is_monthly_subscription = $request->is_monthly_subscription ? Status::ENABLE : Status::DISABLE;
        $general->ads_auto_approve = $request->ads_auto_approve ? Status::ENABLE : Status::DISABLE;
        $general->save();
        Cache::forget('GeneralSetting');
    }

    public function updateLogoIcon(Request $request): void
    {
        $path = getFilePath('logoIcon');

        if ($request->hasFile('logo')) {
            fileUploader($request->logo, $path, filename: 'logo.png');
        }
        if ($request->hasFile('logo_dark')) {
            fileUploader($request->logo_dark, $path, filename: 'logo_dark.png');
        }
        if ($request->hasFile('favicon')) {
            fileUploader($request->favicon, $path, filename: 'favicon.png');
        }
        if ($request->hasFile('splash_logo')) {
            $splashPath = public_path('assets/images');
            if (!file_exists($splashPath)) {
                mkdir($splashPath, 0755, true);
            }
            $filename = 'logo_splash.png';
            $request->splash_logo->move($splashPath, $filename);

            $general = gs();
            $general->splash_logo = 'assets/images/' . $filename;
            $general->save();
            Cache::forget('GeneralSetting');

            $siteSetting = SiteSetting::first() ?? new SiteSetting();
            $siteSetting->splash_logo_path = 'assets/images/' . $filename;
            $siteSetting->save();
        }

        RequiredConfig::configured('logo_favicon');
    }

    public function updateCustomCss(string $css): void
    {
        $file = activeTemplate(true) . 'css/custom.css';
        if (!file_exists($file)) {
            $dir = dirname($file);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            fopen($file, "w");
        }
        file_put_contents($file, $css);
    }

    public function updateSitemap(string $content): void
    {
        $file = 'sitemap.xml';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $content);
    }

    public function updateRobotsTxt(string $content): void
    {
        $file = 'robots.txt';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $content);
    }

    public function getMaintenanceSettings(): Frontend
    {
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        if (!$maintenance) {
            $maintenance = new Frontend();
            $maintenance->data_keys = 'maintenance.data';
            $maintenance->data_values = [
                'description' => 'Our system is currently under maintenance. We will be back soon.',
                'image' => null,
            ];
            $maintenance->save();
        }
        return $maintenance;
    }

    public function updateMaintenanceMode(Request $request): void
    {
        $general = gs();
        $general->maintenance_mode = $request->status ? Status::ENABLE : Status::DISABLE;
        $general->save();

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        $image = @$maintenance->data_values->image;

        if ($request->hasFile('image')) {
            $image = fileUploader($request->image, getFilePath('maintenance'), getFileSize('maintenance'), $image);
        } elseif ($request->should_remove_image) {
            $image = null;
        }

        $maintenance->data_values = [
            'description' => $request->description,
            'image' => $image,
        ];
        $maintenance->save();
    }

    public function updateCookie(Request $request): void
    {
        $cookie = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        $cookie->data_values = [
            'short_desc' => $request->short_desc,
            'description' => $request->description,
            'status' => $request->status ? Status::ENABLE : Status::DISABLE,
        ];
        $cookie->save();
    }

    public function getSocialiteCredentials()
    {
        return gs()->socialite_credentials;
    }

    public function toggleSocialiteCredentialStatus(string $key): void
    {
        $general = gs();
        $credentials = $general->socialite_credentials;
        $credentials->$key->status = $credentials->$key->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $general->socialite_credentials = $credentials;
        $general->save();
    }

    public function updateSocialiteCredential(string $key, string $clientId, string $clientSecret): void
    {
        $general = gs();
        $credentials = $general->socialite_credentials;
        $credentials->$key->client_id = $clientId;
        $credentials->$key->client_secret = $clientSecret;
        $general->socialite_credentials = $credentials;
        $general->save();
    }

    public function updateAdSettings(Request $request): void
    {
        $general = gs();
        $general->ad_config = [
            'per_minute' => $request->per_minute,
            'ad_views' => $request->ad_views,
        ];
        $general->per_impression_spent = $request->per_impression_spent;
        $general->per_click_spent = $request->per_click_spent;
        $general->per_click_earn = $request->per_click_earn;
        $general->per_impression_earn = $request->per_impression_earn;
        $general->ads_module = $request->ads_module;
        $general->ads_auto_approve = $request->ads_auto_approve;
        $general->ad_reach = $request->ad_reach;
        $general->ad_engagement = $request->ad_engagement;
        $general->save();
        Cache::forget('GeneralSetting');
    }

    public function getHolidays(int $perPage = 15)
    {
        return Holiday::paginate($perPage);
    }

    public function updateOffDays(?array $offDays): void
    {
        if (count($offDays ?? []) == 7) {
            throw new \RuntimeException('You couldn\'t set all days as holiday');
        }
        $general = gs();
        $general->off_days = $offDays;
        $general->save();
    }

    public function addHoliday(string $title, string $date): Holiday
    {
        $holiday = new Holiday();
        $holiday->day_off = $date;
        $holiday->title = $title;
        $holiday->save();

        return $holiday;
    }

    public function deleteHoliday(int $id): void
    {
        Holiday::findOrFail($id)->delete();
    }

    public function updateChargeSettings(float $videoCharge, float $playlistCharge, float $planCharge): void
    {
        $general = gs();
        $general->video_sell_charge = $videoCharge;
        $general->playlist_sell_charge = $playlistCharge;
        $general->plan_sell_charge = $planCharge;
        $general->save();
        RequiredConfig::configured('charge_setting');
    }

    public function checkFfmpeg(): string
    {
        if (!gs('ffmpeg_status')) {
            return 'ffmpeg_disable';
        }
        $ffmpegVersion = shell_exec('ffmpeg -version 2>&1');
        if (strpos($ffmpegVersion, 'ffmpeg version') !== false) {
            return 'success';
        }
        return 'error';
    }

    public function getKeywordBlacklist()
    {
        return KeywordBlacklist::latest()->get();
    }

    public function addKeyword(string $word): KeywordBlacklist
    {
        return KeywordBlacklist::create(['word' => $word]);
    }

    public function deleteKeyword(int $id): void
    {
        KeywordBlacklist::findOrFail($id)->delete();
    }

    public function getSiteSettings(): SiteSetting
    {
        return SiteSetting::firstOrCreate([], [
            'site_name' => 'New YouTube',
            'max_upload_size' => 104857600,
            'allowed_video_types' => 'mp4,mov,avi,wmv',
            'default_video_quality' => '720p',
            'ads_enabled' => true,
            'monetization_enabled' => true,
        ]);
    }

    public function updateSiteSettings(Request $request, SiteSetting $settings): void
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'max_upload_size' => 'required|integer',
            'allowed_video_types' => 'required|string',
            'default_video_quality' => 'required|string',
            'ads_enabled' => 'boolean',
            'monetization_enabled' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = getFilePath('logoIcon');
            $filename = fileUploader($request->logo, $path, old: $settings->logo_path, filename: 'logo.png');
            $validated['logo_path'] = 'logoIcon/' . $filename;
        }

        $validated['ads_enabled'] = $request->has('ads_enabled');
        $validated['monetization_enabled'] = $request->has('monetization_enabled');

        $settings->update($validated);
    }

    public function getSystemInfo(): array
    {
        return [
            'laravelVersion' => app()->version(),
            'timeZone' => config('app.timezone'),
        ];
    }

    public function clearCache(): void
    {
        Artisan::call('optimize:clear');
    }

    public function getCronJobs()
    {
        return [
            'crons' => CronJob::with('schedule', 'logs')->get(),
            'schedules' => CronSchedule::active()->orderBy('interval')->get(),
        ];
    }

    public function createCronJob(array $data): CronJob
    {
        $cronJob = new CronJob();
        $cronJob->name = $data['name'];
        $cronJob->alias = titleToKey($data['name']);
        $cronJob->next_run = \Carbon\Carbon::parse($data['next_run'])->toDateTimeString();
        $cronJob->cron_schedule_id = $data['cron_schedule_id'];
        $cronJob->url = $data['url'];
        $cronJob->is_default = Status::NO;
        $cronJob->save();

        return $cronJob;
    }

    public function updateCronJob(array $data): CronJob
    {
        $cronJob = CronJob::findOrFail($data['id']);
        $cronJob->name = $data['name'] ?? $cronJob->name;
        $cronJob->next_run = isset($data['next_run']) ? \Carbon\Carbon::parse($data['next_run'])->toDateTimeString() : $cronJob->next_run;
        $cronJob->cron_schedule_id = $data['cron_schedule_id'] ?? $cronJob->cron_schedule_id;
        $cronJob->url = $data['url'] ?? $cronJob->url;
        $cronJob->save();

        return $cronJob;
    }

    public function toggleCronJobStatus(int $id): void
    {
        $cronJob = CronJob::findOrFail($id);
        $cronJob->is_active = !$cronJob->is_active;
        $cronJob->save();
    }

    public function deleteCronJob(int $id): void
    {
        CronJob::findOrFail($id)->delete();
    }

    public function getCronJobLogs(int $cronJobId, int $perPage = 50)
    {
        return CronJobLog::where('cron_job_id', $cronJobId)->latest()->paginate($perPage);
    }

    public function getExtensions()
    {
        return Extension::orderBy('name')->get();
    }

    public function updateExtension(int $id, array $values): Extension
    {
        $extension = Extension::findOrFail($id);
        $shortcode = json_decode(json_encode($extension->shortcode), true);

        foreach ($shortcode as $key => $value) {
            if (isset($values[$key])) {
                $shortcode[$key]['value'] = $values[$key];
            }
        }

        $extension->shortcode = $shortcode;
        $extension->save();

        return $extension;
    }

    public function toggleExtensionStatus(int $id): mixed
    {
        return Extension::changeStatus($id);
    }

    public function getLanguages()
    {
        return Language::orderBy('is_default', 'desc')->get();
    }

    public function createLanguage(array $data, $imageFile): Language
    {
        $data['image'] = fileUploader($imageFile, getFilePath('language'), getFileSize('language'));

        $jsonContent = file_get_contents(resource_path('lang/') . 'en.json');
        $jsonFile = strtolower($data['code']) . '.json';
        File::put(resource_path('lang/') . $jsonFile, $jsonContent);

        if (isset($data['is_default']) && $data['is_default']) {
            Language::where('is_default', Status::YES)->update(['is_default' => Status::NO]);
        }

        return Language::create($data);
    }

    public function updateLanguage(Language $language, array $data, $imageFile = null): Language
    {
        if ($imageFile) {
            $data['image'] = fileUploader($imageFile, getFilePath('language'), getFileSize('language'));
        }

        if (isset($data['is_default']) && $data['is_default']) {
            Language::where('id', '!=', $language->id)->where('is_default', Status::YES)->update(['is_default' => Status::NO]);
        }

        $language->update($data);
        return $language;
    }

    public function deleteLanguage(Language $language): void
    {
        $language->delete();
    }

    public function getLanguageTranslations(Language $language)
    {
        $jsonFile = resource_path('lang/') . strtolower($language->code) . '.json';
        if (!File::exists($jsonFile)) {
            return collect();
        }

        return collect(json_decode(file_get_contents($jsonFile)));
    }

    public function updateLanguageTranslations(Language $language, array $translations): void
    {
        $jsonFile = resource_path('lang/') . strtolower($language->code) . '.json';
        file_put_contents($jsonFile, json_encode($translations, JSON_UNESCAPED_UNICODE));
    }

    public function importLanguageTranslations(Language $language): void
    {
        $sourceFile = resource_path('lang/') . 'en.json';
        $destFile = resource_path('lang/') . strtolower($language->code) . '.json';

        if (File::exists($destFile)) {
            $sourceData = json_decode(file_get_contents($sourceFile), true);
            $destData = json_decode(file_get_contents($destFile), true);

            foreach ($sourceData as $key => $value) {
                if (!isset($destData[$key])) {
                    $destData[$key] = $value;
                }
            }

            file_put_contents($destFile, json_encode($destData, JSON_UNESCAPED_UNICODE));
        }
    }

    public function getUpdateLogs(int $perPage = 20)
    {
        return UpdateLog::latest()->paginate($perPage);
    }

    public function getSystemSettingsJson()
    {
        return json_decode(file_get_contents(resource_path('views/admin/setting/settings.json')));
    }
}
