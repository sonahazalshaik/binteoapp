<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\Admin\MarketingService;
use Google\Service\CloudSearch\UserId;
use Illuminate\Http\Request;

class CampaignController extends Controller
{

    protected $service;

    public function __construct(MarketingService $service)
    {
        $this->service = $service;
        if (!app()->runningInConsole() && !gs('ads_module')) {
            abort(404);
        }
    }


    public function index($id=null)
    {
        $pageTitle = 'Campaigns';
        $campaigns  = $this->service->getCampaigns(userId: $id);
        return view('admin.campaign.index', compact('pageTitle', 'campaigns'));
    }

    public function create()
    {
        $pageTitle = 'Initiate New Campaign';
        $users = User::active()->get();
        return view('admin.campaign.create', compact('pageTitle', 'users'));
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        $pageTitle = 'Modify Campaign';
        $users = User::active()->get();
        return view('admin.campaign.edit', compact('pageTitle', 'campaign', 'users'));
    }

    public function active()
    {
        $pageTitle = 'Active Campaigns';
        $campaigns  = $this->service->getCampaigns('active');
        return view('admin.campaign.index', compact('pageTitle', 'campaigns'));
    }

    public function inactive()
    {
        $pageTitle = 'Inactive Campaigns';
        $campaigns  = $this->service->getCampaigns('inactive');
        return view('admin.campaign.index', compact('pageTitle', 'campaigns'));
    }


    protected function campaignData($scope = null, $userId = null)
    {
        if ($scope) {
            $campaigns = Campaign::$scope();
        } else {
            $campaigns = Campaign::query();
        }
         if ($userId) {
            $campaigns = $campaigns->where('user_id', $userId);
        }

        return $campaigns->searchable(['title'])->orderBy('id', 'desc')->paginate(getPaginate());
    }


    public function detail($id)
    {
        $campaign = $this->service->getCampaignDetail($id);
        $pageTitle = $campaign->title . ' Details';
        return view('admin.campaign.detail', compact('pageTitle', 'campaign'));
    }


    public function status($id)
    {
        return $this->service->toggleCampaignStatus($id);
    }
}
