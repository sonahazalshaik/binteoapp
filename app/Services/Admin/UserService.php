<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Lib\FormProcessor;
use App\Lib\UserNotificationSender;
use App\Models\Channel;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\KycSubmission;
use App\Models\NotificationLog;
use App\Models\Plan;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Withdrawal;
use App\Models\ViewLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function userQuery(?string $scope = null)
    {
        $query = $scope ? User::$scope() : User::query();
        return $query->searchable(['username', 'email'])->filter(['status'])->sortFilter('id', 'desc');
    }

    public function createUser(array $data, ?array $files = []): User
    {
        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->mobile = $data['mobile'];
        $user->dial_code = $data['dial_code'];
        $user->country_code = $data['country_code'];
        $user->country_name = $data['country_name'] ?? null;
        $user->password = bcrypt($data['password']);
        $user->password_text = encrypt($data['password']);
        $user->address = $data['address'] ?? null;
        $user->city = $data['city'] ?? null;
        $user->state = $data['state'] ?? null;
        $user->zip = $data['zip'] ?? null;
        $user->status = $data['status'] ?? Status::USER_ACTIVE;
        $user->ev = $data['ev'] ?? Status::UNVERIFIED;
        $user->sv = $data['sv'] ?? Status::UNVERIFIED;
        $user->kv = $data['kv'] ?? Status::KYC_UNVERIFIED;
        $user->creator_status = $data['creator_status'] ?? 0;
        $user->monetization_status = $data['monetization_status'] ?? 0;
        $user->advertiser_status = $data['advertiser_status'] ?? 0;

        if (isset($files['image'])) {
            $user->image = \App\Helpers\ImageHelper::uploadToR2($files['image'], 'userProfile');
        }

        $user->save();

        if (isset($data['channel_name']) || isset($files['channel_avatar']) || isset($files['channel_banner'])) {
            $this->createUserChannel($user, $data, $files);
        }

        return $user;
    }

    public function updateUser(User $user, array $data, ?array $files = []): User
    {
        $user->firstname = $data['firstname'] ?? $user->firstname;
        $user->lastname = $data['lastname'] ?? $user->lastname;
        $user->name = trim(($data['firstname'] ?? $user->firstname) . ' ' . ($data['lastname'] ?? $user->lastname));
        $user->email = $data['email'] ?? $user->email;
        $user->mobile = $data['mobile'] ?? $user->mobile;
        $user->dial_code = $data['dial_code'] ?? $user->dial_code;
        $user->country_code = $data['country_code'] ?? $user->country_code;
        $user->country_name = $data['country_name'] ?? $user->country_name;
        $user->address = $data['address'] ?? $user->address;
        $user->city = $data['city'] ?? $user->city;
        $user->state = $data['state'] ?? $user->state;
        $user->zip = $data['zip'] ?? $user->zip;
        $user->description = $data['description'] ?? $user->description;
        $user->social_links = $data['social_links'] ?? $user->social_links;

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
            $user->password_text = encrypt($data['password']);
        }

        $user->status = $data['status'] ?? $user->status;
        $user->ev = $data['ev'] ?? $user->ev;
        $user->sv = $data['sv'] ?? $user->sv;
        $user->kv = $data['kv'] ?? $user->kv;
        $user->creator_status = $data['creator_status'] ?? $user->creator_status;
        $user->monetization_status = $data['monetization_status'] ?? $user->monetization_status;
        $user->advertiser_status = $data['advertiser_status'] ?? $user->advertiser_status;

        if (isset($files['image'])) {
            $user->image = \App\Helpers\ImageHelper::uploadToR2($files['image'], 'userProfile');
            if ($user->channel) {
                $user->channel->avatar = $user->image;
                $user->channel->save();
            }
        }

        $user->save();

        if (array_key_exists('channel_name', $data) || isset($files['channel_avatar']) || isset($files['channel_banner'])) {
            $this->updateUserChannel($user, $data, $files);
        }

        return $user;
    }

    public function toggleUserStatus(User $user, ?string $reason = null): string
    {
        if ($user->status == Status::USER_ACTIVE) {
            $user->status = Status::USER_BAN;
            $user->ban_reason = $reason;
            $user->save();
            return 'banned';
        }

        $user->status = Status::USER_ACTIVE;
        $user->ban_reason = null;
        $user->save();

        return 'unbanned';
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function bulkAction(array $ids, string $action): void
    {
        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            match ($action) {
                'block' => $user->update(['status' => Status::USER_BAN, 'ban_reason' => 'Bulk restriction by administrator']),
                'unblock' => $user->update(['status' => Status::USER_ACTIVE, 'ban_reason' => null]),
                'approve' => $user->update(['ev' => Status::VERIFIED, 'sv' => Status::VERIFIED]),
                'unapprove' => $user->update(['ev' => Status::UNVERIFIED, 'sv' => Status::UNVERIFIED]),
                'delete' => $user->delete(),
                default => null,
            };
        }
    }

    public function loginAsUser(User $user): void
    {
        Auth::loginUsingId($user->id);
    }

    public function getUserDetail(User $user): array
    {
        $user->loadCount([
            'subscribers', 'videos', 'reels', 'playlists',
            'comments', 'tickets', 'userNotifications',
            'likedVideos', 'watchHistories',
        ])->load(['channel']);

        return [
            'totalDeposit' => Deposit::where('user_id', $user->id)->successful()->sum('amount'),
            'totalWithdrawals' => Withdrawal::where('user_id', $user->id)->approved()->sum('amount'),
            'totalTransaction' => Transaction::where('user_id', $user->id)->count(),
            'widget' => [
                'totalSubscriber' => $user->subscribers_count,
                'totalVideos' => $user->videos_count,
                'totalReels' => $user->reels_count,
                'totalPlaylists' => $user->playlists_count,
                'totalComments' => $user->comments_count,
                'totalTickets' => $user->tickets_count,
                'totalNotifications' => $user->user_notifications_count,
                'totalLikedVideos' => $user->liked_videos_count,
                'totalWatchHistory' => $user->watch_histories_count,
                'totalRegularVideos' => $user->videos()->regular()->count(),
                'totalShortsVideos' => $user->videos()->shorts()->count(),
                'totalPublicVideos' => $user->videos()->public()->count(),
                'totalPrivateVideos' => $user->videos()->private()->count(),
                'totalStockVideos' => $user->videos()->stock()->count(),
                'totalFreeVideos' => $user->videos()->free()->count(),
            ],
            'userPlans' => $user->purchasedPlans()->with('plan')->latest()->get(),
            'ottPlans' => $user->ottSubscriptions()->with('ottPlan')->latest()->get(),
            'transactions' => $user->transactions()->latest()->limit(50)->get(),
            'kycSubmission' => KycSubmission::where('user_id', $user->id)->latest()->first(),
        ];
    }

    public function getPolicyViolators(int $perPage = 15)
    {
        return User::where('status', Status::USER_BAN)
            ->orWhereHas('copyrightStrikes', fn($q) => $q->where('status', 'active'))
            ->withCount(['copyrightStrikes' => fn($q) => $q->where('status', 'active')])
            ->paginate($perPage);
    }

    public function getKycList(?string $status = null, ?string $search = null, int $perPage = 15)
    {
        $query = KycSubmission::latest()->with('user');

        if ($status !== null) {
            $statusMap = ['pending' => 0, 'approved' => 1, 'rejected' => 2];
            $query->where('status', $statusMap[$status] ?? $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        return $query->paginate($perPage);
    }

    public function approveKyc(int $submissionId): void
    {
        DB::table('kyc_submissions')->where('id', $submissionId)->update([
            'status' => 1,
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
            'updated_at' => now(),
        ]);

        $submission = KycSubmission::findOrFail($submissionId);
        $user = $submission->user;
        $user->kv = Status::KYC_VERIFIED;
        $user->save();
    }

    public function rejectKyc(int $submissionId, string $reason): void
    {
        DB::table('kyc_submissions')->where('id', $submissionId)->update([
            'status' => 2,
            'admin_feedback' => $reason,
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
            'updated_at' => now(),
        ]);

        $submission = KycSubmission::findOrFail($submissionId);
        $user = $submission->user;
        $user->kv = Status::KYC_UNVERIFIED;
        $user->kyc_rejection_reason = $reason;
        $user->save();
    }

    public function createKycSubmission(array $data, ?array $files = []): KycSubmission
    {
        $submission = new KycSubmission();
        $submission->user_id = $data['user_id'];
        $submission->full_name = $data['full_name'];
        $submission->date_of_birth = $data['dob'];
        $submission->id_type = $data['id_type'];
        $submission->id_number = $data['id_number'];
        $submission->address = $data['address'] ?? null;
        $submission->city = $data['city'] ?? null;
        $submission->state = $data['state'] ?? null;
        $submission->postal_code = $data['zip'] ?? null;
        $submission->bank_name = $data['bank_name'];
        $submission->account_holder_name = $data['account_holder'];
        $submission->account_number = $data['account_number'];
        $submission->ifsc_code = $data['ifsc'];
        $submission->branch_name = $data['branch'] ?? null;

        if (isset($files['id_front'])) {
            $submission->id_document_front = \App\Helpers\ImageHelper::uploadToR2($files['id_front'], 'kyc/id_front');
        }
        if (isset($files['id_back'])) {
            $submission->id_document_back = \App\Helpers\ImageHelper::uploadToR2($files['id_back'], 'kyc/id_back');
        }
        if (isset($files['selfie'])) {
            $submission->selfie_image = \App\Helpers\ImageHelper::uploadToR2($files['selfie'], 'kyc/selfies');
        }

        $submission->status = $data['status'] ?? 1;
        $submission->admin_feedback = $data['admin_feedback'] ?? null;
        $submission->save();

        $user = User::findOrFail($data['user_id']);
        $user->kv = $submission->status == 1 ? Status::KYC_VERIFIED : Status::KYC_UNVERIFIED;
        $user->save();

        return $submission;
    }

    public function updateKycSubmission(KycSubmission $submission, array $data, ?array $files = []): KycSubmission
    {
        $submission->full_name = $data['full_name'] ?? $submission->full_name;
        $submission->date_of_birth = $data['dob'] ?? $submission->date_of_birth;
        $submission->id_type = $data['id_type'] ?? $submission->id_type;
        $submission->id_number = $data['id_number'] ?? $submission->id_number;
        $submission->address = $data['address'] ?? $submission->address;
        $submission->city = $data['city'] ?? $submission->city;
        $submission->state = $data['state'] ?? $submission->state;
        $submission->postal_code = $data['zip'] ?? $submission->postal_code;
        $submission->bank_name = $data['bank_name'] ?? $submission->bank_name;
        $submission->account_holder_name = $data['account_holder'] ?? $submission->account_holder_name;
        $submission->account_number = $data['account_number'] ?? $submission->account_number;
        $submission->ifsc_code = $data['ifsc'] ?? $submission->ifsc_code;
        $submission->branch_name = $data['branch'] ?? $submission->branch_name;
        $submission->admin_feedback = $data['admin_feedback'] ?? $submission->admin_feedback;
        $submission->status = $data['status'] ?? $submission->status;

        if (isset($files['id_front'])) {
            $submission->id_document_front = \App\Helpers\ImageHelper::uploadToR2($files['id_front'], 'kyc/id_front');
        }
        if (isset($files['id_back'])) {
            $submission->id_document_back = \App\Helpers\ImageHelper::uploadToR2($files['id_back'], 'kyc/id_back');
        }
        if (isset($files['selfie'])) {
            $submission->selfie_image = \App\Helpers\ImageHelper::uploadToR2($files['selfie'], 'kyc/selfies');
        }

        $submission->save();

        $user = $submission->user;
        if ($submission->status == 1) {
            $user->kv = Status::KYC_VERIFIED;
        } elseif ($submission->status == 2) {
            $user->kv = Status::KYC_UNVERIFIED;
            $user->kyc_rejection_reason = $data['admin_feedback'] ?? null;
        } else {
            $user->kv = Status::KYC_PENDING;
        }
        $user->save();

        return $submission;
    }

    public function deleteKycSubmission(int $submissionId): void
    {
        $submission = KycSubmission::findOrFail($submissionId);
        $user = $submission->user;
        $submission->delete();
        $user->kv = 0;
        $user->save();
    }

    public function checkKycAvailability(string $field, string $value, ?int $userId = null): bool
    {
        $columnMap = [
            'id_number' => 'id_number',
            'account_number' => 'account_number',
        ];

        $column = $columnMap[$field] ?? null;
        if (!$column) {
            return false;
        }

        return KycSubmission::where($column, $value)
            ->where('user_id', '!=', $userId)
            ->whereIn('status', [0, 1])
            ->exists();
    }

    public function getKycForm()
    {
        return Form::where('act', 'kyc')->first();
    }

    public function updateKycSetting(Request $request): void
    {
        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);
        $exist = Form::where('act', 'kyc')->first();
        $formProcessor->generate('kyc', $exist, 'act');
    }

    public function getMonetizationDetail(User $user): array
    {
        return [
            'totalViews' => $user->videos()->sum('views_count'),
            'totalSubscriber' => $user->subscribers()->count(),
        ];
    }

    public function approveMonetization(User $user): void
    {
        $user->monetization_status = Status::MONETIZATION_APPROVED;
        $user->save();
    }

    public function rejectMonetization(User $user): void
    {
        $user->monetization_status = Status::MONETIZATION_CANCEL;
        $user->save();
    }

    public function getMonetizationChart(User $user, string $startDate, string $endDate): array
    {
        $diffInDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        $dates = $groupBy === 'days'
            ? $this->getAllDates($startDate, $endDate)
            : $this->getAllMonths($startDate, $endDate);

        $totalViews = ViewLog::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COUNT(*) AS views')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $totalSubscribers = Subscriber::whereBetween('created_at', [$startDate, $endDate])
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(user_id) AS user_id')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $data = collect($dates)->map(fn($date) => [
            'created_on' => showDateTime($date, 'd-M-y'),
            'total_subscribers' => $totalSubscribers->where('created_on', $date)->first()?->user_id ?? 0,
            'total_views' => $totalViews->where('created_on', $date)->first()?->views ?? 0,
        ]);

        return [
            'targetSubscribers' => gs('minimum_subscribe'),
            'actualSubscribers' => $data->sum('total_subscribers'),
            'targetViews' => gs('minimum_views'),
            'actualViews' => $data->sum('total_views'),
            'created_on' => $data->pluck('created_on'),
        ];
    }

    public function adjustBalance(User $user, float $amount, string $type, string $remark, ?string $paymentType = null, ?string $paymentId = null): array
    {
        $details = $remark;
        if ($paymentType) {
            $details .= " | Type: " . $paymentType;
        }
        if ($paymentId) {
            $details .= " | ID: " . $paymentId;
        }

        if ($type == '1') {
            $user->balance += $amount;
            $user->save();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'post_balance' => $user->balance,
                'charge' => 0,
                'trx_type' => '+',
                'details' => 'Added Balance via Admin: ' . $details,
                'trx' => getTrx(),
                'remark' => 'admin_add_balance',
            ]);

            return ['success' => true, 'transaction' => $transaction, 'type' => 'add'];
        }

        if ($amount > $user->balance) {
            return ['success' => false, 'error' => $user->username . ' has insufficient balance.'];
        }

        $user->balance -= $amount;
        $user->save();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'post_balance' => $user->balance,
            'charge' => 0,
            'trx_type' => '-',
            'details' => 'Subtracted Balance via Admin: ' . $details,
            'trx' => getTrx(),
            'remark' => 'admin_subtract_balance',
        ]);

        return ['success' => true, 'transaction' => $transaction, 'type' => 'subtract'];
    }

    public function assignPlan(User $user, int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $user->plan_id = $plan->id;
        $user->expire_date = Carbon::now()->addDays($plan->duration);
        $user->save();
    }

    public function getUserPlanList(int $perPage = 10)
    {
        return UserPlan::orderBy('id', 'desc')->paginate($perPage);
    }

    public function createUserPlan(array $data): UserPlan
    {
        $data['is_featured_plan'] = $data['is_featured_plan'] ?? 0;
        $data['contact_access'] = $data['contact_access'] ?? 0;
        return UserPlan::create($data);
    }

    public function updateUserPlan(UserPlan $userPlan, array $data): UserPlan
    {
        $data['is_featured_plan'] = $data['is_featured_plan'] ?? 0;
        $data['contact_access'] = $data['contact_access'] ?? 0;
        $userPlan->update($data);
        return $userPlan;
    }

    public function deleteUserPlan(UserPlan $userPlan): void
    {
        $userPlan->delete();
    }

    public function sendNotificationToSingle(Request $request, int $userId)
    {
        return (new UserNotificationSender())->notificationToSingle($request, $userId);
    }

    public function sendNotificationToAll(Request $request)
    {
        return (new UserNotificationSender())->notificationToAll($request);
    }

    public function getUserNotificationLogs(int $userId, int $perPage = 15)
    {
        return NotificationLog::where('user_id', $userId)->with('user')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function searchUsers(?string $search, int $perPage = 15)
    {
        $query = User::active();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function countBySegment(string $methodName): int
    {
        return User::active()->{$methodName}()->count();
    }

    protected function createUserChannel(User $user, array $data, array $files): Channel
    {
        $channel = new Channel();
        $channel->user_id = $user->id;
        $channel->name = $data['channel_name'] ?? $user->username . "'s Channel";

        if (isset($files['channel_avatar'])) {
            $channel->avatar = \App\Helpers\ImageHelper::uploadToR2($files['channel_avatar'], 'userProfile');
        }
        if (isset($files['channel_banner'])) {
            $channel->banner = \App\Helpers\ImageHelper::uploadToR2($files['channel_banner'], 'cover');
        }

        $channel->save();
        return $channel;
    }

    protected function updateUserChannel(User $user, array $data, array $files): Channel
    {
        $channel = $user->channel ?? new Channel();
        $channel->user_id = $user->id;
        $channel->name = $data['channel_name'] ?? $channel->name;
        $channel->description = $data['channel_description'] ?? $channel->description;

        if (isset($files['channel_avatar'])) {
            $channel->avatar = \App\Helpers\ImageHelper::uploadToR2($files['channel_avatar'], 'userProfile');
        }
        if (isset($files['channel_banner'])) {
            $channel->banner = \App\Helpers\ImageHelper::uploadToR2($files['channel_banner'], 'cover');
        }

        $channel->save();
        return $channel;
    }

    protected function getAllDates(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('d-F-Y');
            $current->addDay();
        }

        return $dates;
    }

    protected function getAllMonths(string $startDate, string $endDate): array
    {
        $months = [];
        $current = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->startOfMonth();

        while ($current->lte($end)) {
            $months[] = $current->format('F-Y');
            $current->addMonth();
        }

        return $months;
    }
}
