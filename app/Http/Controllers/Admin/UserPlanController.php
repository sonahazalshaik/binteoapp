<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting; // Assuming this model exists
use App\Models\UserPlan;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserPlanController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the user plans.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageTitle = "Market Plans Management";
        $userplans = $this->service->getUserPlanList();
        $general = GeneralSetting::first();
        return view('admin.userplan.index', compact('pageTitle', 'userplans', 'general'));
    }

    /**
     * Show the form for creating a new user plan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pageTitle = "Create New Market Plan";
        return view('admin.userplan.create', compact('pageTitle'));
    }

    /**
     * Store a newly created user plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'plan_price' => 'required|numeric|min:0',
            'plan_duration' => 'required|integer|min:0',
            'plan_content' => 'required|string',
        ]);
        
        $request->merge([
            'is_featured_plan' => $request->is_featured_plan ? 1 : 0,
            'contact_access' => $request->contact_access ? 1 : 0
        ]);

        $this->service->createUserPlan($request->all());

        $notify[] = ['success', 'Market Plan has been created successfully.'];
        return redirect()->route('admin.userplans.index')->withNotify($notify);
    }

    /**
     * Show the form for editing the specified user plan.
     *
     * @param  \App\Models\UserPlan  $userplan
     * @return \Illuminate\View\View
     */
    public function edit(UserPlan $userplan)
    {
        $pageTitle = "Edit Market Plan: " . $userplan->plan_name;
        return view('admin.userplan.edit', compact('pageTitle', 'userplan'));
    }

    /**
     * Update the specified user plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserPlan  $userplan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UserPlan $userplan)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'plan_price' => 'required|numeric|min:0',
            'plan_duration' => 'required|integer|min:0',
            'plan_content' => 'required|string',
        ]);

        $request->merge([
            'is_featured_plan' => $request->is_featured_plan ? 1 : 0,
            'contact_access' => $request->contact_access ? 1 : 0
        ]);

        $this->service->updateUserPlan($userplan, $request->all());

        $notify[] = ['success', 'Market Plan has been updated successfully.'];
        return redirect()->route('admin.userplans.index')->withNotify($notify);
    }

    /**
     * Remove the specified user plan from storage.
     *
     * @param  \App\Models\UserPlan  $userplan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UserPlan $userplan)
    {
        $this->service->deleteUserPlan($userplan);

        $notify[] = ['success', 'Market Plan has been deleted successfully.'];
        return redirect()->route('admin.userplans.index')->withNotify($notify);
    }
}
