<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Helpers\ImageHelper;
use App\Lib\FormProcessor;
use App\Models\Advertisement;
use App\Models\AdvertisementAnalytics;
use App\Models\BannerAd;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Form;
use App\Models\User;
use App\Traits\AdminAdsManage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MarketingService
{
    use AdminAdsManage;

    protected array $validSlots = ['slot1', 'slot2', 'slot3', 'slot4'];

    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getBannersBySlot(string $slot, int $perPage = 15)
    {
        if (!in_array($slot, $this->validSlots)) {
            abort(404);
        }

        return BannerAd::where('slot', $slot)->latest()->paginate($perPage);
    }

    public function createBanner(string $slot, array $data, array $files): int
    {
        if (!in_array($slot, $this->validSlots)) {
            abort(404);
        }

        $deployedCount = 0;

        $status = !empty($data['status']) ? 1 : 0;

        foreach ($files['banner_imgs'] as $file) {
            $banner = new BannerAd();
            $banner->slot = $slot;
            $banner->link = $data['link'] ?? null;
            $banner->start_date = $data['start_date'];
            $banner->end_date = $data['end_date'];
            $banner->status = $status;
            $banner->slug = 'ad-' . uniqid() . '-' . Str::random(5);

            $filename = 'banners/' . $slot . '/' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('r2')->put($filename, file_get_contents($file));
            $banner->image = $filename;
            $banner->save();
            $deployedCount++;
        }

        $this->clearBannerCaches();

        return $deployedCount;
    }

    public function updateBanner(string $slot, int $id, array $data, ?array $files = []): BannerAd
    {
        if (!in_array($slot, $this->validSlots)) {
            abort(404);
        }

        $banner = BannerAd::where('slot', $slot)->findOrFail($id);
        $banner->link = $data['link'] ?? $banner->link;
        $banner->start_date = $data['start_date'] ?? $banner->start_date;
        $banner->end_date = $data['end_date'] ?? $banner->end_date;
        $banner->status = !empty($data['status']) ? 1 : 0;

        if (!empty($files['banner_imgs'])) {
            $fileList = $files['banner_imgs'];
            $firstFile = array_shift($fileList);

            if ($banner->image) {
                Storage::disk('r2')->delete($banner->image);
            }
            $filename = 'banners/' . $slot . '/' . uniqid() . '_' . time() . '.' . $firstFile->getClientOriginalExtension();
            Storage::disk('r2')->put($filename, file_get_contents($firstFile));
            $banner->image = $filename;

            foreach ($fileList as $file) {
                $newBanner = new BannerAd();
                $newBanner->slot = $slot;
                $newBanner->link = $data['link'] ?? null;
                $newBanner->start_date = $data['start_date'];
                $newBanner->end_date = $data['end_date'];
                $newBanner->status = !empty($data['status']) ? 1 : 0;
                $newBanner->slug = 'ad-' . uniqid() . '-' . Str::random(5);

                $fn = 'banners/' . $slot . '/' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('r2')->put($fn, file_get_contents($file));
                $newBanner->image = $fn;
                $newBanner->save();
            }
        }

        $banner->save();
        $this->clearBannerCaches();
        return $banner;
    }

    public function toggleBannerStatus(int $id): void
    {
        $banner = BannerAd::findOrFail($id);
        $banner->status = !$banner->status;
        $banner->save();
        $this->clearBannerCaches();
    }

    public function deleteBanner(int $id): void
    {
        $banner = BannerAd::findOrFail($id);
        if ($banner->image) {
            Storage::disk('r2')->delete($banner->image);
        }
        $banner->delete();
        $this->clearBannerCaches();
    }

    private function clearBannerCaches(): void
    {
        foreach (['home_banners_slot1', 'home_banners_slot2', 'home_banners_slot3', 'home_banners_slot4', 'video_page_banners'] as $key) {
            try {
                Cache::forget($key);
            } catch (\Throwable) {
            }
        }
    }

    public function getAdvertisements(?string $type = null, ?int $userId = null, int $perPage = 15)
    {
        return $this->advertisementData($type, $userId, $perPage);
    }

    public function createAdvertisement(Request $request): Advertisement
    {
        $advertisement = new Advertisement();
        $advertisement->title = $request->title;

        if ($request->hasFile('ad_video')) {
            $advertisement->ad_file = ImageHelper::uploadToR2($request->ad_video, 'ads/videos');
        }
        if ($request->hasFile('logo')) {
            $advertisement->logo = ImageHelper::uploadToR2($request->logo, 'ads/logos');
        }

        $advertisement->url = $request->url;
        $advertisement->button_label = $request->button_label;
        $advertisement->ad_type = $request->ad_type;
        $advertisement->impression = $request->impression ?? 0;
        $advertisement->click = $request->click ?? 0;
        $advertisement->total_amount = $request->total_amount ?? 0;
        $advertisement->status = Status::RUNNING;
        $advertisement->save();

        if ($request->category_id) {
            $advertisement->categories()->sync($request->category_id);
        }

        return $advertisement;
    }

    public function updateAdvertisement(Request $request, int $id): Advertisement
    {
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->title = $request->title;

        if ($request->hasFile('ad_video')) {
            if ($advertisement->ad_file) {
                ImageHelper::deleteImage($advertisement->ad_file);
            }
            $advertisement->ad_file = ImageHelper::uploadToR2($request->ad_video, 'ads/videos');
        }

        if ($request->hasFile('logo')) {
            if ($advertisement->logo) {
                ImageHelper::deleteImage($advertisement->logo);
            }
            $advertisement->logo = ImageHelper::uploadToR2($request->logo, 'ads/logos');
        }

        $advertisement->url = $request->url;
        $advertisement->button_label = $request->button_label;
        $advertisement->ad_type = $request->ad_type;
        $advertisement->impression = $request->impression ?? 0;
        $advertisement->click = $request->click ?? 0;
        $advertisement->total_amount = $request->total_amount;
        $advertisement->status = $request->status ? Status::RUNNING : Status::PAUSE;
        $advertisement->save();

        if ($request->category_id) {
            $advertisement->categories()->sync($request->category_id);
        }

        return $advertisement;
    }

    public function toggleAdvertisementStatus(int $id): void
    {
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->status = $advertisement->status == Status::RUNNING ? Status::PAUSE : Status::RUNNING;
        $advertisement->save();
    }

    public function deleteAdvertisement(int $id): void
    {
        $advertisement = Advertisement::findOrFail($id);
        if ($advertisement->ad_file) {
            ImageHelper::deleteImage($advertisement->ad_file);
        }
        if ($advertisement->logo) {
            ImageHelper::deleteImage($advertisement->logo);
        }
        $advertisement->delete();
    }

    public function approveAdvertisement(int $id): void
    {
        $advertisement = Advertisement::where('status', Status::ADVERTISEMENT_PENDING)->findOrFail($id);
        $advertisement->status = Status::RUNNING;
        $advertisement->save();
    }

    public function rejectAdvertisement(int $id, string $message): void
    {
        $advertisement = Advertisement::where('status', Status::ADVERTISEMENT_PENDING)->findOrFail($id);
        $advertisement->reject_reason = $message;
        $advertisement->status = Status::ADVERTISEMENT_REJECTED;
        $advertisement->save();
    }

    public function getAdvertisementDetail(int $id): Advertisement
    {
        return Advertisement::with('campaign', 'countries', 'schedules')->findOrFail($id);
    }

    public function getAdvertiserList(?string $scope = null, int $perPage = 15)
    {
        $query = $scope ? User::$scope() : User::query();
        return $query->searchable(['username', 'email'])->latest()->paginate($perPage);
    }

    public function getAdvertiserDetail(int $id): array
    {
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);

        $widget = [
            'total_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->count(),
            'running_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->running()->count(),
            'pause_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->stop()->count(),
            'click_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->click()->count(),
            'impressions_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->impression()->count(),
            'both_type_ads' => $user->advertisements()->where('ad_module', gs('ads_module'))->both()->count(),
            'total_spent_amount' => $user->advertisements()->where('ad_module', gs('ads_module'))->sum('total_amount'),
            'last_seven_days_spent' => $user->advertisements()->where('ad_module', gs('ads_module'))->where('created_at', '>=', now()->subDays(7))->sum('total_amount'),
        ];

        $totalCampaign = Campaign::where('user_id', $user->id)->count();
        $totalBudget = gs('ads_module') == Status::YES ? $user->advertisements()->where('ad_module', Status::YES)->sum('total_amount') : 0;
        $availableBudget = gs('ads_module') == Status::YES ? Campaign::where('user_id', $user->id)->sum('total_amount') : 0;
        $dailyAds = gs('ads_module') == Status::YES ? $user->advertisements()->where('ad_module', Status::YES)->where('schedule_type', Status::ALL_DAYS)->count() : 0;
        $customAds = gs('ads_module') == Status::YES ? $user->advertisements()->where('ad_module', Status::YES)->where('schedule_type', Status::CUSTOM_DAYS)->count() : 0;

        return compact('user', 'widget', 'totalCampaign', 'totalBudget', 'availableBudget', 'dailyAds', 'customAds');
    }

    public function approveAdvertiser(int $id): void
    {
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);
        $user->advertiser_status = Status::ADVERTISER_APPROVED;
        $user->save();
    }

    public function rejectAdvertiser(int $id, string $reason): void
    {
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);
        $user->advertiser_status = Status::ADVERTISER_REJECTED;
        $user->advertiser_rejection_reason = $reason;
        $user->save();
    }

    public function getAdvertiserReport(int $userId, string $startDate, string $endDate): array
    {
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($userId);

        $diffInDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        $dates = $groupBy === 'days'
            ? $this->getAllDates($startDate, $endDate)
            : $this->getAllMonths($startDate, $endDate);

        $clicks = AdvertisementAnalytics::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('advertisement', fn($q) => $q->where('user_id', $user->id)->where('ad_module', gs('ads_module')))
            ->selectRaw('SUM(click) AS click')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $impressions = AdvertisementAnalytics::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('advertisement', fn($q) => $q->where('user_id', $user->id)->where('ad_module', gs('ads_module')))
            ->selectRaw('SUM(impression) AS impression')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $data = collect($dates)->map(fn($date) => [
            'created_on' => showDateTime($date, 'd-M-y'),
            'total_clicks' => $clicks->where('created_on', $date)->first()?->click ?? 0,
            'total_impressions' => $impressions->where('created_on', $date)->first()?->impression ?? 0,
        ]);

        return [
            'created_on' => $data->pluck('created_on'),
            'data' => [
                ['name' => 'Clicks', 'data' => $data->pluck('total_clicks')],
                ['name' => 'Impressions', 'data' => $data->pluck('total_impressions')],
            ],
        ];
    }

    public function updateAdvertiserSetting(Request $request): void
    {
        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);
        $exist = Form::where('act', 'advertiser')->first();
        $formProcessor->generate('advertiser', $exist, 'act');
    }

    public function getCampaigns(?string $scope = null, ?int $userId = null, int $perPage = 15)
    {
        $query = $scope ? Campaign::$scope() : Campaign::query();

        if ($userId) {
            $query = $query->where('user_id', $userId);
        }

        return $query->searchable(['title'])->orderBy('id', 'desc')->paginate($perPage);
    }

    public function getCampaignDetail(int $id): Campaign
    {
        return Campaign::searchable(['title', 'advertisements:title'])
            ->with('advertisements', 'advertisements.schedules', 'advertisements.countries')
            ->findOrFail($id);
    }

    public function toggleCampaignStatus(int $id): mixed
    {
        return Campaign::changeStatus($id);
    }

    protected function advertisementData(?string $type = null, ?int $userId = null, int $perPage = 15)
    {
        $query = Advertisement::query();

        if ($type) {
            $query = $query->where('ad_type', $type);
        }

        if ($userId) {
            $query = $query->where('user_id', $userId);
        }

        return $query->with(['user', 'categories'])->latest()->paginate($perPage);
    }

    protected function getAllDates(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('d-F-Y');
            $current->addDay();
        }

        return $dates;
    }

    protected function getAllMonths(string $startDate, string $endDate): array
    {
        $months = [];
        $current = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->startOfMonth();

        while ($current->lte($end)) {
            $months[] = $current->format('F-Y');
            $current->addMonth();
        }

        return $months;
    }
}
