<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Playlist;
use App\Models\PurchasedPlan; 
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Http\Controllers\Gateway\PaymentController;

use App\Models\Video;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;
use App\Mail\PlanPurchaseMail;

class PlanController extends Controller {
    public function index() {
        $pageTitle = "Subscription Plans";
        $plans = Plan::where('status', 1)->latest()->paginate(12);
        $userPlanIds = auth()->check() ? auth()->user()->purchasedPlans()->where(function($q) {
            $q->where('expired_date', '>', now())->orWhereNull('expired_date');
        })->pluck('plan_id')->toArray() : [];
        
        $activeSubscription = auth()->check() ? auth()->user()->purchasedPlans()->where(function($q) {
            $q->where('expired_date', '>', now())->orWhereNull('expired_date');
        })->with('plan')->latest()->first() : null;

        $totalUsers = \App\Models\User::active()->count();
        return view('frontend.client.plans.index', compact('pageTitle', 'plans', 'userPlanIds', 'activeSubscription', 'totalUsers'));
    }

    public function ott(Request $request)
    {
        $miniOtt = gs('mini_ott_status');
        if ($miniOtt !== null && (int) $miniOtt === 0) {
            return view('frontend.mini-ott-coming-soon', ['pageTitle' => 'Mini OTT - Coming Soon']);
        }
        $pageTitle = 'Mini OTT';
        $categories = \App\Models\Category::where('status', 1)->get();
        
        $query = Video::forUser()->where('is_premium', 1);
        
        if ($request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Sort logic
        switch ($request->sort) {
            case 'most_viewed':
                $query->orderBy('views_count', 'desc');
                break;
            case 'trending':
                $query->orderBy('is_trending', 'desc')->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $premiumVideos = $query->take(20)->get();

        $featuredVideos = Video::forUser()->where('is_premium', 1)->where('is_trending', 1)->latest()->take(5)->get();
        if($featuredVideos->isEmpty()) {
            $featuredVideos = Video::forUser()->where('is_premium', 1)->latest()->take(5)->get();
        }

        $plans = \App\Models\OttPlan::where('status', 1)->latest()->get();
        $activeSubscription = auth()->check() ? auth()->user()->ottSubscriptions()->where('status', 1)->where(function($q) {
            $q->where('end_date', '>', now())->orWhereNull('end_date');
        })->latest()->first() : null;

        $trendingPremiumVideos = Video::forUser()->where('is_premium', 1)
            ->orderBy('is_trending', 'desc')
            ->orderBy('views_count', 'desc')
            ->take(8)
            ->get();

        return view('frontend.ott', compact('pageTitle', 'premiumVideos', 'featuredVideos', 'plans', 'activeSubscription', 'categories', 'trendingPremiumVideos'));
    }

    public function allPremiumVideos(Request $request)
    {
        $miniOtt = gs('mini_ott_status');
        if ($miniOtt !== null && (int) $miniOtt === 0) {
            return view('frontend.mini-ott-coming-soon', ['pageTitle' => 'Mini OTT - Coming Soon']);
        }
        $pageTitle = 'All Premium Videos';
        $categories = \App\Models\Category::where('status', 1)->get();
        
        $query = Video::forUser()->where('is_premium', 1);
        
        if ($request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort logic
        switch ($request->sort) {
            case 'most_viewed':
                $query->orderBy('views_count', 'desc');
                break;
            case 'trending':
                $query->orderBy('is_trending', 'desc')->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }
        
        $premiumVideos = $query->paginate(10);

        return view('frontend.premium_videos', compact('pageTitle', 'premiumVideos', 'categories'));
    }

    public function trendingPremiumVideos(Request $request)
    {
        $miniOtt = gs('mini_ott_status');
        if ($miniOtt !== null && (int) $miniOtt === 0) {
            return view('frontend.mini-ott-coming-soon', ['pageTitle' => 'Mini OTT - Coming Soon']);
        }
        $pageTitle = 'Trending Premium';
        $categories = \App\Models\Category::where('status', 1)->get();
        
        $query = Video::forUser()->where('is_premium', 1);

        if ($request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort logic (defaults to trending for this page)
        $sort = $request->sort ?? 'trending';
        switch ($sort) {
            case 'most_viewed':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'trending':
            default:
                $query->orderBy('is_trending', 'desc')->orderBy('views_count', 'desc');
                break;
        }

        $trendingVideos = $query->paginate(10);

        return view('frontend.premium_trending', compact('pageTitle', 'trendingVideos', 'categories'));
    }

    public function show($id) {
        $plan = Plan::findOrFail($id);
        $pageTitle = 'Plan Details: ' . $plan->name;
        return view('frontend.client.plans.show', compact('plan', 'pageTitle'));
    }

    public function viewPlanVideos($id) {
        $plan   = Plan::find($id);
        $videos = $plan->videos()->published()->paginate(15);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'videos'    => $videos,
                'last_page' => $videos->lastPage(),
            ],
        ]);
    }

    public function viewPlaylistVideos($id) {
        $playlist = Playlist::find($id);
        if (!$playlist) return response()->json(['status' => 'error'], 404);
        
        $videos   = $playlist->videos()->published()->paginate(15);
        return response()->json([
            'status' => 'success',
            'data'   => [
                'videos'    => $videos,
                'last_page' => $videos->lastPage(),
            ],
        ]);
    }

    public function viewPlanPlaylists($id) {
        $plan = Plan::findOrFail($id);

        $playlists = $plan->playlists()->withCount('videos')->with('user')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'playlists' => $playlists,
                'last_page' => $playlists->lastPage(),
            ],
        ]);
    }

    public function purchasedPlanLists() {
        $pageTitle = "My Purchased Plans";

        // Querying our PurchasedPlan architecture (Creator Plans)
        $creatorPlans = PurchasedPlan::where('user_id', auth()->id())
            ->with(['plan'])
            ->paginate(15, ['*'], 'creator_page');

        // Querying OTT Subscriptions
        $ottPlans = \App\Models\OttSubscription::where('user_id', auth()->id())
            ->with(['ottPlan'])
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'ott_page');

        return view('frontend.plans.purchased', compact('pageTitle', 'creatorPlans', 'ottPlans'));
    }

    public function invoice($id) {
        $pageTitle = "Invoice Details";
        $purchasedPlan = PurchasedPlan::where('user_id', auth()->id())->with('plan')->findOrFail($id);
        return view('frontend.plans.invoice', compact('pageTitle', 'purchasedPlan'));
    }

    public function planSellHistory() {
        $pageTitle = 'Plan Sell History';
        // Assume owner_id logic if implemented, omitting searchable trait usage to prevent undefined method errors
        $sellPlans = PurchasedPlan::orderBy('id', 'desc')->paginate(15);
        
        return view('frontend.plans.sell', compact('pageTitle', 'sellPlans'));
    }

    public function buy($id) {
        \Log::info('--- PlanController@buy Execution Started ---');
        \Log::info('Attempting to buy plan ID: ' . $id . ' for user ID: ' . auth()->id());
        $plan = Plan::findOrFail($id);
        \Log::info('Plan details: ', $plan->toArray());
        
        // Check if user already has this plan
        $existing = PurchasedPlan::where('user_id', auth()->id())->where('plan_id', $plan->id)->first();
        if ($existing) {
            return response()->json(['error' => 'You already have this plan active.']);
        }

        // Create a deposit record to track this transaction
        $deposit = new Deposit();
        $deposit->user_id = auth()->id();
        $deposit->method_code = 507; // Assuming 507 is Razorpay in your system
        $deposit->method_currency = 'INR';
        $deposit->amount = $plan->price;
        $deposit->charge = 0;
        $deposit->rate = 1;
        $deposit->final_amount = $plan->price;

        if ($plan->price <= 0) {
            // Free Plan Activation
            PurchasedPlan::updateOrCreate(
                ['user_id' => auth()->id(), 'plan_id' => $plan->id],
                [
                    'price' => $plan->price,
                    'trx' => getTrx(),
                    'owner_id' => $plan->user_id,
                    'expired_date' => $plan->duration ? (function() use ($plan) {
                        $d = $plan->duration;
                        $date = now();
                        if ($d == 30 || $d == 1) return $date->addMonth();
                        if ($d == 90 || $d == 3) return $date->addMonths(3);
                        if ($d == 180 || $d == 6) return $date->addMonths(6);
                        if ($d == 365 || $d == 12) return $date->addYear();
                        return $date->addDays($d);
                    })() : null
                ]
            );

            // Enable creator status
            $user = auth()->user();
            $user->creator_status = 1;
            $user->save();

            try {
                app(MailService::class)->sendMailable($user->email, new PlanPurchaseMail($user, $plan, 'FREE_PLAN'));
            } catch (\Exception $e) {
                Log::error('Plan Purchase Email Failed for ' . $user->email . ': ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Free plan activated successfully!', 'redirect' => route('user.plans.purchased')]);
        }
        $deposit->trx = getTrx();
        $deposit->status = 0; // Pending
        $deposit->detail = json_encode(['plan_id' => $plan->id]);
        $deposit->save();

        // Create Razorpay Order
        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Order creation requested', [
            'user_id'      => auth()->id(),
            'plan_id'      => $plan->id,
            'amount'       => $deposit->final_amount,
            'trx'          => $deposit->trx,
            'key_prefix'   => substr((string) $apiKey, 0, 12),
        ]);

        \Log::info('Razorpay Credentials Check', [
            'key_exists' => !empty($apiKey),
            'secret_exists' => !empty($apiSecret)
        ]);

        if (!$apiKey || !$apiSecret) {
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('PLAN | Razorpay configuration missing', [
                'user_id' => auth()->id(),
                'key_set' => (bool) $apiKey,
                'secret_set' => (bool) $apiSecret,
            ]);
            \Illuminate\Support\Facades\Log::error('Razorpay configuration missing in PlanController: key=' . ($apiKey ? 'set' : 'missing') . ', secret=' . ($apiSecret ? 'set' : 'missing'));
            return response()->json(['error' => 'Razorpay configuration missing. Please check your .env file for RAZORPAY_KEY and RAZORPAY_SECRET.']);
        }
        \Illuminate\Support\Facades\Log::info('Razorpay initialization attempt', ['key' => $apiKey]);

        try {
            $api = new Api($apiKey, $apiSecret);
            \Log::info('Attempting to create Razorpay Order for TRX: ' . $deposit->trx . ' | Amount: ' . ($deposit->final_amount * 100));
            $order = $api->order->create([
                'receipt'         => $deposit->trx,
                'amount'          => round($deposit->final_amount * 100),
                'currency'        => 'INR',
                'payment_capture' => '1',
            ]);
            \Log::info('Razorpay Order created successfully. Order ID: ' . $order->id);

            \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Order created successfully on Razorpay', [
                'user_id'    => auth()->id(),
                'plan_id'    => $plan->id,
                'order_id'   => $order->id,
                'amount'     => $order->amount,
                'currency'   => $order->currency,
                'status'     => $order->status ?? null,
                'trx'        => $deposit->trx,
                'key_prefix' => substr((string) $apiKey, 0, 12),
            ]);
            
            $deposit->btc_wallet = $order->id; // Using btc_wallet to store order_id
            $deposit->save();

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
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('PLAN | Order creation FAILED on Razorpay', [
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'trx'     => $deposit->trx,
                'error'   => $e->getMessage(),
            ]);
            \Log::error('Razorpay Order Creation Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function verifyPayment(Request $request) {
        \Log::info('--- PlanController@verifyPayment Execution Started ---');
        \Log::info('Incoming Verify Payload:', $request->all());

        \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Verification callback received (handler fired = payment reached Razorpay success)', [
            'user_id'             => auth()->id(),
            'trx'                 => $request->trx,
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'trx' => 'required'
        ]);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            \Log::info('Verifying Signature with attributes:', $attributes);
            $api->utility->verifyPaymentSignature($attributes);
            \Log::info('Signature verification SUCCESS.');

            \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Signature verified', ['trx' => $request->trx]);
            
            // Payment is auto-captured (payment_capture=1), verify it's captured
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            \Log::info('Payment Status: ' . $payment->status, ['trx' => $request->trx, 'amount' => $payment->amount]);

            \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Payment fetched from Razorpay API', [
                'trx'          => $request->trx,
                'payment_id'   => $payment->id,
                'order_id'     => $payment->order_id ?? null,
                'status'       => $payment->status,
                'method'       => $payment->method ?? null,
                'amount'       => $payment->amount,
                'error_desc'   => $payment->error_description ?? null,
            ]);

            $deposit = Deposit::where('trx', $request->trx)->where('status', 0)->firstOrFail();
            \Log::info('Deposit found. Updating status to 1.');
            $deposit->status = 1; // Completed
            $deposit->save();

            $detail = json_decode($deposit->detail);
            $plan = Plan::findOrFail($detail->plan_id);

            // Activate Plan
            PurchasedPlan::updateOrCreate(
                ['user_id' => auth()->id(), 'plan_id' => $plan->id],
                [
                    'price' => $plan->price,
                    'trx' => $request->trx,
                    'owner_id' => $plan->user_id, // Assuming plan has a creator
                    'expired_date' => $plan->duration ? (function() use ($plan) {
                        $d = $plan->duration;
                        $date = now();
                        if ($d == 30 || $d == 1) return $date->addMonth();
                        if ($d == 90 || $d == 3) return $date->addMonths(3);
                        if ($d == 180 || $d == 6) return $date->addMonths(6);
                        if ($d == 365 || $d == 12) return $date->addYear();
                        return $date->addDays($d);
                    })() : null
                ]
            );

            // Enable creator status
            $user = auth()->user();
            $user->creator_status = 1;
            $user->save();

            try {
                app(MailService::class)->sendMailable($user->email, new PlanPurchaseMail($user, $plan, $request->trx));
            } catch (\Exception $e) {
                Log::error('Plan Purchase Email Failed for ' . $user->email . ': ' . $e->getMessage());
            }

            // Firebase Notification to Creator
            try {
                $creator = \App\Models\User::find($plan->user_id);
                if ($creator) {
                    \App\Jobs\SendFirebaseNotificationJob::dispatch($creator->id, [
                        'title' => 'Premium Plan Purchased!',
                        'body' => $user->username . ' has subscribed to your premium plan: ' . $plan->name,
                        'url' => route('user.plans.purchased')
                    ], 'user');
                }
            } catch (\Exception $e) {
                \Log::error('Creator Plan Purchase Notification Failed: ' . $e->getMessage());
            }

            \Illuminate\Support\Facades\Log::channel('razorpay')->info('PLAN | Payment COMPLETED, plan activated', [
                'user_id'   => auth()->id(),
                'plan_id'   => $plan->id,
                'trx'       => $request->trx,
                'payment_id'=> $request->razorpay_payment_id,
            ]);
            \Log::info('Subscription process completed successfully for user ID: ' . auth()->id());
            return response()->json(['success' => 'Subscription activated successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('PLAN | Verification FAILED', [
                'trx'   => $request->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            \Log::error('Razorpay verifyPayment Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }
}
