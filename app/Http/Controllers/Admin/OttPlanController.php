<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OttPlan;
use App\Services\Admin\PlanService;
use Illuminate\Http\Request;

class OttPlanController extends Controller
{
    protected $service;

    public function __construct(PlanService $service) {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'OTT Plans Management';
        $plans = $this->service->getAllOttPlans();
        return view('admin.ott_plans.index', compact('plans', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Create New OTT Plan';
        return view('admin.ott_plans.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:0',
        ]);

        $this->service->createOttPlan([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'duration'    => $request->duration,
            'ott_access'  => $request->ott_access ? 1 : 0,
        ]);

        $notify[] = ['success', 'OTT Plan created successfully.'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $plan = OttPlan::findOrFail($id);
        $pageTitle = 'Edit OTT Plan: ' . $plan->name;
        return view('admin.ott_plans.edit', compact('plan', 'pageTitle'));
    }

    public function update(Request $request, $id)
    {
        $plan = OttPlan::findOrFail($id);
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:0',
        ]);

        $this->service->updateOttPlan($id, [
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'duration'    => $request->duration,
            'ott_access'  => $request->ott_access ? 1 : 0,
        ]);

        $notify[] = ['success', 'OTT Plan updated successfully.'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $this->service->toggleOttPlanStatus($id);

        $notify[] = ['success', 'OTT Plan status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $this->service->deleteOttPlan($id);
        $notify[] = ['success', 'OTT Plan deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function subscriptions()
    {
        $pageTitle = 'OTT Purchased Users';
        $subscriptions = $this->service->getOttSubscriptions();
        return view('admin.ott_plans.subscriptions', compact('subscriptions', 'pageTitle'));
    }
}
