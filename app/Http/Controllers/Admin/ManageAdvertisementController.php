<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use App\Services\Admin\MarketingService;
use App\Traits\AdminAdsManage;
use App\Traits\StorageDriver;
use Illuminate\Http\Request;

class ManageAdvertisementController extends Controller
{
    protected $service;

    public function __construct(MarketingService $service)
    {
        $this->service = $service;
        $this->view = 'advertisements';
    }

    use StorageDriver , AdminAdsManage;
   
    public function impression($id = null)
    {
        $pageTitle      = "Ad Type Impression";
        $advertisements = $this->service->getAdvertisements('impression', $id);
        return view('admin.advertisements.index', compact('advertisements', 'pageTitle'));
    }

    public function click($id = null)
    {
        $pageTitle      = "Ad Type Click";
        $advertisements = $this->service->getAdvertisements('click', $id);
        return view('admin.advertisements.index', compact('advertisements', 'pageTitle'));
    }

    public function both($id = null)
    {
        $pageTitle      = "Ad Type Both";
        $advertisements = $this->service->getAdvertisements('both', $id);
        return view('admin.advertisements.index', compact('advertisements', 'pageTitle'));
    }

    

 

    public function create()
    {
        $pageTitle  = "Initialize Advertisement";
        $categories = Category::active()->get();
        return view('admin.advertisements.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string',
            'category_id'   => 'required|array|min:1',
            'category_id.*' => 'integer',
            'ad_video'      => ['required', new FileTypeValidate(['mp4', 'mov', 'wmv', 'flv', 'avi', 'mkv'])],
            'logo'          => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'url'           => 'nullable|url|required_if:ad_type,2,3',
            'ad_type'       => 'required|numeric',
            'impression'    => 'nullable|numeric|required_if:ad_type,1,3',
            'click'         => 'nullable|numeric|required_if:ad_type,2,3',
            'button_label'  => 'nullable|string|required_if:ad_type,2,3',
        ]);

        $advertisement = $this->service->createAdvertisement($request);

        $notify[] = ['success', 'Advertisement initialized successfully'];
        return redirect()->route('admin.advertisement.both')->withNotify($notify);
    }

    public function show($id)
    {
        $advertisement = Advertisement::with(['user', 'categories'])->findOrFail($id);
        $pageTitle     = "Ad Analysis: " . $advertisement->title;
        return view('admin.advertisements.view', compact('advertisement', 'pageTitle'));
    }

    public function edit($id)
    {
        $advertisement = Advertisement::with('user')->findOrFail($id);
        $pageTitle     = "Edit " . $advertisement->title . " Advertisement";

        $categories = Category::active()->get();
        return view('admin.advertisements.edit', compact('advertisement', 'pageTitle', 'categories'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title'         => 'required|string',
            'category_id'   => 'required|array|min:1',
            'category_id.*' => 'integer',
            'ad_video'      => ['nullable', new FileTypeValidate(['mp4', 'mov', 'wmv', 'flv', 'avi', 'mkv'])],
            'logo'          => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'url'           => 'nullable|url|required_if:ad_type,2,3',
            'ad_type'       => 'required|numeric',
            'impression'    => 'nullable|numeric|required_if:ad_type,1,3',
            'click'         => 'nullable|numeric|required_if:ad_type,2,3',
            'button_label'  => 'nullable|string|required_if:ad_type,2,3',
        ], [
            'title.required'         => 'Title is required',
            'category_id.required'   => 'Please select at least one category',
            'category_id.*.integer'  => 'Each category must be a valid integer',
            'ad_video.required'      => 'Please upload an ad video',
            'url.url'                => 'Invalid URL',
            'ad_type.required'       => 'Please select Ad type',
            'impression.required_if' => 'Please enter an impression value',
            'click.required_if'      => 'Please enter a click value',
        ]);

        $categories = Category::whereIn('id', $request->category_id)->get();

        if (count($categories) != count($request->category_id)) {
            $notify[] = ['error', 'Please select valid categories'];
            return back()->withNotify($notify);
        }

        $advertisement = $this->service->updateAdvertisement($request, $id);

        if (@$advertisement->storage && $request->hasFile('ad_video')) {
            $path = getFilePath('adVideo') . '/' . $advertisement->ad_file;
            $this->uploadServer( $advertisement->ad_file,$path,$advertisement, 'ads');
     
        }

        $notify[] = ['success', 'Advertisement updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $this->service->toggleAdvertisementStatus($id);
        $notify[] = ['success', 'Status has been changed'];
        return back()->withNotify($notify);
    }

   
    public function destroy($id)
    {
        $this->service->deleteAdvertisement($id);

        $notify[] = ['success', 'Advertisement deleted successfully'];
        return back()->withNotify($notify);
    }

}
