<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Advertisement;
use App\Models\AdvertisementAnalytics;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\GatewayCurrency;
use App\Models\Storage;
use App\Rules\FileTypeValidate;
use App\Traits\GetDateMonths;
use App\Traits\StorageDriver;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AdvertiserController extends Controller
{
    use GetDateMonths, StorageDriver;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function home()
    {
        $pageTitle = 'Advertiser Dashboard';
        $user      = auth()->user();
        
        $totalAds  = Advertisement::where('user_id', $user->id)->count();

        $analyticsQuery = AdvertisementAnalytics::whereHas('advertisement', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $totalClicks      = (clone $analyticsQuery)->where('click', Status::YES)->count();
        $totalImpressions = (clone $analyticsQuery)->where('impression', Status::YES)->count();
        $totalCampaign    = Campaign::where('user_id', $user->id)->count(); 

        $advertisements = Advertisement::with('campaign')->where('user_id', auth()->id())->latest()->take(10)->get();
        
        return view('frontend.client.advertiser.dashboard', compact('user', 'pageTitle', 'totalImpressions', 'totalClicks', 'totalAds', 'totalCampaign', 'advertisements'));
    }

    public function adsChart(Request $request)
    {
        $user       = auth()->user();
        $startDate  = Carbon::parse($request->start_date);
        $endDate    = Carbon::parse($request->end_date);
        $diffInDays = $startDate->diffInDays($endDate);

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format  = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $clicks = AdvertisementAnalytics::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->whereHas('advertisement', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->selectRaw('SUM(click) AS click')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')
            ->groupBy('created_on')
            ->get();

        $impressions = AdvertisementAnalytics::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->whereHas('advertisement', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->selectRaw('SUM(impression) AS impression')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on'        => showDateTime($date, 'd-M-y'),
                'total_clicks'      => $clicks->where('created_on', $date)->first()?->click ?? 0,
                'total_impressions' => $impressions->where('created_on', $date)->first()?->impression ?? 0,
            ];
        }

        $data = collect($data);

        $report['created_on'] = $data->pluck('created_on');
        $report['data']       = [
            [
                'name' => 'Clicks',
                'data' => $data->pluck('total_clicks'),
            ],
            [
                'name' => 'Impressions',
                'data' => $data->pluck('total_impressions'),
            ],
        ];

        return response()->json($report);
    }

    public function dataSubmit(Request $request)
    {
        $form           = Form::where('act', 'advertiser')->firstOrFail();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        
        $user = auth()->user();
        foreach (@$user->advertiser_data ?? [] as $advertiserData) {
            if ($advertiserData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $advertiserData->value);
            }
        }
        $advertiserData          = $formProcessor->processFormData($request, $formData);

        $user->advertiser_data   = $advertiserData;
        $user->advertiser_status = Status::ADVERTISER_PENDING;
        $user->save();

        $notify[] = ['success', 'Data submitted successfully'];
        return back()->withNotify($notify);
    }

    public function adList()
    {
        $pageTitle      = 'Standard Advertisements';
        $advertisements = Advertisement::searchable(['title'])
            ->where('user_id', auth()->id())
            ->where('ad_module', Status::NO)
            ->with(['categories', 'campaign'])->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $totalClick = Advertisement::where('ad_module', Status::NO)->where('user_id', auth()->id())->sum('click');
        $totalImpression = Advertisement::where('ad_module', Status::NO)->where('user_id', auth()->id())->sum('impression');
        $availableClick = Advertisement::where('ad_module', Status::NO)->where('user_id', auth()->id())->sum('available_click');
        $availableImpression = Advertisement::where('ad_module', Status::NO)->where('user_id', auth()->id())->sum('available_impression');

        return view('frontend.client.advertiser.ad.list', compact('pageTitle', 'advertisements', 'totalClick', 'totalImpression', 'availableClick', 'availableImpression'));
    }

    public function advanceAdList()
    {
        $pageTitle      = 'Advanced Campaigns';
        $advertisements = Advertisement::where('ad_module', Status::YES)
            ->searchable(['title'])
            ->where('user_id', auth()->id())
            ->with(['campaign', 'countries', 'adReaches', 'advertisementAnalytics'])->orderBy('id', 'desc')
            ->paginate(getPaginate());
            
        $user = auth()->user();
        $totalAds = Advertisement::where('ad_module', Status::YES)->where('user_id', $user->id)->count();
        $totalCampaign = Campaign::where('user_id', $user->id)->count();
        $totalDailyBudget = Advertisement::where('user_id', $user->id)->where('ad_module', Status::YES)->sum('daily_costs');
        $totalCosts = Campaign::where('user_id', $user->id)->sum('total_amount');

        return view('frontend.client.advertiser.ad.advance_list', compact('pageTitle', 'advertisements', 'totalCampaign', 'totalAds', 'totalDailyBudget', 'totalCosts'));
    }

    public function createAd()
    {
        $pageTitle  = 'Launch New Campaign';
        $categories = Category::all();
        $availableStorage = Storage::active()->where('available_space', '>', 0)->exists();
        return view('frontend.client.advertiser.ad.create', compact('pageTitle', 'categories', 'availableStorage'));
    }

    public function uploadAdVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video' => ['required', new FileTypeValidate(['mp4', 'mov', 'wmv', 'flv', 'avi', 'mkv'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $advertisement          = new Advertisement();
        $advertisement->user_id = auth()->id();

        if ($request->hasFile('video')) {
            try {
                $fileName = now()->format('Y/F') . '/' . uniqid() . time() . '.' . $request->video->getClientOriginalExtension();
                $advertisement->ad_file = fileUploader($request->video, getFilePath('adVideo') . '/' . now()->format('Y/F'), filename: $fileName);
            } catch (\Exception $exp) {
                return response()->json([
                    'remark'  => 'error',
                    'status'  => 'error',
                    'message' => ['error' => 'Video upload failed: ' . $exp->getMessage()],
                ]);
            }
        }

        $advertisement->step = Status::FIRST_STEP;
        $advertisement->save();
        
        return response()->json([
            'remark'  => 'success',
            'status'  => 'success',
            'message' => ['success' => 'Creative uploaded successfully'],
            'data'    => [
                'advertisement' => $advertisement,
            ],
        ]);
    }

    public function processedCheckout(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string',
            'category_id'   => 'required|array|min:1',
            'category_id.*' => 'integer',
            'logo'          => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'url'           => 'nullable|url',
            'ad_type'       => 'required|numeric',
            'impression'    => 'nullable|numeric',
            'click'         => 'nullable|numeric',
        ]);

        $advertisement = Advertisement::where('user_id', auth()->id())->findOrFail($id);
        
        $impressionCost = 0;
        $clickCost      = 0;

        if ($request->impression) {
            $impressionCost = $request->impression * gs('per_impression_spent');
        }

        if ($request->click) {
            $clickCost = $request->click * gs('per_click_spent');
        }

        $totalAmount = $impressionCost + $clickCost;

        $advertisement->title = $request->title;
        if ($request->hasFile('logo')) {
            $advertisement->logo = fileUploader($request->logo, getFilePath('adLogo'));
        }
        
        $advertisement->url                  = $request->url;
        $advertisement->ad_type              = $request->ad_type;
        $advertisement->impression           = $request->impression ?? 0;
        $advertisement->available_impression = $request->impression ?? 0;
        $advertisement->click                = $request->click ?? 0;
        $advertisement->available_click      = $request->click ?? 0;
        $advertisement->ad_module            = gs('ads_module');

        $advertisement->step         = Status::SECOND_STEP;
        $advertisement->total_amount = $totalAmount;
        $advertisement->save();

        $advertisement->categories()->sync($request->category_id);
        
        $notify[] = ['success', 'Campaign details saved. Proceeding to payment.'];
        return to_route('user.deposit.index', $advertisement->id)->withNotify($notify);
    }

    public function paymentHistory()
    {
        $pageTitle = 'Billing Ledger';
        $payments = Deposit::searchable(['trx'])->where('user_id', auth()->id())->where(function($query){
           $query->where('advertisement_id', '!=', 0)->orWhere('campaign_id','!=', 0);
        })->orderby('id', 'desc')->paginate(getPaginate());

        return view('frontend.client.advertiser.payment_history', compact('pageTitle', 'payments'));
    }

    public function status($id)
    {
        $advertisement = Advertisement::where('user_id', auth()->id())->findOrFail($id);
        $advertisement->status = $advertisement->status == Status::RUNNING ? Status::PAUSE : Status::RUNNING;
        $advertisement->save();

        $notify[] = ['success', 'Campaign status updated'];
        return back()->withNotify($notify);
    }
}
