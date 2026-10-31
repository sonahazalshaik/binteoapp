<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotificationJob;
use App\Mail\ChannelPlanPurchaseMail;
use App\Models\Channel;
use App\Models\ChannelPlan;
use App\Models\ChannelSubscription;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VideoCommission;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Razorpay\Api\Api;

class ChannelPlanController extends Controller
{
    /**
     * Display Creator Channel Subscription Plans Management Dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $channel = $user->channel;

        if (!$channel) {
            $notify[] = ['error', 'You must create a channel first to manage channel plans.'];
            return redirect()->route('studio.dashboard')->withNotify($notify);
        }

        $pageTitle = 'Creator Channel Plans';

        // Graceful fallback if migrations haven't run yet
        if (!Schema::hasTable('channel_plans')) {
            $plans = collect();
            $activeSubscribersCount = 0;
            $totalEarnings = 0;
            $recentSubscriptions = collect();

            return view('frontend.user.studio.channel_plans', compact(
                'pageTitle',
                'channel',
                'plans',
                'activeSubscribersCount',
                'totalEarnings',
                'recentSubscriptions'
            ))->with('migrationPending', true);
        }

        // Initialize standard tiers for this channel if not yet existing
        $standardTiers = ChannelPlan::standardTiers();
        foreach ($standardTiers as $type => $tierData) {
            ChannelPlan::firstOrCreate(
                [
                    'channel_id' => $channel->id,
                    'duration_type' => $type,
                ],
                [
                    'name' => $tierData['label'],
                    'duration_days' => $tierData['days'],
                    'price' => 0.00,
                    'description' => $tierData['description'],
                    'status' => false,
                ]
            );
        }

        // Load channel plans ordered by duration
        $plans = ChannelPlan::where('channel_id', $channel->id)
            ->orderBy('duration_days', 'asc')
            ->get();

        // Stats & subscribers
        $activeSubscribersCount = 0;
        $totalEarnings = 0;
        $recentSubscriptions = collect();

        if (Schema::hasTable('channel_subscriptions')) {
            $activeSubscribersCount = ChannelSubscription::where('channel_id', $channel->id)
                ->active()
                ->count();

            $totalEarnings = ChannelSubscription::where('channel_id', $channel->id)
                ->where('status', true)
                ->sum('amount');

            $recentSubscriptions = ChannelSubscription::with(['user', 'plan'])
                ->where('channel_id', $channel->id)
                ->latest()
                ->take(10)
                ->get();
        }

        return view('frontend.user.studio.channel_plans', compact(
            'pageTitle',
            'channel',
            'plans',
            'activeSubscribersCount',
            'totalEarnings',
            'recentSubscriptions'
        ));
    }

    /**
     * Update a specific channel plan's price and details.
     */
    public function update(Request $request)
    {
        $channel = auth()->user()->channel;
        if (!$channel) {
            return response()->json(['success' => false, 'message' => 'Channel not found.'], 403);
        }

        $request->validate([
            'plan_id' => 'required|integer|exists:channel_plans,id',
            'price' => 'required|numeric|min:0',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $plan = ChannelPlan::where('channel_id', $channel->id)
            ->where('id', $request->plan_id)
            ->firstOrFail();

        $plan->price = $request->price;
        if ($request->filled('name')) {
            $plan->name = $request->name;
        }
        if ($request->has('description')) {
            $plan->description = $request->description;
        }
        if ($request->has('status')) {
            $plan->status = (bool) $request->status;
        }
        if ($plan->price <= 0) {
            $plan->status = false;
        }
        $plan->save();

        $activeCount = ChannelPlan::where('channel_id', $channel->id)->where('status', true)->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$plan->duration_label} plan updated successfully!",
                'plan' => $plan,
                'active_count' => $activeCount,
            ]);
        }

        $notify[] = ['success', "{$plan->duration_label} plan updated successfully."];
        return back()->withNotify($notify);
    }

    /**
     * Toggle active/inactive status of a channel plan.
     */
    public function toggle(Request $request)
    {
        $channel = auth()->user()->channel;
        if (!$channel) {
            return response()->json(['success' => false, 'message' => 'Channel not found.'], 403);
        }

        $request->validate([
            'plan_id' => 'required|integer|exists:channel_plans,id',
        ]);

        $plan = ChannelPlan::where('channel_id', $channel->id)
            ->where('id', $request->plan_id)
            ->firstOrFail();

        // If trying to activate but price is 0
        if (!$plan->status && $plan->price <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Please set a price greater than 0 before activating this plan.',
                'status' => false
            ], 422);
        }

        $plan->status = !$plan->status;
        if ($plan->price <= 0) {
            $plan->status = false;
        }
        $plan->save();

        $statusText = $plan->status ? 'activated' : 'deactivated';
        $activeCount = ChannelPlan::where('channel_id', $channel->id)->where('status', true)->count();

        return response()->json([
            'success' => true,
            'status' => (bool) $plan->status,
            'active_count' => $activeCount,
            'message' => "{$plan->duration_label} plan {$statusText} successfully!",
        ]);
    }

    /**
     * Initiate Purchase for a Channel Subscription Plan.
     */
    public function purchase(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required to subscribe.'], 401);
        }

        $plan = ChannelPlan::with('channel.user')->findOrFail($id);

        if (!$plan->status) {
            return response()->json(['error' => 'This plan is currently not available for subscription.']);
        }

        if (auth()->id() == optional($plan->channel)->user_id) {
            return response()->json(['error' => 'You cannot subscribe to your own channel.']);
        }

        // Check if user already has an active subscription for this channel
        if (Schema::hasTable('channel_subscriptions')) {
            $existing = ChannelSubscription::where('user_id', auth()->id())
                ->where('channel_id', $plan->channel_id)
                ->active()
                ->first();

            if ($existing) {
                return response()->json([
                    'error' => "You already have an active subscription for this channel until " . ($existing->expires_at ? $existing->expires_at->format('M d, Y') : 'Active') . "."
                ]);
            }
        }

        // Create a deposit record
        $deposit = new Deposit();
        $deposit->user_id = auth()->id();
        $deposit->method_code = 507; // Razorpay
        $deposit->method_currency = 'INR';
        $deposit->amount = $plan->price;
        $deposit->charge = 0;
        $deposit->rate = 1;
        $deposit->final_amount = $plan->price;
        $deposit->trx = getTrx();
        $deposit->status = 0; // Pending
        $deposit->detail = json_encode(['channel_plan_id' => $plan->id, 'type' => 'channel_plan']);
        $deposit->save();

        // If plan is free (0 price)
        if ($plan->price <= 0) {
            $subscription = $this->activateChannelSubscription($deposit, $plan, $deposit->trx);
            $this->sendMembershipNotifications(auth()->user(), $plan, $subscription, $deposit->trx);
            return response()->json([
                'success' => true,
                'free_activated' => true,
                'message' => 'Free channel membership activated successfully!'
            ]);
        }

        // Razorpay Order Creation
        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        try {
            $api = new Api($apiKey, $apiSecret);
            $order = $api->order->create([
                'receipt'         => $deposit->trx,
                'amount'          => (int) round($deposit->final_amount * 100),
                'currency'        => 'INR',
                'payment_capture' => '1',
            ]);

            $deposit->btc_wallet = $order->id;
            $deposit->save();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'key' => $apiKey,
                'name' => auth()->user()->username ?? auth()->user()->name,
                'email' => auth()->user()->email,
                'contact' => auth()->user()->mobile ?? '',
                'plan_name' => $plan->name ?: $plan->duration_label,
                'site_name' => gs('site_name') ?? 'Binteo',
                'trx' => $deposit->trx,
            ]);
        } catch (\Exception $e) {
            Log::error('Channel Plan Razorpay Order Failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'trx'     => $deposit->trx,
            ]);
            return response()->json(['error' => 'Payment initialization failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify payment signature and activate channel subscription with creator/admin commission.
     */
    public function paymentCallback(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required.'], 401);
        }

        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
            'trx'                 => 'required|string',
        ]);

        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        try {
            $api = new Api($apiKey, $apiSecret);

            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);

            $payment = $api->payment->fetch($request->razorpay_payment_id);
            if ($payment->status !== 'captured') {
                return response()->json(['error' => 'Payment was not captured. Please contact support.'], 400);
            }

            $deposit = Deposit::where('trx', $request->trx)->where('status', 0)->firstOrFail();
            $deposit->status = 1;
            $deposit->save();

            $detail = json_decode($deposit->detail);
            $plan = ChannelPlan::with('channel.user')->findOrFail($detail->channel_plan_id);

            DB::beginTransaction();

            // 1. Activate channel subscription
            $subscription = $this->activateChannelSubscription($deposit, $plan, $request->razorpay_payment_id);

            // 2. Calculate Creator Share and Admin Commission Split
            $channel = $plan->channel;
            $creator = $channel ? $channel->user : null;
            $totalAmount = (float) $plan->price;
            $creatorPercent = (float) (gs('ppv_creator_commission_percent') ?? 70.00);
            $creatorAmount = round(($totalAmount * $creatorPercent) / 100, 2);
            $adminCommission = round($totalAmount - $creatorAmount, 2);

            if ($creator && $creatorAmount > 0) {
                $creator->balance += $creatorAmount;
                $creator->save();

                Transaction::create([
                    'user_id' => $creator->id,
                    'amount' => $creatorAmount,
                    'post_balance' => $creator->balance,
                    'charge' => $adminCommission,
                    'trx_type' => '+',
                    'details' => 'Commission from channel membership: ' . ($plan->name ?: $plan->duration_label) . ' (' . ($channel ? $channel->name : '') . ')',
                    'trx' => $request->razorpay_payment_id,
                    'remark' => 'channel_subscription_commission',
                ]);

                if (Schema::hasTable('video_commissions')) {
                    VideoCommission::create([
                        'video_id' => 0,
                        'purchaser_id' => auth()->id(),
                        'creator_id' => $creator->id,
                        'total_amount' => $totalAmount,
                        'admin_commission' => $adminCommission,
                        'creator_commission' => $creatorAmount,
                        'trx' => $request->razorpay_payment_id,
                    ]);
                }
            }

            // 3. Record Subscriber Transaction log
            Transaction::create([
                'user_id' => auth()->id(),
                'amount' => $totalAmount,
                'post_balance' => auth()->user()->balance,
                'charge' => 0,
                'trx_type' => '-',
                'details' => 'Subscribed to channel: ' . ($channel ? $channel->name : '') . ' (' . ($plan->name ?: $plan->duration_label) . ')',
                'trx' => $request->razorpay_payment_id,
                'remark' => 'channel_subscription_purchase',
            ]);

            DB::commit();

            // 4. Send In-App, Firebase Push, and Email Notifications
            $this->sendMembershipNotifications(auth()->user(), $plan, $subscription, $request->razorpay_payment_id);

            return response()->json([
                'success' => true,
                'message' => 'Channel membership activated successfully! You now have full access to all premium videos on this channel.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Channel Plan Payment Verification Failed: ' . $e->getMessage(), [
                'trx' => $request->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Helper to activate or renew channel subscription.
     */
    private function activateChannelSubscription($deposit, $plan, $trx)
    {
        if (Schema::hasTable('channel_subscriptions')) {
            ChannelSubscription::where('user_id', auth()->id())
                ->where('channel_id', $plan->channel_id)
                ->update(['status' => false]);

            return ChannelSubscription::create([
                'user_id' => auth()->id(),
                'channel_id' => $plan->channel_id,
                'channel_plan_id' => $plan->id,
                'plan_name' => $plan->name ?: $plan->duration_label,
                'amount' => $plan->price,
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
                'payment_method' => 'razorpay',
                'trx' => $trx,
                'status' => true,
            ]);
        }
    }

    /**
     * Send in-app, Firebase push, and email notifications for new channel membership.
     */
    private function sendMembershipNotifications($subscriber, $plan, $subscription, $trx)
    {
        try {
            $channel = $plan->channel ?? Channel::find($plan->channel_id);
            $creator = $channel ? $channel->user : null;
            $planName = $plan->name ?: ($plan->duration_days . ' Days');
            $channelName = $channel ? $channel->name : 'Channel';
            $subscriberName = $subscriber->firstname ? ($subscriber->firstname . ' ' . $subscriber->lastname) : ($subscriber->username ?? 'A member');
            $channelUrl = $channel ? route('channels.premium', $channel->slug) : url('/');
            $studioUrl = route('channel-plans.index');
            $channelAvatar = ($channel && $channel->avatar) ? getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) : '';

            // 1. NOTIFICATIONS FOR CHANNEL OWNER (CREATOR)
            if ($creator && $creator->id !== $subscriber->id) {
                // In-App Notification
                UserNotification::create([
                    'user_id' => $creator->id,
                    'sender_id' => $subscriber->id,
                    'title' => "{$subscriberName} joined {$channelName} membership ({$planName})! 👑",
                    'click_url' => $studioUrl,
                ]);

                // Firebase Push Notification
                SendFirebaseNotificationJob::dispatch($creator->id, [
                    'title' => 'New Channel Member! 👑',
                    'body' => "{$subscriberName} purchased {$planName} membership for {$channelName}!",
                    'url' => $studioUrl,
                    'sender_id' => $subscriber->id,
                    'image' => $channelAvatar,
                ], 'user');
            }

            // 2. IN-APP & FIREBASE PUSH NOTIFICATION FOR SUBSCRIBER (BUYER)
            UserNotification::create([
                'user_id' => $subscriber->id,
                'sender_id' => $creator ? $creator->id : null,
                'title' => "Your {$channelName} membership ({$planName}) is now active! 👑",
                'click_url' => $channelUrl,
            ]);

            SendFirebaseNotificationJob::dispatch($subscriber->id, [
                'title' => 'Membership Activated! 👑',
                'body' => "You now have access to all exclusive premium videos on {$channelName}!",
                'url' => $channelUrl,
                'image' => $channelAvatar,
            ], 'user');

            // 3. EMAIL NOTIFICATION FOR THE BUYER (PURCHASER)
            if (!empty($subscriber->email)) {
                try {
                    app(MailService::class)->sendMailable(
                        $subscriber->email,
                        new ChannelPlanPurchaseMail($subscriber, $channel, $plan, $subscription, $trx)
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('MailService failed for channel plan, attempting Laravel Mail fallback: ' . $mailErr->getMessage());
                    try {
                        Mail::to($subscriber->email)->send(
                            new ChannelPlanPurchaseMail($subscriber, $channel, $plan, $subscription, $trx)
                        );
                    } catch (\Throwable $fallbackErr) {
                        Log::error('Channel Plan Purchase Mail Fallback Failed: ' . $fallbackErr->getMessage());
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Channel Plan Notifications Dispatch Error: ' . $e->getMessage(), [
                'plan_id' => $plan->id ?? null,
                'subscriber_id' => $subscriber->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
