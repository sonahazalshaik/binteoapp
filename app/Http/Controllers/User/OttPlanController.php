<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\OttPlan;
use App\Models\OttSubscription;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;
use App\Mail\OttPlanPurchaseMail;

class OttPlanController extends Controller
{
    public function index()
    {
        $pageTitle = "OTT Membership Plans";
        $plans = OttPlan::where('status', 1)->latest()->get();
        $activeSubscription = OttSubscription::where('user_id', auth()->id())
            ->where('status', 1)
            ->where(function($q) {
                $q->where('end_date', '>', now())
                  ->orWhereNull('end_date');
            })
            ->first();
        
        return view('frontend.client.ott_plans.index', compact('pageTitle', 'plans', 'activeSubscription'));
    }

    public function buy($id)
    {
        $plan = OttPlan::findOrFail($id);
        
        // Check if user already has an active subscription
        $existing = OttSubscription::where('user_id', auth()->id())
            ->where('status', 1)
            ->where(function($q) {
                $q->where('end_date', '>', now())
                  ->orWhereNull('end_date');
            })
            ->first();

        if ($existing && $existing->ott_plan_id == $plan->id) {
            return response()->json(['error' => 'You already have this plan active.']);
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
        $deposit->detail = json_encode(['ott_plan_id' => $plan->id, 'type' => 'ott_plan']);
        $deposit->save();

        if ($plan->price <= 0) {
            $this->activateSubscription($deposit, $plan);

            try {
                app(MailService::class)->sendMailable(auth()->user()->email, new OttPlanPurchaseMail(auth()->user(), $plan, 'FREE_OTT'));
            } catch (\Exception $e) {
                Log::error('OTT Plan Purchase Email Failed for ' . auth()->user()->email . ': ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Free OTT plan activated!', 'redirect' => route('user.ott-plans.index')]);
        }

        // Razorpay Order
        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        Log::channel('razorpay')->info('OTT | Order creation requested', [
            'user_id'    => auth()->id(),
            'ott_plan_id' => $plan->id,
            'amount'     => $deposit->final_amount,
            'trx'        => $deposit->trx,
            'key_prefix' => substr((string) $apiKey, 0, 12),
        ]);

        try {
            $api = new Api($apiKey, $apiSecret);
            $order = $api->order->create([
                'receipt'         => $deposit->trx,
                'amount'          => round($deposit->final_amount * 100),
                'currency'        => 'INR',
                'payment_capture' => '1',
            ]);
            
            $deposit->btc_wallet = $order->id;
            $deposit->save();

            Log::channel('razorpay')->info('OTT | Order created successfully on Razorpay', [
                'user_id'    => auth()->id(),
                'ott_plan_id' => $plan->id,
                'order_id'   => $order->id,
                'amount'     => $order->amount,
                'status'     => $order->status ?? null,
                'trx'        => $deposit->trx,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'key' => $apiKey,
                'name' => auth()->user()->username,
                'email' => auth()->user()->email,
                'contact' => auth()->user()->mobile,
                'plan_name' => $plan->name,
                'trx' => $deposit->trx
            ]);
        } catch (\Exception $e) {
            Log::channel('razorpay')->error('OTT | Order creation FAILED on Razorpay', [
                'user_id' => auth()->id(),
                'ott_plan_id' => $plan->id,
                'trx'     => $deposit->trx,
                'error'   => $e->getMessage(),
            ]);
            \Illuminate\Support\Facades\Log::error("OTT Razorpay Order Creation Failed: " . $e->getMessage(), [
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'trx' => 'required'
        ]);

        \Illuminate\Support\Facades\Log::info("OTT Payment Verification Started", ['trx' => $request->trx, 'payment_id' => $request->razorpay_payment_id]);

        Log::channel('razorpay')->info('OTT | Verification callback received (handler fired = payment reached Razorpay success)', [
            'user_id'             => auth()->id(),
            'trx'                 => $request->trx,
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            $api->utility->verifyPaymentSignature($attributes);
            \Illuminate\Support\Facades\Log::info("Signature Verified Successfully", ['trx' => $request->trx]);

            Log::channel('razorpay')->info('OTT | Signature verified', ['trx' => $request->trx]);
            
            // Payment is auto-captured (payment_capture=1), verify it's captured
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            \Illuminate\Support\Facades\Log::info("Payment Status: " . $payment->status, ['trx' => $request->trx, 'amount' => $payment->amount]);

            Log::channel('razorpay')->info('OTT | Payment fetched from Razorpay API', [
                'trx'        => $request->trx,
                'payment_id' => $payment->id,
                'order_id'   => $payment->order_id ?? null,
                'status'     => $payment->status,
                'method'     => $payment->method ?? null,
                'amount'     => $payment->amount,
                'error_desc' => $payment->error_description ?? null,
            ]);

            $deposit = Deposit::where('trx', $request->trx)->where('status', 0)->firstOrFail();
            $deposit->status = 1;
            $deposit->save();
            \Illuminate\Support\Facades\Log::info("Deposit Status Updated", ['trx' => $request->trx]);

            $detail = json_decode($deposit->detail);
            $plan = OttPlan::findOrFail($detail->ott_plan_id);

            $this->activateSubscription($deposit, $plan);
            \Illuminate\Support\Facades\Log::info("OTT Subscription Activated", ['user_id' => auth()->id(), 'plan_id' => $plan->id]);

            try {
                app(MailService::class)->sendMailable(auth()->user()->email, new OttPlanPurchaseMail(auth()->user(), $plan, $request->trx));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('OTT Plan Purchase Email Failed for ' . auth()->user()->email . ': ' . $e->getMessage());
            }

            return response()->json(['success' => 'OTT Subscription activated successfully!']);
        } catch (\Exception $e) {
            Log::channel('razorpay')->error('OTT | Verification FAILED', [
                'trx'   => $request->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            \Illuminate\Support\Facades\Log::error("OTT Payment Verification Failed", [
                'trx' => $request->trx,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }

    private function activateSubscription($deposit, $plan)
    {
        // Deactivate old subscriptions if any
        OttSubscription::where('user_id', auth()->id())->update(['status' => 0]);

        $monthsToAdd = $plan->duration;
        if ($plan->duration >= 30 && $plan->duration % 30 == 0) {
            $monthsToAdd = $plan->duration / 30;
        } else if ($plan->duration == 365) {
            $monthsToAdd = 12;
        }

        OttSubscription::create([
            'user_id' => auth()->id(),
            'ott_plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'price' => $plan->price,
            'start_date' => now(),
            'end_date' => $plan->duration == 0 ? null : now()->addMonths($monthsToAdd),
            'status' => 1
        ]);
    }
}
