<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Channel;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Services\UserService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class UserController extends Controller {

    protected UserService $userService;
    protected TransactionService $transactionService;

    public function __construct(UserService $userService, TransactionService $transactionService)
    {
        $this->userService = $userService;
        $this->transactionService = $transactionService;
    }




    public function monetizationSetting() {
        $pageTitle = "Monetization Center";
        $user      = auth()->user();

        $totalViews          = Video::where('user_id', $user->id)->sum('views_count') + \App\Models\Reel::where('user_id', $user->id)->sum('views_count');
        
        // Ensure channel exists to prevent errors
        $channel = Channel::firstOrCreate(['user_id' => $user->id], ['name' => $user->username . "\'s Channel"]);
        $totalSubscriber = Subscriber::where('channel_id', $channel->id)->count();
        
        $minViews = gs('minimum_views') ?? 1000;
        $minSubs  = gs('minimum_subscribe') ?? 100;

        $viewInPercent       = ($totalViews / ($minViews > 0 ? $minViews : 1)) * 100;
        $subscriberInPercent = ($totalSubscriber / ($minSubs > 0 ? $minSubs : 1)) * 100;
        
        return view('frontend.client.monetization.index', compact('pageTitle', 'user', 'totalViews', 'totalSubscriber', 'viewInPercent', 'subscriberInPercent', 'minViews', 'minSubs'));
    }

    public function applyForMonetization() {
        $user       = auth()->user();
        $totalViews = Video::where('user_id', $user->id)->sum('views_count') + \App\Models\Reel::where('user_id', $user->id)->sum('views_count');
        
        // Ensure channel exists
        $channel = Channel::firstOrCreate(['user_id' => $user->id], ['name' => $user->username . "\'s Channel"]);
        $totalSubscriber = Subscriber::where('channel_id', $channel->id)->count();

        $minSubs  = gs('minimum_subscribe') ?? 100;
        $minViews = gs('minimum_views') ?? 1000;

        if ($totalSubscriber < $minSubs) {
            $notify[] = ['error', 'You must have at least ' . $minSubs . ' subscribers.'];
            return back()->withNotify($notify);
        }

        if ($totalViews < $minViews) {
            $notify[] = ['error', 'You must have at least ' . $minViews . ' views.'];
            return back()->withNotify($notify);
        }

        $user->monetization_status = Status::MONETIZATION_APPLYING;
        $user->save();
        
        $notify[] = ['success', 'Application submitted successfully.'];
        return back()->withNotify($notify);
    }

    public function earnings() {
        $pageTitle = 'My Earnings';
        $user      = auth()->user();

        // 1. Settled Earnings (from Transactions)
        $settledAdEarnings  = $user->transactions()->where('remark', 'ads_revenue')->sum('amount');
        $videoSalesEarnings = $user->transactions()->whereIn('remark', ['earn_from_video', 'video_ppv_commission'])->sum('amount');
        
        // 2. Estimated Ad Earnings (from VideoEarning model - live impressions)
        $estimatedAdEarnings = \App\Models\VideoEarning::whereHas('video', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('estimated_revenue');

        $totalEarnings = $settledAdEarnings + $videoSalesEarnings + $estimatedAdEarnings;
        
        // Placeholder for other types if needed later
        $playlistEarnings = 0;
        $planEarnings     = 0;
        $adminCommission  = 0;

        $recentTransactions = $user->transactions()->with('video')->latest()->take(20)->get();

        return view('frontend.client.earnings', compact(
            'pageTitle', 
            'totalEarnings', 
            'settledAdEarnings', 
            'videoSalesEarnings', 
            'estimatedAdEarnings', 
            'playlistEarnings',
            'planEarnings',
            'adminCommission',
            'recentTransactions'
        ));
    }

    public function transactions() {
        $pageTitle = 'Transaction History';
        $transactions = auth()->user()->transactions()->paginate(getPaginate());
        return view('frontend.client.transactions', compact('pageTitle', 'transactions'));
    }

    public function notifications() {
        $pageTitle = 'My Notifications';
        if (auth()->check()) {
            auth()->user()->userNotifications()->where('is_read', 0)->update(['is_read' => 1]);
        }
        $notifications = auth()->user()->userNotifications()->latest()->paginate(getPaginate());
        return view('frontend.client.notifications', compact('pageTitle', 'notifications'));
    }

    public function kycForm() {
        if (auth()->user()->kv == 2) {
            $notify[] = ['warning', 'Your KYC is under review'];
            return to_route('user.kyc.data')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            return to_route('user.kyc.data');
        }
        $pageTitle = 'KYC Form';
        $form      = \App\Models\Form::where('act', 'kyc')->first();
        return view('frontend.client.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData() {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        $submission = \App\Models\KycSubmission::where('user_id', $user->id)->latest()->first();
        return view('frontend.client.kyc.info', compact('pageTitle', 'user', 'submission'));
    }

    public function kycSubmit(Request $request) {
        $rules = [
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'id_type' => 'required|string|in:passport,aadhaar,driving_license,pan_card,voter_id',
            'id_front' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'id_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'bank_name' => 'required|string|min:2|max:100',
            'account_holder' => 'required|string|min:2|max:100',
            'account_number' => 'required|string|regex:/^\d{9,18}$/',
            'ifsc' => 'required|string|regex:/^[A-Za-z0-9]{5,15}$/',
            'branch' => 'nullable|string|max:100',
            'selfie_image_data' => 'required|string',
        ];

        if ($request->id_type === 'aadhaar') {
            $rules['id_number'] = 'required|string|regex:/^\d{12}$/';
        } elseif ($request->id_type === 'pan_card') {
            $rules['id_number'] = 'required|string|regex:/^[A-Za-z]{5}\d{4}[A-Za-z]{1}$/';
        } elseif ($request->id_type === 'voter_id') {
            $rules['id_number'] = 'required|string|regex:/^[A-Za-z]{3}\d{7}$/';
        } else {
            $rules['id_number'] = 'required|string|min:5|max:30';
        }

        $request->validate($rules);

        $user = auth()->user();
        $submission = new \App\Models\KycSubmission();
        $submission->user_id = $user->id;
        $submission->full_name = $request->full_name;
        $submission->date_of_birth = $request->dob;
        $submission->address = $request->address;
        $submission->city = $request->city;
        $submission->state = $request->state;
        $submission->postal_code = $request->zip;
        $submission->id_type = $request->id_type;
        $submission->id_number = $request->id_number;
        $submission->bank_name = $request->bank_name;
        $submission->account_holder_name = $request->account_holder;
        $submission->account_number = $request->account_number;
        $submission->ifsc_code = $request->ifsc;
        $submission->branch_name = $request->branch;

        // Handle R2 Uploads
        if ($request->hasFile('id_front')) {
            $submission->id_document_front = \App\Helpers\ImageHelper::uploadToR2($request->file('id_front'), 'kyc/id_front');
        }
        if ($request->hasFile('id_back')) {
            $submission->id_document_back = \App\Helpers\ImageHelper::uploadToR2($request->file('id_back'), 'kyc/id_back');
        }

        // Handle Selfie (Base64 from live capture)
        if ($request->selfie_image_data) {
            $imgData = $request->selfie_image_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    throw new \Exception('invalid image type');
                }
                $imgData = base64_decode($imgData);
                if ($imgData === false) {
                    throw new \Exception('base64_decode failed');
                }
                
                $fileName = 'selfie_' . time() . '_' . uniqid() . '.' . $type;
                $tempPath = storage_path('app/public/temp_' . $fileName);
                file_put_contents($tempPath, $imgData);
                
                $submission->selfie_image = \App\Helpers\ImageHelper::uploadToR2(new \Illuminate\Http\File($tempPath), 'kyc/selfies');
                
                @unlink($tempPath);
            }
        }

        $submission->status = 0; // Pending
        $submission->save();

        $user->kv = 2; // Pending
        $user->save();
        
        $adminNotification            = new \App\Models\AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New KYC submission from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.users.kyc.details', $user->id);
        $adminNotification->save();

        $notify[] = ['success', 'KYC submission received and is under review.'];
        return to_route('user.kyc.data')->withNotify($notify);
    }

    public function kycBankEdit() {
        $user = auth()->user();
        $pageTitle = 'Update Bank Details';
        $submission = \App\Models\KycSubmission::where('user_id', $user->id)->latest()->first();
        
        if (!$submission) {
            $notify[] = ['error', 'Please submit your basic KYC first.'];
            return to_route('user.kyc.form')->withNotify($notify);
        }

        return view('frontend.client.kyc.bank_edit', compact('pageTitle', 'submission'));
    }

    public function kycBankUpdate(Request $request) {
        $request->validate([
            'bank_name' => 'required|string|min:2|max:100',
            'account_holder' => 'required|string|min:2|max:100',
            'account_number' => 'required|string|regex:/^\d{9,18}$/',
            'ifsc' => 'required|string|regex:/^[A-Za-z0-9]{5,15}$/',
            'branch' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $lastSubmission = \App\Models\KycSubmission::where('user_id', $user->id)->latest()->first();

        if (!$lastSubmission) {
            $notify[] = ['error', 'KYC record not found.'];
            return to_route('user.kyc.form')->withNotify($notify);
        }

        // Create a new submission based on the last one but with updated bank details
        $submission = new \App\Models\KycSubmission();
        $submission->user_id = $user->id;
        $submission->full_name = $lastSubmission->full_name;
        $submission->date_of_birth = $lastSubmission->date_of_birth;
        $submission->address = $lastSubmission->address;
        $submission->city = $lastSubmission->city;
        $submission->state = $lastSubmission->state;
        $submission->postal_code = $lastSubmission->postal_code;
        $submission->id_type = $lastSubmission->id_type;
        $submission->id_number = $lastSubmission->id_number;
        $submission->id_document_front = $lastSubmission->id_document_front;
        $submission->id_document_back = $lastSubmission->id_document_back;
        $submission->selfie_image = $lastSubmission->selfie_image;
        
        // Updated Bank Details
        $submission->bank_name = $request->bank_name;
        $submission->account_holder_name = $request->account_holder;
        $submission->account_number = $request->account_number;
        $submission->ifsc_code = $request->ifsc;
        $submission->branch_name = $request->branch;

        $submission->status = 0; // Set to Pending
        $submission->save();

        $user->kv = 2; // Set user to pending verification
        $user->save();

        $adminNotification            = new \App\Models\AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Bank details updated by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.users.kyc.details', $user->id);
        $adminNotification->save();

        $notify[] = ['success', 'Bank details updated. Your KYC is now under review.'];
        return to_route('user.kyc.data')->withNotify($notify);
    }

    public function saveConsent(Request $request)
    {
        $user = auth()->user();
        $user->data_consent = 1;
        $user->consent_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Consent recorded successfully.'
        ]);
    }

    public function checkKycAvailability(Request $request)
    {
        $field = $request->field;
        $value = $request->value;
        $user = auth()->user();

        // Map frontend fields to database columns
        $columnMap = [
            'id_number' => 'id_number',
            'account_number' => 'account_number'
        ];

        if (!isset($columnMap[$field])) {
            return response()->json(['error' => 'Invalid field']);
        }

        $column = $columnMap[$field];

        $exists = \App\Models\KycSubmission::where($column, $value)
            ->where('user_id', '!=', $user->id)
            ->whereIn('status', [0, 1]) // Pending or Approved
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'This ' . str_replace('_', ' ', $field) . ' is already registered with another account.']);
        }

        return response()->json(['success' => true]);
    }
}
