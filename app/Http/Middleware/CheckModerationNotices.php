<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReelReport;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModerationNotices
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            // 1. Check for Video Reports
            $videoReport = Report::where('reported_user_id', $user->id)
                ->where('status', 1) // Resolved/Accepted
                ->where('is_notified', false)
                ->with('video')
                ->first();

            if ($videoReport) {
                session()->flash('moderation_notice', [
                    'id' => $videoReport->id,
                    'type' => 'Video',
                    'content_title' => $videoReport->video ? $videoReport->video->title : 'Deleted Video',
                    'reason' => $videoReport->reason,
                    'feedback' => $videoReport->admin_feedback,
                    'action_type' => $videoReport->video && $videoReport->video->is_age_restricted ? 'Age Restricted' : 'Community Warning'
                ]);
            } else {
                // 2. Check for Reel Reports
                // We need to find reels owned by this user that have resolved reports
                $reelReport = ReelReport::whereHas('reel', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->where('status', 1)
                ->where('is_notified', false)
                ->with('reel')
                ->first();

                if ($reelReport) {
                    session()->flash('moderation_notice', [
                        'id' => $reelReport->id,
                        'type' => 'Reel',
                        'content_title' => $reelReport->reel ? $reelReport->reel->title : 'Deleted Reel',
                        'reason' => $reelReport->reason,
                        'feedback' => $reelReport->admin_feedback,
                        'action_type' => $reelReport->reel && $reelReport->reel->is_age_restricted ? 'Age Restricted' : 'Community Warning'
                    ]);
                }
            }
        }

        return $next($request);
    }
}
