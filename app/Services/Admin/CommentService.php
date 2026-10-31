<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\BlockedUser;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\CopyrightStrike;
use App\Models\ReelComment;
use App\Models\ReelReport;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CommentService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getAllComments(int $perPage = 20)
    {
        $search = request()->search;

        $videoQuery = Comment::select('id', 'created_at')->selectRaw("'video' as comment_type");
        $reelQuery = ReelComment::select('id', 'created_at')->selectRaw("'reel' as comment_type");

        if ($search) {
            $videoQuery->where(function ($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('username', 'LIKE', "%{$search}%")
                        ->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%"))
                    ->orWhereHas('video', fn($v) => $v->where('title', 'LIKE', "%{$search}%"));
            });

            $reelQuery->where(function ($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('username', 'LIKE', "%{$search}%")
                        ->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%"))
                    ->orWhereHas('reel', fn($v) => $v->where('title', 'LIKE', "%{$search}%"));
            });
        }

        $combined = $videoQuery->union($reelQuery)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $videoIds = collect($combined->items())->where('comment_type', 'video')->pluck('id');
        $reelIds = collect($combined->items())->where('comment_type', 'reel')->pluck('id');

        $videoComments = Comment::with(['user', 'video.user.channel'])->whereIn('id', $videoIds)->get()->keyBy('id');
        $reelComments = ReelComment::with(['user', 'reel.user.channel'])->whereIn('id', $reelIds)->get()->keyBy('id');

        $comments = $combined->getCollection()->map(function ($item) use ($videoComments, $reelComments) {
            $model = $item->comment_type === 'video' ? $videoComments->get($item->id) : $reelComments->get($item->id);
            if ($model) {
                $model->comment_type = $item->comment_type;
            }
            return $model;
        })->filter();

        $combined->setCollection($comments);

        return $combined;
    }

    public function createComment(array $data)
    {
        if ($data['comment_type'] === 'video') {
            $comment = new Comment();
            $comment->video_id = $data['video_id'];
        } else {
            $comment = new ReelComment();
            $comment->reel_id = $data['reel_id'];
        }

        $comment->content = $data['comment'];
        $comment->user_id = $data['user_id'];
        $comment->save();

        return $comment;
    }

    public function getComment(string $type, int $id)
    {
        if ($type === 'video') {
            $comment = Comment::with(['user', 'video.user.channel'])->findOrFail($id);
        } else {
            $comment = ReelComment::with(['user', 'reel.user.channel'])->findOrFail($id);
        }

        $comment->comment_type = $type;
        return $comment;
    }

    public function updateComment(int $id, array $data): mixed
    {
        $oldType = $data['old_type'] ?? 'video';

        if ($oldType === 'video') {
            $comment = Comment::findOrFail($id);
        } else {
            $comment = ReelComment::findOrFail($id);
        }

        if ($oldType !== $data['comment_type']) {
            $comment->delete();

            if ($data['comment_type'] === 'video') {
                $comment = new Comment();
                $comment->video_id = $data['video_id'];
            } else {
                $comment = new ReelComment();
                $comment->reel_id = $data['reel_id'];
            }
        } else {
            if ($data['comment_type'] === 'video') {
                $comment->video_id = $data['video_id'];
            } else {
                $comment->reel_id = $data['reel_id'];
            }
        }

        $comment->content = $data['comment'];
        $comment->user_id = $data['user_id'];
        $comment->save();

        return $comment;
    }

    public function deleteComment(string $type, int $id): void
    {
        if ($type === 'video') {
            Comment::findOrFail($id)->delete();
        } else {
            ReelComment::findOrFail($id)->delete();
        }
    }

    public function getCommentReports(int $perPage = 15)
    {
        $reports = CommentReport::with(['comment.user', 'comment.video', 'reporter'])->latest()->paginate($perPage, ['*'], 'reports_page');
        $blockedUsers = BlockedUser::with(['blocker', 'blocked'])->latest()->paginate($perPage, ['*'], 'blocked_page');

        return compact('reports', 'blockedUsers');
    }

    public function dismissReport(int $reportId): void
    {
        CommentReport::findOrFail($reportId)->delete();
    }

    public function deleteReportedComment(int $reportId): void
    {
        $report = CommentReport::findOrFail($reportId);
        if ($report->comment) {
            $report->comment->delete();
        }
        $report->status = 'reviewed';
        $report->save();
    }

    public function getReportedVideos(int $perPage = 20)
    {
        return Report::with(['user', 'video'])
            ->whereNotNull('video_id')
            ->latest()
            ->paginate($perPage);
    }

    public function getReportedUsers(int $perPage = 20)
    {
        return Report::with(['user', 'reportedUser'])
            ->whereNotNull('reported_user_id')
            ->latest()
            ->paginate($perPage);
    }

    public function getAppeals(int $perPage = 20)
    {
        return Report::with('user')
            ->where('status', 'appeal')
            ->latest()
            ->paginate($perPage);
    }

    public function handleReport(int $reportId, string $status, ?string $feedback = null): void
    {
        $report = Report::findOrFail($reportId);

        $updateData = ['status' => $status];
        if ($feedback) {
            $updateData['admin_feedback'] = $feedback;
        }
        $report->update($updateData);

        if ($status === 'resolved') {
            $targetUserId = $report->reported_user_id;
            if (!$targetUserId && $report->video_id) {
                $targetUserId = $report->video->user_id;
            }

            if ($targetUserId) {
                CopyrightStrike::create([
                    'user_id' => $targetUserId,
                    'video_id' => $report->video_id,
                    'reason' => 'Moderation Decision: ' . $report->reason . ($feedback ? ' - ' . $feedback : ''),
                    'status' => 'active',
                    'is_read' => false,
                ]);

                if ($report->video) {
                    $report->video->update([
                        'status' => Status::VIDEO_STRUCK,
                        'moderation_status' => 'struck',
                    ]);
                }

                $this->checkAutoBan($targetUserId);
            }
        } elseif ($status === 'age_restricted') {
            if ($report->video) {
                $report->video->update(['is_age_restricted' => 1]);
            }
            $report->update(['status' => 'resolved']);
        } elseif ($status === 'warning') {
            $report->update(['status' => 'resolved']);
        }
    }

    public function getStruckUsers(int $perPage = 20)
    {
        return CopyrightStrike::with(['user', 'video'])
            ->latest()
            ->paginate($perPage);
    }

    public function removeStrike(int $strikeId): void
    {
        $strike = CopyrightStrike::findOrFail($strikeId);
        $strike->update(['status' => 'removed']);

        $user = $strike->user;
        if ($user) {
            $activeStrikes = CopyrightStrike::where('user_id', $user->id)
                ->where('status', 'active')
                ->count();

            if ($activeStrikes < 3 && $user->status == Status::USER_BAN) {
                $user->update([
                    'status' => Status::USER_ACTIVE,
                    'ban_reason' => null,
                ]);
            }
        }

        if ($strike->video) {
            $strike->video->update([
                'status' => Status::PUBLISHED,
                'moderation_status' => 'approved',
            ]);
        }
    }

    public function getReportedReels(int $perPage = 20)
    {
        return ReelReport::with(['user', 'reel'])
            ->latest()
            ->paginate($perPage);
    }

    public function handleReelReport(int $reportId, string $status, ?string $feedback = null): void
    {
        $report = ReelReport::findOrFail($reportId);

        $report->update([
            'status' => in_array($status, ['resolved', 'age_restricted', 'warning']) ? 1 : ($status == 'rejected' ? 2 : 0),
            'admin_feedback' => $feedback,
        ]);

        if ($status === 'resolved') {
            $reel = $report->reel;
            if ($reel) {
                CopyrightStrike::create([
                    'user_id' => $reel->user_id,
                    'reel_id' => $reel->id,
                    'reason' => 'Moderation Decision (Reel): ' . $report->reason . ($feedback ? ' - ' . $feedback : ''),
                    'status' => 'active',
                    'is_read' => false,
                ]);

                $reel->update([
                    'status' => Status::VIDEO_STRUCK,
                    'moderation_status' => 'struck',
                ]);

                $this->checkAutoBan($reel->user_id);
            }
        } elseif ($status === 'age_restricted') {
            if ($report->reel) {
                $report->reel->update(['is_age_restricted' => 1]);
            }
            $report->update(['status' => 1]);
        } elseif ($status === 'warning') {
            $report->update(['status' => 1]);
        }
    }

    protected function checkAutoBan(int $userId): void
    {
        $activeStrikes = CopyrightStrike::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        if ($activeStrikes >= 3) {
            User::where('id', $userId)->update([
                'status' => 'banned',
                'ban_reason' => 'Your channel has been closed due to multiple copyright strikes. Please contact support for further information.',
            ]);
        }
    }
}
