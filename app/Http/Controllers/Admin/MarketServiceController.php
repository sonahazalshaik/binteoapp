<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketPlace;
use App\Services\Admin\MarketTalentService;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class MarketServiceController extends Controller
{
    protected $service;

    public function __construct(MarketTalentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Market Services';
        $services = $this->service->listServices();
        return view('admin.marketplace.service.index', compact('pageTitle', 'services'));
    }

    public function create()
    {
        $pageTitle = 'Create Service';
        $talents = MarketPlace::orderBy('id', 'desc')->get();
        return view('admin.marketplace.service.create', compact('pageTitle', 'talents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'service_name' => 'required|string|max:255',
            'service_brief' => 'required|string',
            'images' => 'required|array',
            'images.*' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $count = $this->service->createService($request->all(), $request->file('images'));

        $notify[] = ['success', $count . ' service(s) created successfully'];
        return to_route('admin.marketplace.services.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Service';
        $service = $this->service->findService($id);
        $talents = MarketPlace::orderBy('id', 'desc')->get();
        return view('admin.marketplace.service.edit', compact('pageTitle', 'service', 'talents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'service_name' => 'required|string|max:255',
            'service_brief' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $this->service->updateService($id, $request->all(), $request->file('images'));

        $notify[] = ['success', 'Service updated successfully'];
        return to_route('admin.marketplace.services.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $this->service->deleteService($id);

        $notify[] = ['success', 'Service deleted successfully'];
        return back()->withNotify($notify);
    }
}
