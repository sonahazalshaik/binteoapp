<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\BlacklistedKeyword;
use App\Models\BlockedIp;
use App\Models\CopyrightStrike;
use App\Models\NotInterestedVideo;
use App\Models\NotificationLog;
use App\Models\PurchasedPlan;
use App\Models\PurchasedVideo;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\ViewLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SecurityService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getSecurityDashboard(): array
    {
        return [
            'logs' => ActivityLog::with('user')->latest()->paginate(20),
            'announcements' => Announcement::latest()->get(),
            'bannedUsers' => User::where('status', 'banned')->count(),
            'blockedIps' => BlockedIp::latest()->get(),
        ];
    }

    public function blockIp(string $ipAddress, ?string $reason = null): BlockedIp
    {
        return BlockedIp::create([
            'ip_address' => $ipAddress,
            'reason' => $reason,
        ]);
    }

    public function unblockIp(int $id): void
    {
        BlockedIp::findOrFail($id)->delete();
    }

    public function createAnnouncement(string $title, string $message): Announcement
    {
        return Announcement::create([
            'title' => $title,
            'message' => $message,
            'is_active' => true,
        ]);
    }

    public function toggleAnnouncement(int $id): void
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);
    }

    public function deleteAnnouncement(int $id): void
    {
        Announcement::findOrFail($id)->delete();
    }

    public function getStrikes(int $perPage = 20)
    {
        return CopyrightStrike::with(['user', 'video'])->latest()->paginate($perPage);
    }

    public function issueStrike(int $videoId, string $reason): CopyrightStrike
    {
        $video = \App\Models\Video::findOrFail($videoId);

        $strike = CopyrightStrike::create([
            'user_id' => $video->user_id,
            'video_id' => $video->id,
            'reason' => $reason,
            'status' => 'active',
        ]);

        $video->update([
            'status' => Status::VIDEO_STRUCK,
            'moderation_status' => 'struck',
        ]);

        $activeStrikes = CopyrightStrike::where('user_id', $video->user_id)
            ->where('status', 'active')
            ->count();

        if ($activeStrikes >= 3) {
            User::where('id', $video->user_id)->update(['status' => Status::USER_BAN]);
        }

        return $strike;
    }

    public function resolveStrike(int $id): void
    {
        CopyrightStrike::findOrFail($id)->update(['status' => 'resolved']);
    }

    public function getBlacklistedKeywords(int $perPage = 50)
    {
        return BlacklistedKeyword::latest()->paginate($perPage);
    }

    public function addBlacklistedKeyword(string $keyword): BlacklistedKeyword
    {
        return BlacklistedKeyword::create([
            'keyword' => strtolower($keyword),
        ]);
    }

    public function removeBlacklistedKeyword(int $id): void
    {
        BlacklistedKeyword::findOrFail($id)->delete();
    }

    public function getNotInterestedVideos(int $perPage = 15)
    {
        return NotInterestedVideo::with(['user', 'video'])->latest()->paginate($perPage);
    }

    public function deleteNotInterestedVideo(int $id): void
    {
        NotInterestedVideo::findOrFail($id)->delete();
    }

    public function getReports(int $perPage = 10)
    {
        return Report::with(['user', 'video', 'comment'])->latest()->paginate($perPage);
    }

    public function resolveReport(Report $report): void
    {
        $report->status = 'resolved';
        $report->save();
    }

    public function strikeFromReport(Report $report): void
    {
        if (!$report->video_id) {
            throw new \RuntimeException('This report is not associated with a video.');
        }

        $video = $report->video;

        CopyrightStrike::create([
            'user_id' => $video->user_id,
            'video_id' => $video->id,
            'reason' => 'Community Report: ' . $report->reason,
            'status' => 'active',
        ]);

        $video->update([
            'status' => 9,
            'moderation_status' => 'struck',
        ]);

        $report->status = 'resolved';
        $report->save();

        $activeStrikes = CopyrightStrike::where('user_id', $video->user_id)
            ->where('status', 'active')
            ->count();

        if ($activeStrikes >= 3) {
            User::where('id', $video->user_id)->update(['status' => 'banned']);
        }
    }

    public function deleteReport(Report $report): void
    {
        $report->delete();
    }

    public function destroyReportedContent(Report $report): void
    {
        if ($report->video_id) {
            $report->video->delete();
        } elseif ($report->comment_id) {
            $report->comment->delete();
        }

        $report->status = 'resolved';
        $report->save();
    }

    public function getTransactions(?int $userId = null, int $perPage = 15)
    {
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::searchable(['trx', 'user:username'])
            ->filter(['trx_type', 'remark', 'user_id'])
            ->dateFilter()
            ->when(request()->min_amount, fn($q, $min) => $q->where('amount', '>=', $min))
            ->when(request()->max_amount, fn($q, $max) => $q->where('amount', '<=', $max))
            ->orderBy('id', 'desc')
            ->with('user');

        if ($userId) {
            $transactions = $transactions->where('user_id', $userId);
        }

        return [
            'transactions' => $transactions->paginate($perPage),
            'remarks' => $remarks,
        ];
    }

    public function getPurchasedVideos(int $perPage = 15)
    {
        return PurchasedVideo::orderBy('id', 'desc')
            ->with('user', 'owner', 'video')
            ->searchable(['user:username', 'owner:username', 'video:title'])
            ->dateFilter()
            ->paginate($perPage);
    }

    public function getPurchasedPlans(int $perPage = 15)
    {
        return PurchasedPlan::orderBy('id', 'desc')
            ->with(['user', 'owner', 'plan'])
            ->searchable(['user:username', 'owner:username', 'plan:name'])
            ->dateFilter()
            ->paginate($perPage);
    }

    public function getLoginHistory(?string $ip = null, int $perPage = 15)
    {
        $query = UserLogin::orderBy('id', 'desc');

        if ($ip) {
            $query->where('user_ip', $ip);
        }

        return $query->searchable(['user:username'])
            ->dateFilter()
            ->with('user')
            ->paginate($perPage);
    }

    public function getNotificationHistory(int $perPage = 15)
    {
        return NotificationLog::orderBy('id', 'desc')
            ->searchable(['user:username'])
            ->dateFilter()
            ->with('user')
            ->paginate($perPage);
    }

    public function getEmailDetails(int $id): NotificationLog
    {
        return NotificationLog::findOrFail($id);
    }

    public function getWatchHistory(?string $role = null, int $perPage = 15)
    {
        $query = ViewLog::orderBy('id', 'desc')->with(['user', 'video', 'reel']);

        if ($role === 'creator') {
            $query->whereHas('user', fn($q) => $q->where('creator_status', 1));
        } elseif ($role === 'regular') {
            $query->whereHas('user', fn($q) => $q->where('creator_status', 0));
        }

        return $query->searchable(['user:username', 'video:title', 'reel:title'])
            ->dateFilter()
            ->paginate($perPage);
    }
}
