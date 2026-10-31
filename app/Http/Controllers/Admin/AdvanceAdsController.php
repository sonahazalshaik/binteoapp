<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\Admin\MarketingService;
use App\Traits\AdminAdsManage;
use Illuminate\Http\Request;

class AdvanceAdsController extends Controller
{
    protected $service;

    public function __construct(MarketingService $service)
    {
        $this->service = $service;
        $this->view = 'advance_ads';
        if (!app()->runningInConsole() && !gs('ads_module')) {
            abort(404);
        }
    }

    use AdminAdsManage;


    public function detail($id)
    {
        $advertisement = $this->service->getAdvertisementDetail($id);
        $pageTitle = 'Detail of ' . $advertisement->title;
        $campaign = $advertisement->campaign;
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.' . $this->view . '.detail', compact('advertisement','pageTitle','campaign','countries'));
    }

    public function status($id){
        $this->service->toggleAdvertisementStatus($id);
        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function approved($id){
        $this->service->approveAdvertisement($id);
        $notify[] = ['success', 'Advertisement Approved successfully'];
        return back()->withNotify($notify);
    }

  


    public function reject(Request $request, $id) {
        $request->validate([
        
            'message' => 'required|string|max:255',
        ]);
        $advertisement = Advertisement::where('status', Status::ADVERTISEMENT_PENDING)->findOrFail($id);
        $this->service->rejectAdvertisement($id, $request->message);

        notify($advertisement->user, 'ADVERTISEMENT_REJECT', [
            'title' => $advertisement->title,
            'rejection_message' => $request->message,
        ]);

        $notify[] = ['success', 'Advertisement rejected successfully'];
        return back()->withNotify($notify);

    }

    public function destroy($id)
    {
        $this->service->deleteAdvertisement($id);

        $notify[] = ['success', 'Advertisement deleted successfully'];
        return back()->withNotify($notify);
    }

}
