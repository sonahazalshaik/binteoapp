<?php
namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use App\Services\ZeptoMailService;
use App\Mail\MonetizationApprovalMail;

class ManageUsersController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users = $this->userData();
        $plans = \App\Models\Plan::active()->get();
        return view('admin.users.index', compact('pageTitle', 'users', 'plans'));
    }

    public function creators()
    {
        $pageTitle = 'Creators';
        $users = $this->userData('creators');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function regularUsers()
    {
        $pageTitle = 'Regular Users';
        $users = $this->userData('regularUsers');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }


    public function activeUsers()
    {
        $pageTitle = 'Active Users';
        $users = $this->userData('active');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Blocked Users';
        $users = $this->userData('banned');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users = $this->userData('emailUnverified');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users = $this->userData('kycUnverified');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers()
    {
        $pageTitle = 'KYC Pending Users';
        $users = $this->userData('kycPending');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users = $this->userData('emailVerified');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }


    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users = $this->userData('mobileUnverified');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }


    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users = $this->userData('mobileVerified');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }


    public function usersWithBalance()
    {
        $pageTitle = 'Users with Balance';
        $users = $this->userData('withBalance');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }




    public function monetizationRequest()
    {
        $pageTitle = 'Applying for Monetization';
        $users = $this->userData('monetizationRequest');
        return view('admin.users.index', compact('pageTitle', 'users'));
    }


    protected function userData($scope = null){
        return $this->service->userQuery($scope)->paginate(getPaginate());
    }

    public function policyViolators()
    {
        $pageTitle = 'Policy Violations';
        $users = $this->service->getPolicyViolators();
            
        return view('admin.users.violators', compact('pageTitle', 'users'));
    }

    public function detail($id)
    {
        $user = User::with(['channel'])->findOrFail($id);
        $pageTitle = 'Profile Intelligence: ' . $user->username;

        $detail = $this->service->getUserDetail($user);
        $totalDeposit     = $detail['totalDeposit'];
        $totalWithdrawals = $detail['totalWithdrawals'];
        $totalTransaction = $detail['totalTransaction'];
        $widget           = $detail['widget'];
        $userPlans        = $detail['userPlans'];
        $ottPlans         = $detail['ottPlans'];
        $transactions     = $detail['transactions'];
        $kycSubmission    = $detail['kycSubmission'];

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        
        return view('admin.users.detail', compact(
            'pageTitle', 
            'user', 
            'totalDeposit', 
            'totalWithdrawals', 
            'totalTransaction', 
            'countries', 
            'widget',
            'userPlans',
            'ottPlans',
            'transactions',
            'kycSubmission'
        ));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user = User::findOrFail($id);
        $submission = \App\Models\KycSubmission::where('user_id', $user->id)->latest()->first();
        return view('admin.kyc.details', compact('pageTitle','user', 'submission'));
    }

    public function kycEdit($id)
    {
        $submission = \App\Models\KycSubmission::findOrFail($id);
        $pageTitle = 'Edit KYC Portfolio - ' . $submission->user->username;
        return view('admin.kyc.edit', compact('pageTitle', 'submission'));
    }

    public function kycUpdate(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'id_type' => 'required|string|in:passport,aadhaar,driving_license,pan_card,voter_id',
            'id_number' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'bank_name' => 'required|string',
            'account_holder' => 'required|string',
            'account_number' => 'required|string',
            'ifsc' => 'required|string',
            'branch' => 'nullable|string',
            'id_front' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'id_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status' => 'required|integer|in:0,1,2',
            'admin_feedback' => 'nullable|string'
        ]);

        $submission = \App\Models\KycSubmission::findOrFail($id);

        $data = $request->only([
            'full_name', 'dob', 'id_type', 'id_number', 'address', 'city', 'state', 'zip',
            'bank_name', 'account_holder', 'account_number', 'ifsc', 'branch',
            'admin_feedback', 'status'
        ]);

        $files = [];
        foreach (['id_front', 'id_back', 'selfie'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $files[$fileKey] = $request->file($fileKey);
            }
        }

        $this->service->updateKycSubmission($submission, $data, $files);

        $notify[] = ['success', 'KYC portfolio updated and synchronized successfully'];
        return back()->withNotify($notify);
    }

    public function kycAll()
    {
        $pageTitle = 'All KYC Submissions';
        $search = request()->search;
        $submissions = $this->service->getKycList(null, $search);
        $emptyMessage = 'No KYC submissions found';
        return view('admin.kyc.index', compact('pageTitle', 'submissions', 'emptyMessage'));
    }

    public function kycPendingList()
    {
        $pageTitle = 'Pending KYC Audits';
        $search = request()->search;
        $submissions = $this->service->getKycList('pending', $search);
        $emptyMessage = 'No pending KYC audits';
        return view('admin.kyc.index', compact('pageTitle', 'submissions', 'emptyMessage'));
    }

    public function kycApprovedList()
    {
        $pageTitle = 'Approved KYC Submissions';
        $search = request()->search;
        $submissions = $this->service->getKycList('approved', $search);
        $emptyMessage = 'No approved KYC submissions';
        return view('admin.kyc.index', compact('pageTitle', 'submissions', 'emptyMessage'));
    }

    public function kycRejectedList()
    {
        $pageTitle = 'Rejected KYC Submissions';
        $search = request()->search;
        $submissions = $this->service->getKycList('rejected', $search);
        $emptyMessage = 'No rejected KYC submissions';
        return view('admin.kyc.index', compact('pageTitle', 'submissions', 'emptyMessage'));
    }
    
    public function kycCreate()
    {
        $pageTitle = 'Manual KYC Onboarding';
        $users = User::whereHas('channel')->orderBy('username')->get();
        return view('admin.kyc.create', compact('pageTitle', 'users'));
    }

    public function kycStore(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'id_type' => 'required|string|in:passport,aadhaar,driving_license,pan_card,voter_id',
            'id_number' => 'required|string',
            'bank_name' => 'required|string',
            'account_holder' => 'required|string',
            'account_number' => 'required|string',
            'ifsc' => 'required|string',
            'id_front' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'id_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ];

        $request->validate($rules);

        $data = $request->only([
            'user_id', 'full_name', 'dob', 'id_type', 'id_number',
            'bank_name', 'account_holder', 'account_number', 'ifsc',
            'address', 'city', 'state', 'zip', 'branch'
        ]);

        $files = [];
        foreach (['id_front', 'id_back', 'selfie'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $files[$fileKey] = $request->file($fileKey);
            }
        }

        $this->service->createKycSubmission($data, $files);

        $notify[] = ['success', 'Manual KYC profile created and verified successfully'];
        return to_route('admin.users.kyc.all')->withNotify($notify);
    }

    public function kycDelete($id)
    {
        $this->service->deleteKycSubmission($id);

        return response()->json([
            'success' => true,
            'message' => 'Identity verification record has been delete and user status reset.'
        ]);
    }

    public function checkKycAvailability(Request $request)
    {
        $field = $request->field;
        $value = $request->value;
        $userId = $request->user_id;

        $exists = $this->service->checkKycAvailability($field, $value, $userId);

        if ($exists) {
            return response()->json(['error' => 'This ' . str_replace('_', ' ', $field) . ' is already registered with another account.']);
        }

        return response()->json(['success' => true]);
    }

    public function add()
    {
        $pageTitle = 'Architect New Node';
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.create', compact('pageTitle', 'countries'));
    }

    public function store(Request $request)
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email',
            'mobile' => 'required|string|max:40',
            'username' => 'required|string|max:40|unique:users,username',
            'password' => 'required|string|min:6',
            'country' => 'required|in:'.$countries,
            'channel_name' => 'nullable|string|max:255',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'channel_avatar' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'channel_banner' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $exists = User::where('mobile',$request->mobile)->where('dial_code',$dialCode)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'mobile' => $request->mobile,
            'dial_code' => $dialCode,
            'country_code' => $countryCode,
            'country_name' => @$country,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'status' => Status::USER_ACTIVE,
            'ev' => $request->ev ? Status::VERIFIED : Status::UNVERIFIED,
            'sv' => $request->sv ? Status::VERIFIED : Status::UNVERIFIED,
            'kv' => $request->kv ? Status::KYC_VERIFIED : Status::KYC_UNVERIFIED,
            'creator_status' => $request->creator_status ? 1 : 0,
            'monetization_status' => $request->monetization_status ? 1 : 0,
            'advertiser_status' => $request->advertiser_status ? 1 : 0,
            'channel_name' => $request->channel_name,
        ];

        $files = [];
        foreach (['image', 'channel_avatar', 'channel_banner'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $files[$fileKey] = $request->file($fileKey);
            }
        }

        $user = $this->service->createUser($data, $files);

        $notify[] = ['success', 'Node instantiated successfully'];
        return to_route('admin.users.detail', $user->id)->withNotify($notify);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Edit User - ' . $user->username;
        $detail = $this->service->getUserDetail($user);
        $totalDeposit     = $detail['totalDeposit'];
        $totalWithdrawals = $detail['totalWithdrawals'];
        $totalTransaction = $detail['totalTransaction'];
        $widget['totalSubscriber']    = $user->subscribers->count();
        $widget['totalVideos']        = $user->videos->count();
        $widget['totalRegularVideos'] = $user->videos()->regular()->count();
        $widget['totalShortsVideos'] = $user->videos()->shorts()->count();
        $widget['totalPublicVideos']  = $user->videos()->public()->count();
        $widget['totalPrivateVideos'] = $user->videos()->private()->count();
        $widget['totalStockVideos']   = $user->videos()->stock()->count();
        $widget['totalFreeVideos']    = $user->videos()->free()->count();
        $countries          = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.edit', compact('pageTitle', 'user','totalDeposit','totalWithdrawals','totalTransaction','countries', 'widget'));
    }

    public function monetizationDetail($id){
        $pageTitle = 'Monetization Details';
        $user = User::where('monetization_status', '!=', Status::MONETIZATION_INITIATE)->findOrFail($id);
        $totalViews = $user->videos()->sum('views_count');
        $totalSubscriber = $user->subscribers()->count();

        return view('admin.users.monetization_detail', compact('pageTitle', 'user', 'totalViews', 'totalSubscriber'));

    }



    public function kycApprove($id)
    {
        \Illuminate\Support\Facades\Log::info("====================================");
        \Illuminate\Support\Facades\Log::info("KYC APPROVE ACTION TRIGGERED");
        \Illuminate\Support\Facades\Log::info("ID FROM REQUEST: " . $id);
        
        try {
            $submission = \App\Models\KycSubmission::findOrFail($id);
            $user = $submission->user;

            $this->service->approveKyc($id);

            \Illuminate\Support\Facades\Log::info("KYC APPROVED FOR USER: " . $user->username);

            try {
                notify($user, 'KYC_APPROVE', []);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("NOTIFICATION FAILED: " . $e->getMessage());
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'KYC approved successfully'
                ]);
            }

            $notify[] = ['success', 'KYC approved successfully'];
            return to_route('admin.users.kyc.pending')->withNotify($notify);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("KYC APPROVE ERROR: " . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);

        \Illuminate\Support\Facades\Log::info("====================================");
        \Illuminate\Support\Facades\Log::info("KYC REJECT ACTION TRIGGERED");
        \Illuminate\Support\Facades\Log::info("ID FROM REQUEST: " . $id);
        \Illuminate\Support\Facades\Log::info("REASON: " . $request->reason);

        try {
            $submission = \App\Models\KycSubmission::findOrFail($id);
            $user = $submission->user;

            $this->service->rejectKyc($id, $request->reason);

            \Illuminate\Support\Facades\Log::info("KYC REJECTED FOR USER: " . $user->username);

            try {
                notify($user, 'KYC_REJECT', [
                    'reason' => $request->reason
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("NOTIFICATION FAILED: " . $e->getMessage());
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'KYC rejected successfully'
                ]);
            }

            $notify[] = ['success', 'KYC rejected successfully'];
            return to_route('admin.users.kyc.pending')->withNotify($notify);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("KYC REJECT ERROR: " . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:'.$countries,
            'password' => 'nullable|string|min:6',
            'description' => 'nullable|string|max:1000',
            'social_links' => 'nullable|array',
            'channel_name' => 'nullable|string|max:255',
            'channel_description' => 'nullable|string|max:1000',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'channel_avatar' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'channel_banner' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $exists = User::where('mobile',$request->mobile)->where('dial_code',$dialCode)->where('id','!=',$user->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'dial_code' => $dialCode,
            'country_code' => $countryCode,
            'country_name' => @$country,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'description' => $request->description,
            'social_links' => $request->social_links,
            'password' => $request->password,
            'status' => $request->status ?? $user->status,
            'ev' => $request->ev ? Status::VERIFIED : Status::UNVERIFIED,
            'sv' => $request->sv ? Status::VERIFIED : Status::UNVERIFIED,
            'kv' => $request->kv ? Status::KYC_VERIFIED : Status::KYC_UNVERIFIED,
            'creator_status' => $request->creator_status ? 1 : 0,
            'monetization_status' => $request->monetization_status ? 1 : 0,
            'advertiser_status' => $request->advertiser_status ? 1 : 0,
            'channel_name' => $request->channel_name,
            'channel_description' => $request->channel_description,
        ];

        $files = [];
        foreach (['image', 'channel_avatar', 'channel_banner'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $files[$fileKey] = $request->file($fileKey);
            }
        }

        $this->service->updateUser($user, $data, $files);

        $notify[] = ['success', 'User branding and details updated successfully'];
        return back()->withNotify($notify);
    }



    public function login($id){
        Auth::loginUsingId($id);
        return to_route('home');
    }

    public function status(Request $request,$id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason'=>'required|string|max:255'
            ]);
            $this->service->toggleUserStatus($user, $request->reason);
            $notify[] = ['success','User banned successfully'];
        }else{
            $this->service->toggleUserStatus($user);
            $notify[] = ['success','User unbanned successfully'];
        }
        return back()->withNotify($notify);

    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids'    => 'required|array',
            'ids.*'  => 'required',
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        if (!$ids || count($ids) == 0) {
            $notify[] = ['error', 'No users selected'];
            return back()->withNotify($notify);
        }

        $this->service->bulkAction($ids, $action);

        $notify[] = ['success', 'Bulk action executed successfully'];
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning','Notification options are disabled currently'];
            return to_route('admin.users.detail',$user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        return $this->service->sendNotificationToSingle($request, $id);
    }

    public function showNotificationAllForm()
    {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToUser = User::notifyToUser();
        $users        = User::active()->count();
        $pageTitle    = 'Notification to Verified Users';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.users.notification_all', compact('pageTitle', 'users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request)
    {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        return $this->service->sendNotificationToAll($request);
    }


    public function countBySegment($methodName){
        return $this->service->countBySegment($methodName);
    }

    public function list()
    {
        $users = $this->service->searchUsers(request()->search);
        return response()->json([
            'success' => true,
            'users'   => $users,
            'more'    => $users->hasMorePages()
        ]);
    }

    public function notificationLog($id){
        $user = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to '.$user->username;
        $logs = $this->service->getUserNotificationLogs($id);
        return view('admin.reports.notification_history', compact('pageTitle','logs','user'));
    }



    public function monetizationChart(Request $request, $id) {

        $user = User::findOrFail($id);

        $chartData = $this->service->getMonetizationChart($user, $request->start_date, $request->end_date);

        return response()->json($chartData);
    }

    public function monetizationApprove($id){
        $user = User::findOrFail($id);
        $this->service->approveMonetization($user);

        $notify[] =['success', 'Monetization status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function monetizationReject($id){
        $user = User::findOrFail($id);
        $this->service->rejectMonetization($user);
        $notify[] =['success', 'Monetization status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'type' => 'required|in:1,2', // 1: Add, 2: Subtract
            'remark' => 'required|string|max:255',
            'payment_type' => 'nullable|string|max:255',
            'payment_id' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $general = GeneralSetting::first();

        $result = $this->service->adjustBalance(
            $user,
            $request->amount,
            $request->type,
            $request->remark,
            $request->payment_type,
            $request->payment_id
        );

        if (!$result['success']) {
            $notify[] = ['error', $result['error']];
            return back()->withNotify($notify);
        }

        $transaction = $result['transaction'];

        if ($result['type'] == 'add') {
            notify($user, 'BAL_ADD', [
                'trx' => $transaction->trx,
                'amount' => showAmount($request->amount),
                'remark' => $request->remark,
                'post_balance' => showAmount($user->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . showAmount($request->amount) . ' added successfully'];
        } else {
            notify($user, 'BAL_SUB', [
                'trx' => $transaction->trx,
                'amount' => showAmount($request->amount),
                'remark' => $request->remark,
                'post_balance' => showAmount($user->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . showAmount($request->amount) . ' subtracted successfully'];
        }

        return back()->withNotify($notify);
    }

    public function addPlan(Request $request, $id)
    {
        $request->validate([
            'plan_type_selection' => 'required|in:ott,creator',
            'plan_id' => 'required|integer',
        ]);

        $user = User::findOrFail($id);

        if ($request->plan_type_selection === 'ott') {
            $plan = \App\Models\OttPlan::findOrFail($request->plan_id);
            $subscription = new \App\Models\OttSubscription();
            $subscription->user_id = $user->id;
            $subscription->ott_plan_id = $plan->id;
            $subscription->plan_name = $plan->name;
            $subscription->price = $plan->price;
            $subscription->start_date = now();
            $subscription->end_date = now()->addDays((int)($plan->duration ?? 30));
            $subscription->status = 1;
            $subscription->save();
        } else {
            $plan = \App\Models\Plan::findOrFail($request->plan_id);
            $purchase = new \App\Models\PurchasedPlan();
            $purchase->user_id = $user->id;
            $purchase->plan_id = $plan->id;
            $purchase->owner_id = $plan->user_id ?? null;
            $purchase->price = $plan->price;
            $purchase->trx = getTrx();
            $purchase->expired_date = now()->addDays((int)($plan->duration ?? 30));
            $purchase->save();
        }

        $notify[] = ['success', 'Plan assigned successfully'];
        return back()->withNotify($notify);
    }

    public function deletionRequests()
    {
        $pageTitle = 'Pending Deletion Requests';
        $requests = \App\Models\DeletionRequest::where('status', 'pending')
            ->with(['user', 'marketplace'])
            ->latest()
            ->paginate(getPaginate());
        $emptyMessage = 'No pending deletion requests';
        return view('admin.users.deletion_requests', compact('pageTitle', 'requests', 'emptyMessage'));
    }

    public function deletionLogs()
    {
        $pageTitle = 'Deleted Account Logs';
        $logs = \App\Models\DeletedAccountLog::latest()->paginate(getPaginate());
        $emptyMessage = 'No deletion logs found';
        return view('admin.users.deletion_logs', compact('pageTitle', 'logs', 'emptyMessage'));
    }

    public function approveDeletion($id)
    {
        $request = \App\Models\DeletionRequest::findOrFail($id);

        if ($request->marketplace_id) {
            $talent = \App\Models\MarketPlace::find($request->marketplace_id);
            if (!$talent) {
                $request->delete();
                $notify[] = ['error', 'Marketplace user not found. Request cleared.'];
                return back()->withNotify($notify);
            }

            // Cache details
            $userName = $talent->name;
            $email = $talent->email;
            $phone = $talent->number;
            $role = 'talent';
            $finalBalance = 0;
            $joinedAt = $talent->created_at;
            $reason = $request->reason . ($request->custom_reason ? ': ' . $request->custom_reason : '');

            // Log
            \App\Models\DeletedAccountLog::create([
                'user_id' => $talent->id,
                'user_name' => $userName,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'final_balance' => $finalBalance,
                'reason' => $request->reason,
                'custom_reason' => $request->custom_reason,
                'joined_at' => $joinedAt,
                'deleted_at' => now(),
            ]);

            // Purge and delete assets
            $talent->deleteWithAssets();

            // Email
            try {
                app(\App\Services\MailService::class)->sendMailable($email, new \App\Mail\AccountDeletedMail($userName, $reason));
            } catch (\Exception $e) {
                Log::error("Failed sending account deleted email: " . $e->getMessage());
            }

            $request->delete();

            $notify[] = ['success', 'Marketplace account deletion request approved and data successfully purged.'];
            return back()->withNotify($notify);
        }

        $user = $request->user;

        if (!$user) {
            $request->delete();
            $notify[] = ['error', 'User not found. Request cleared.'];
            return back()->withNotify($notify);
        }

        // Cache details for log and email
        $userName = $user->fullname;
        $email = $user->email;
        $phone = '+' . $user->dial_code . $user->mobile;
        $role = $user->role ?? 'user';
        $finalBalance = $user->balance ?? 0;
        $joinedAt = $user->created_at;
        $reason = $request->reason . ($request->custom_reason ? ': ' . $request->custom_reason : '');

        // 1. Create Deletion Log Entry
        \App\Models\DeletedAccountLog::create([
            'user_id' => $user->id,
            'user_name' => $userName,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'final_balance' => $finalBalance,
            'reason' => $request->reason,
            'custom_reason' => $request->custom_reason,
            'joined_at' => $joinedAt,
            'deleted_at' => now(),
        ]);

        // 2. Perform Thorough Related Data Purge
        // Videos & all related comments, likes, view logs, formats, subtitles, tags
        $user->videos()->get()->each(function ($video) {
            try {
                $video->deleteWithAssets();
            } catch (\Exception $e) {
                Log::error("Failed to delete video {$video->id} assets: " . $e->getMessage());
                $video->delete();
            }
        });

        // Reels & related assets
        $user->reels()->get()->each(function ($reel) {
            try {
                if ($reel->isBunnyReel() && $reel->bunny_id) {
                    app(\App\Services\BunnyStreamService::class)->useReelLibrary()->deleteVideo($reel->bunny_id);
                }
            } catch (\Exception $e) {
                Log::error("Failed deleting Bunny Reel file: " . $e->getMessage());
            }

            foreach (['video_path', 'compressed_video_path', 'thumbnail_path'] as $field) {
                if ($reel->$field) {
                    $filePath = public_path(getFilePath('reel') . '/' . $reel->$field);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            $reel->delete();
        });

        // Channel and its assets
        $channel = $user->channel;
        if ($channel) {
            if ($channel->avatar) {
                try {
                    \App\Helpers\ImageHelper::deleteImage($channel->avatar);
                } catch (\Exception $e) {
                    Log::warning("Failed deleting channel avatar: " . $e->getMessage());
                }
            }
            if ($channel->banner) {
                try {
                    \App\Helpers\ImageHelper::deleteImage($channel->banner);
                } catch (\Exception $e) {
                    Log::warning("Failed deleting channel banner: " . $e->getMessage());
                }
            }
            $channel->delete();
        }

        // User Avatar
        if ($user->image) {
            try {
                \App\Helpers\ImageHelper::deleteImage($user->image);
            } catch (\Exception $e) {
                Log::warning("Failed deleting user avatar: " . $e->getMessage());
            }
        }

        // Clean up remaining comments, playlists, likes, and requests
        $user->comments()->delete();
        $user->reelComments()->delete();
        $user->playlists()->get()->each(fn($p) => $p->delete());
        $user->likes()->delete();
        $user->reelLikes()->delete();
        $user->deletionRequest()->delete();

        // Delete User
        $user->delete();

        // 3. Send Notification Email
        try {
            app(\App\Services\MailService::class)->sendMailable($email, new \App\Mail\AccountDeletedMail($userName, $reason));
        } catch (\Exception $e) {
            Log::error("Failed sending account deleted email: " . $e->getMessage());
        }

        $notify[] = ['success', 'Account deletion request approved and data successfully purged.'];
        return back()->withNotify($notify);
    }

    public function rejectDeletion($id)
    {
        $request = \App\Models\DeletionRequest::findOrFail($id);
        $request->delete();

        $notify[] = ['success', 'Account deletion request rejected and cleared successfully.'];
        return back()->withNotify($notify);
    }



}

