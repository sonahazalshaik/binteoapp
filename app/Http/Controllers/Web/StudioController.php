<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Video;
use App\Models\Reel;
use App\Services\Frontend\StudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudioController extends Controller
{
    protected StudioService $studioService;

    public function __construct(StudioService $studioService)
    {
        $this->studioService = $studioService;
    }

    public function analytics()
    {
        $data = $this->studioService->analytics();
        return view('frontend.studio.analytics', $data);
    }

    public function dashboard()
    {
        $data = $this->studioService->dashboard();
        return view('frontend.studio.dashboard', $data);
    }

    public function videos(Request $request)
    {
        $data = $this->studioService->videos($request);
        return view('frontend.studio.videos', $data);
    }

    public function savedAudios()
    {
        $data = $this->studioService->savedAudios();
        return view('frontend.studio.saved_audios', $data);
    }

    public function duets()
    {
        $data = $this->studioService->duets();
        return view('frontend.studio.duets', $data);
    }

    public function edit(Video $video)
    {
        if (auth()->id() != $video->user_id) abort(403);
        
        // All drafts must be resumed via the upload/create page
        if ($video->status === \App\Constants\Status::DRAFT || $video->status === 'draft') {
            return redirect()->route('videos.create', ['draft_id' => $video->id]);
        }

        $data = $this->studioService->edit($video);
        return view('frontend.studio.edit', $data);
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:20480',
            'language' => 'nullable|string|max:255',
            'price' => 'nullable|integer|min:0',
        ]);

        $result = $this->studioService->update($request, $video);
        $notify[] = ['success', $result['success']];
        return redirect()->route('studio.videos')->withNotify($notify);
    }

    public function destroy(Request $request, Video $video)
    {
        $this->studioService->destroy($video);
        $notify[] = ['success', 'Video deleted forever.'];

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->withNotify($notify);
        }

        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '/edit')) {
            return redirect()->route('studio.videos')->withNotify($notify);
        }

        return redirect()->back()->withNotify($notify);
    }

    public function monetization()
    {
        if (!auth()->user()->channel) {
            $notify[] = ['error', 'You must have a channel to access monetization.'];
            return redirect()->route('home')->withNotify($notify);
        }

        $data = $this->studioService->monetization();
        return view('frontend.studio.monetization', $data);
    }

    public function buyMonetization(Request $request)
    {
        $amount = gs('monetization_amount');
        if ($amount <= 0) {
            return response()->json(['error' => 'Paid monetization is currently disabled.']);
        }

        if (auth()->user()->monetization_status == \App\Constants\Status::MONETIZATION_APPROVED) {
            return response()->json(['error' => 'You are already monetized.']);
        }

        $razorpayConfig = gs('razorpay_config');
        if (!$razorpayConfig || empty($razorpayConfig->key) || empty($razorpayConfig->secret)) {
            return response()->json(['error' => 'Razorpay is not configured properly.']);
        }

        $api = new \Razorpay\Api\Api($razorpayConfig->key, $razorpayConfig->secret);
        
        \Illuminate\Support\Facades\Log::channel('razorpay')->info('MONETIZATION | Order creation requested', [
            'user_id'    => auth()->id(),
            'amount'     => $amount,
            'key_prefix' => substr((string) $razorpayConfig->key, 0, 12),
        ]);

        try {
            $order = $api->order->create([
                'receipt'         => 'monetization_' . auth()->id(),
                'amount'          => $amount * 100, // Razorpay takes amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ]);

            \Illuminate\Support\Facades\Log::channel('razorpay')->info('MONETIZATION | Order created successfully on Razorpay', [
                'user_id'  => auth()->id(),
                'order_id' => $order->id,
                'amount'   => $order->amount,
                'status'   => $order->status ?? null,
            ]);

            return response()->json([
                'key'         => $razorpayConfig->key,
                'amount'      => $amount * 100,
                'currency'    => 'INR',
                'order_id'    => $order->id,
                'name'        => gs('site_name'),
                'description' => 'Paid Monetization Unlock',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('MONETIZATION | Order creation FAILED on Razorpay', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to create Razorpay order: ' . $e->getMessage()]);
        }
    }

    public function verifyMonetizationPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $razorpayConfig = gs('razorpay_config');
        if (!$razorpayConfig || empty($razorpayConfig->key) || empty($razorpayConfig->secret)) {
            return response()->json(['error' => 'Razorpay is not configured properly.']);
        }

        $api = new \Razorpay\Api\Api($razorpayConfig->key, $razorpayConfig->secret);

        \Illuminate\Support\Facades\Log::channel('razorpay')->info('MONETIZATION | Verification callback received', [
            'user_id'             => auth()->id(),
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_signature'  => $request->razorpay_signature,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id'   => $request->razorpay_order_id
            ]);
            \Illuminate\Support\Facades\Log::channel('razorpay')->info('MONETIZATION | Signature verified', ['user_id' => auth()->id()]);
        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('MONETIZATION | Signature verification FAILED', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Payment verification failed. Invalid signature.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::channel('razorpay')->error('MONETIZATION | Verification FAILED', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Payment verification failed.']);
        }

        // Payment is successful, update user status
        $user = auth()->user();
        $user->monetization_status = \App\Constants\Status::MONETIZATION_APPROVED;
        $user->save();

        // Track the deposit
        $deposit = new \App\Models\Deposit();
        $deposit->user_id = auth()->id();
        $deposit->method_code = 507; 
        $deposit->method_currency = 'INR';
        $deposit->amount = gs('monetization_amount');
        $deposit->charge = 0;
        $deposit->rate = 1;
        $deposit->final_amount = gs('monetization_amount');
        $deposit->trx = getTrx();
        $deposit->status = 1;
        $deposit->save();

        return response()->json(['success' => true, 'message' => 'Monetization Unlocked Successfully!', 'redirect' => route('monetization')]);
    }

    public function storeMembership(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'perks' => 'required|string',
        ]);

        $this->studioService->storeMembership($request);
        $notify[] = ['success', 'Membership tier created successfully.'];
        return back()->withNotify($notify);
    }

    public function updateMembership(Request $request, \App\Models\Membership $membership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'perks' => 'required|string',
        ]);

        $this->studioService->updateMembership($request, $membership);
        $notify[] = ['success', 'Membership tier updated successfully.'];
        return back()->withNotify($notify);
    }

    public function destroyMembership(\App\Models\Membership $membership)
    {
        $this->studioService->destroyMembership($membership);
        $notify[] = ['success', 'Membership tier deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function reels()
    {
        $data = $this->studioService->reels();
        return redirect()->route('studio.videos', ['tab' => 'reels']);
    }

    public function editReel(Reel $reel)
    {
        if (auth()->id() != $reel->user_id) abort(403);
        $data = $this->studioService->editReel($reel);
        return view('frontend.studio.reel-edit', $data);
    }

    public function updateReel(Request $request, Reel $reel)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2200',
            'thumbnail'   => 'nullable|image|max:20480',
            'visibility'  => 'required|in:0,1',
            'language'    => 'nullable|string|max:255',
        ]);

        $result = $this->studioService->updateReel($request, $reel);
        $notify[] = ['success', $result['success']];
        return redirect()->route('studio.videos', ['tab' => 'reels'])->withNotify($notify);
    }

    public function destroyReel(Request $request, Reel $reel)
    {
        $this->studioService->destroyReel($reel);
        $notify[] = ['success', 'Reel deleted.'];

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->withNotify($notify);
        }

        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '/edit')) {
            return redirect()->route('studio.videos', ['tab' => 'reels'])->withNotify($notify);
        }

        return redirect()->back()->withNotify($notify);
    }

    public function purchasedVideos()
    {
        $data = $this->studioService->purchasedVideos();
        $data['pageTitle'] = 'My Purchased Content';
        return view('frontend.studio.purchased-videos', $data);
    }

    public function makePremium(Request $request, Video $video)
    {
        $result = $this->studioService->makePremium($request, $video);

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', $result['success']];
        return back()->withNotify($notify);
    }

    public function toggleFeatured(Video $video)
    {
        $result = $this->studioService->toggleFeatured($video);

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', $result['success']];
        return back()->withNotify($notify);
    }

    public function updateFcmToken(Request $request)
    {
        Log::info('[FCM] Token update request received', [
            'user_id' => auth()->id(),
            'token_prefix' => substr($request->token ?? '', 0, 20) . '...',
            'device_type' => $request->device_type,
            'ip' => $request->ip()
        ]);

        $request->validate(['token' => 'required|string']);
        $result = $this->studioService->updateFcmToken($request);

        Log::info('[FCM] Token update result: ' . json_encode($result));
        return response()->json($result);
    }

    public function deleteThumbnail(Video $video)
    {
        $result = $this->studioService->deleteThumbnail($video);

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
        } else {
            $notify[] = ['success', $result['success']];
        }

        return back()->withNotify($notify);
    }

    public function deleteReelThumbnail(Reel $reel)
    {
        $result = $this->studioService->deleteReelThumbnail($reel);

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
        } else {
            $notify[] = ['success', $result['success']];
        }

        return back()->withNotify($notify);
    }
}
