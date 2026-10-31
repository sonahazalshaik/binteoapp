<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminChannelPlan;
use App\Models\ChannelPlan;
use App\Models\ChannelSubscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelPlanController extends Controller
{
    /**
     * Display a listing of admin channel plan tiers.
     */
    public function index()
    {
        $pageTitle = 'Channel Membership Plans';
        $plans = AdminChannelPlan::orderBy('sort_order', 'asc')->orderBy('duration_days', 'asc')->get();

        $stats = [
            'total_tiers' => $plans->count(),
            'active_tiers' => $plans->where('status', true)->count(),
            'active_subscribers' => ChannelSubscription::active()->count(),
            'total_revenue' => Transaction::where('remark', 'channel_subscription_commission')->sum('amount'),
        ];

        return view('admin.channel_plans.index', compact('plans', 'pageTitle', 'stats'));
    }

    /**
     * Show the form for creating a new channel plan tier.
     */
    public function create()
    {
        $pageTitle = 'Create Channel Plan Tier';
        return view('admin.channel_plans.create', compact('pageTitle'));
    }

    /**
     * Store a newly created channel plan tier in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:120',
            'duration_days' => 'nullable|integer|min:1',
            'default_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $months = (int) $request->duration_months;
        $days = $request->filled('duration_days') ? (int) $request->duration_days : ($months * 30);
        $durationType = $months === 12 ? '1_year' : ($months . '_months');

        // Check if duration_type already exists
        if (AdminChannelPlan::where('duration_type', $durationType)->exists()) {
            $durationType = Str::slug($request->name, '_') . '_' . $months . 'm';
        }

        AdminChannelPlan::create([
            'name' => $request->name,
            'duration_type' => $durationType,
            'duration_days' => $days,
            'duration_months' => $months,
            'default_price' => $request->default_price,
            'description' => $request->description,
            'status' => true,
            'sort_order' => $request->sort_order ?? (AdminChannelPlan::max('sort_order') + 1),
        ]);

        $notify[] = ['success', 'Channel Plan Tier created successfully. Creators can now offer this plan!'];
        return redirect()->route('admin.channel-plans.index')->withNotify($notify);
    }

    /**
     * Show the form for editing the specified channel plan tier.
     */
    public function edit($id)
    {
        $plan = AdminChannelPlan::findOrFail($id);
        $pageTitle = 'Edit Channel Plan: ' . $plan->name;
        return view('admin.channel_plans.edit', compact('plan', 'pageTitle'));
    }

    /**
     * Update the specified channel plan tier in storage.
     */
    public function update(Request $request, $id)
    {
        $plan = AdminChannelPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:120',
            'duration_days' => 'nullable|integer|min:1',
            'default_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $months = (int) $request->duration_months;
        $days = $request->filled('duration_days') ? (int) $request->duration_days : ($months * 30);

        $plan->update([
            'name' => $request->name,
            'duration_days' => $days,
            'duration_months' => $months,
            'default_price' => $request->default_price,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? $plan->sort_order,
        ]);

        $notify[] = ['success', 'Channel Plan Tier updated successfully.'];
        return redirect()->route('admin.channel-plans.index')->withNotify($notify);
    }

    /**
     * Toggle status of the specified channel plan tier.
     */
    public function status($id)
    {
        $plan = AdminChannelPlan::findOrFail($id);
        $plan->status = !$plan->status;
        $plan->save();

        $statusStr = $plan->status ? 'activated' : 'disabled';
        $notify[] = ['success', "Channel Plan Tier has been {$statusStr}."];
        return back()->withNotify($notify);
    }

    /**
     * Delete the specified channel plan tier.
     */
    public function delete($id)
    {
        $plan = AdminChannelPlan::findOrFail($id);
        $plan->delete();

        $notify[] = ['success', 'Channel Plan Tier deleted successfully.'];
        return back()->withNotify($notify);
    }

    /**
     * Display list of all purchased channel subscriptions.
     */
    public function subscriptions()
    {
        $pageTitle = 'Channel Subscriptions & Users';
        $subscriptions = ChannelSubscription::with(['user', 'channel.user', 'plan'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_subscriptions' => ChannelSubscription::count(),
            'active_subscriptions' => ChannelSubscription::active()->count(),
            'total_revenue' => Transaction::where('remark', 'channel_subscription_commission')->sum('amount'),
        ];

        return view('admin.channel_plans.subscriptions', compact('subscriptions', 'pageTitle', 'stats'));
    }
}
