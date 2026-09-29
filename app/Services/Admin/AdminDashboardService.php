<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\FirebaseToken;
use App\Models\Reel;
use App\Models\Storage;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Video;
use App\Models\VideoEarning;
use App\Models\WatchHistory;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminDashboardService
{
    public function getDashboardWidgets(): array
    {
        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::active()->count();
        $widget['email_unverified_users'] = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();
        $widget['kyc_pending_users'] = User::kycPending()->count();
        $widget['online_users'] = User::where('last_seen', '>=', now()->subMinutes(5))->count();

        $widget['total_videos'] = Video::count();
        $widget['uploads_today'] = Video::whereDate('created_at', now())->count();
        $widget['free_videos'] = Video::free()->count();
        $widget['stock_videos'] = Video::stock()->count();
        $widget['regular_videos'] = Video::regular()->count();
        $widget['total_reels'] = Video::shorts()->count() + Reel::count();
        $widget['public_videos'] = Video::public()->count();
        $widget['private_videos'] = Video::private()->count();
        $widget['draft_videos'] = Video::draft()->count();

        $totalWatchSeconds = Video::sum('total_watch_time');
        if ($totalWatchSeconds == 0) {
            $totalWatchSeconds = WatchHistory::sum('progress_seconds');
        }
        $widget['total_watch_time'] = $totalWatchSeconds;
        $widget['estimated_revenue'] = VideoEarning::sum('estimated_revenue');

        return $widget;
    }

    public function getDashboardCharts(): array
    {
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(fn($item) => collect($item)->count());
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(fn($item) => collect($item)->count());
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(fn($item) => collect($item)->count())->sort()->reverse()->take(5);

        return $chart;
    }

    public function getDepositStats(): array
    {
        return [
            'total_deposit_amount' => Deposit::successful()->sum('amount'),
            'total_deposit_pending' => Deposit::pending()->count(),
            'total_deposit_rejected' => Deposit::rejected()->count(),
            'total_deposit_charge' => Deposit::successful()->sum('charge'),
        ];
    }

    public function getWithdrawalStats(): array
    {
        return [
            'total_withdraw_amount' => Withdrawal::approved()->sum('amount'),
            'total_withdraw_pending' => Withdrawal::pending()->count(),
            'total_withdraw_rejected' => Withdrawal::rejected()->count(),
            'total_withdraw_charge' => Withdrawal::approved()->sum('charge'),
        ];
    }

    public function getRecentUploads(int $limit = 5)
    {
        return Video::with('user')->latest()->take($limit)->get();
    }

    public function getDepositWithdrawReport(Request $request): array
    {
        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        $dates = $groupBy == 'days'
            ? $this->getAllDates($request->start_date, $request->end_date)
            : $this->getAllMonths($request->start_date, $request->end_date);

        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $withdrawals = Withdrawal::approved()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $data = collect($dates)->map(fn($date) => [
            'created_on' => showDateTime($date, 'd-M-y'),
            'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0),
            'withdrawals' => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0),
        ]);

        return [
            'created_on' => $data->pluck('created_on'),
            'data' => [
                ['name' => 'Payments', 'data' => $data->pluck('deposits')],
                ['name' => 'Withdrawn', 'data' => $data->pluck('withdrawals')],
            ],
        ];
    }

    public function getTransactionReport(Request $request): array
    {
        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        $dates = $groupBy == 'days'
            ? $this->getAllDates($request->start_date, $request->end_date)
            : $this->getAllMonths($request->start_date, $request->end_date);

        $plusTransactions = Transaction::where('trx_type', '+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $minusTransactions = Transaction::where('trx_type', '-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $data = collect($dates)->map(fn($date) => [
            'created_on' => showDateTime($date, 'd-M-y'),
            'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
            'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0),
        ]);

        return [
            'created_on' => $data->pluck('created_on'),
            'data' => [
                ['name' => 'Plus Transactions', 'data' => $data->pluck('credits')],
                ['name' => 'Minus Transactions', 'data' => $data->pluck('debits')],
            ],
        ];
    }

    public function getAdminProfile()
    {
        return auth()->guard('admin')->user();
    }

    public function updateAdminProfile(Request $request): void
    {
        $user = auth()->guard('admin')->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
    }

    public function updateAdminPassword(Request $request): bool
    {
        $user = auth()->guard('admin')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return false;
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return true;
    }

    public function getNotifications()
    {
        return [
            'notifications' => AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate()),
            'hasUnread' => AdminNotification::where('is_read', Status::NO)->exists(),
            'hasNotification' => AdminNotification::exists(),
        ];
    }

    public function markNotificationRead($id): ?string
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();

        return $notification->click_url != '#' ? $notification->click_url : null;
    }

    public function markAllNotificationsRead(): void
    {
        AdminNotification::where('is_read', Status::NO)->update(['is_read' => Status::YES]);
    }

    public function deleteAllNotifications(): void
    {
        AdminNotification::truncate();
    }

    public function deleteNotification($id): void
    {
        AdminNotification::where('id', $id)->delete();
    }

    public function checkStorageSpace(): array
    {
        if (!gs('is_storage')) {
            return ['status' => false];
        }

        return ['status' => Storage::active()->where('available_space', '<=', 0)->exists()];
    }

    public function updateFirebaseToken(Request $request): void
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string|in:mobile,tablet,desktop',
        ]);

        $token = FirebaseToken::updateOrCreate(
            ['token' => $request->token],
            [
                'admin_id' => auth()->guard('admin')->id(),
                'device_type' => $request->device_type,
            ]
        );

        Log::info('[FCM-ADMIN] Token saved in DB', [
            'id' => $token->id,
            'admin_id' => $token->admin_id,
            'device_type' => $token->device_type,
            'created' => $token->wasRecentlyCreated ? 'new' : 'updated'
        ]);
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
