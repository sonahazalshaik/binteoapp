<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Advertisement;
use App\Models\AdvertisementAnalytics;
use App\Models\Campaign;
use App\Models\Form;
use App\Models\User;
use App\Services\Admin\MarketingService;
use App\Traits\GetDateMonths;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageAdvertiserController extends Controller {

    protected $service;

    public function __construct(MarketingService $service)
    {
        $this->service = $service;
    }

    use GetDateMonths;

    public function pending() {
        $pageTitle = "Pending Advertisers";

        $advertisers = $this->service->getAdvertiserList('pendingAdvertisers');
        return view('admin.advertiser.index', compact('pageTitle', 'advertisers'));
    }
    public function approved() {
        $pageTitle   = "Pending Advertisers";
        $advertisers = $this->service->getAdvertiserList('approvedAdvertisers');

        return view('admin.advertiser.index', compact('pageTitle', 'advertisers'));
    }

    public function rejected() {
        $pageTitle   = "Pending Advertisers";
        $advertisers = $this->service->getAdvertiserList('rejectedAdvertisers');
        return view('admin.advertiser.index', compact('pageTitle', 'advertisers'));
    }

    protected function advertiserData($scope = null) {
        if ($scope) {
            $advertisers = User::$scope();
        } else {
            $advertisers = User::query();
        }

        return $advertisers
            ->searchable(['username', 'email'])
            ->latest()
            ->paginate(getPaginate());
    }

    public function detail($id) {
        $data = $this->service->getAdvertiserDetail($id);
        $user = $data['user'];
        $pageTitle = 'Advertiser Details for ' . $user->fullname;
        $widget = $data['widget'];
        $totalCampaign = $data['totalCampaign'];
        $totalBudget = $data['totalBudget'];
        $availableBudget = $data['availableBudget'];
        $dailyAds = $data['dailyAds'];
        $customAds = $data['customAds'];

        return view('admin.advertiser.detail', compact('pageTitle', 'user', 'widget','totalCampaign', 'totalBudget', 'availableBudget', 'dailyAds', 'customAds'));
    }

    public function dataApprove($id) {
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);
        $this->service->approveAdvertiser($id);

        notify($user, 'ADVERTISER_APPROVE', []);

        $notify[] = ['success', 'Advertiser document approved successfully'];
        return back()->withNotify($notify);
    }

    public function dataReject(Request $request, $id) {
        $request->validate([
            'reason' => 'required',
        ]);
        $user = User::where('advertiser_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);
        $this->service->rejectAdvertiser($id, $request->reason);

        notify($user, 'ADVERTISER_REJECT', [
            'reason' => $request->reason,
        ]);

        $notify[] = ['success', 'Advertiser document  rejected successfully'];
        return back()->withNotify($notify);
    }

    public function report(Request $request, $id) {
        $report = $this->service->getAdvertiserReport($id, $request->start_date, $request->end_date);

        return response()->json($report);
    }

    public function setting() {
        $pageTitle = 'Advertiser Setting';
        $form      = Form::where('act', 'advertiser')->first();
        return view('admin.advertiser.setting', compact('pageTitle', 'form'));
    }

    public function settingUpdate(Request $request) {
        $this->service->updateAdvertiserSetting($request);

        $notify[] = ['success', 'Advertiser data updated successfully'];
        return back()->withNotify($notify);
    }

}
