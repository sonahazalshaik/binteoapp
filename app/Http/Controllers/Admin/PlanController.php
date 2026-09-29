<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Admin\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $service;

    public function __construct(PlanService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        $pageTitle = 'Architect Creator Plan';
        return view('admin.plans.create', compact('pageTitle'));
    }

    public function edit(Plan $plan)
    {
        $pageTitle = 'Refine Creator Membership Tier: ' . $plan->name;
        return view('admin.plans.edit', compact('plan', 'pageTitle'));
    }

    public function index()
    {
        $pageTitle = 'Creator Plans';
        $plans = $this->service->getAllPlans();
        return view('admin.plans.index', compact('plans', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:0',
        ]);

        $this->service->createPlan([
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'duration'         => $request->duration,
            'video_access'     => $request->video_access ? 1 : 0,
            'contact_access'   => $request->contact_access ? 1 : 0,
            'is_featured_plan' => $request->is_featured_plan ? 1 : 0,
            'status'           => 1,
        ]);

        $notify[] = ['success', 'Creator Plan created successfully.'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:0',
        ]);

        $this->service->updatePlan($plan, [
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'duration'         => $request->duration,
            'video_access'     => $request->video_access ? 1 : 0,
            'contact_access'   => $request->contact_access ? 1 : 0,
            'is_featured_plan' => $request->is_featured_plan ? 1 : 0,
        ]);

        $notify[] = ['success', 'Creator Plan updated successfully.'];
        return back()->withNotify($notify);
    }

    public function toggleStatus(Plan $plan)
    {
        $this->service->togglePlanStatus($plan);

        $notify[] = ['success', 'Creator Plan status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function destroy(Plan $plan)
    {
        $this->service->deletePlan($plan);
        $notify[] = ['success', 'Creator Plan deleted successfully.'];
        return back()->withNotify($notify);
    }
}
