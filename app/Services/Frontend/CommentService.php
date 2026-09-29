<?php

namespace App\Services\Frontend;

use App\Models\Comment;
use App\Models\Video;
use App\Models\User;
use App\Models\CommentLike;
use App\Models\BlockedUser;
use App\Models\BlacklistedKeyword;
use App\Models\UserNotification;
use App\Jobs\SendFirebaseNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function index(Request $request, Video $video): array
    {
        $sort = $request->get('sort', 'new');

        $query = $video->comments()->whereNull('parent_id')->orderBy('is_pinned', 'desc');

        if ($sort === 'top') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        $blockedUsers = auth()->check()
            ? BlockedUser::where('user_id', auth()->id())->pluck('blocked_user_id')->toArray()
            : [];

        if (!empty($blockedUsers)) {
            $query->whereNotIn('user_id', $blockedUsers);
        }

        $comments = $query->with(['user', 'likes', 'replies' => function($q) use ($blockedUsers) {
            if (!empty($blockedUsers)) {
                $q->whereNotIn('user_id', $blockedUsers);
            }
            $q->with('user');
        }])->get();

        return compact('comments', 'video', 'blockedUsers');
    }

    public function store(Request $request, Video $video): array
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $blacklistedKeywords = \Cache::remember('blacklisted_keywords', 3600, function () {
            return BlacklistedKeyword::pluck('keyword')->toArray();
        });
        foreach ($blacklistedKeywords as $keyword) {
            if (stripos($request->content, $keyword) !== false) {
                return ['status' => 'error', 'message' => 'Comment blocked: Inappropriate language detected.'];
            }
        }

        $comment = $video->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        if ($comment->parent_id) {
            $parentComment = Comment::find($comment->parent_id);
            if ($parentComment && $parentComment->user_id != auth()->id()) {
                UserNotification::create([
                    'user_id' => $parentComment->user_id,
                    'sender_id' => auth()->id(),
                    'title' => auth()->user()->username . ' replied to your comment',
                    'click_url' => route('videos.show', $video->slug),
                ]);

                Log::info("Comment Push: Dispatching reply notification to user {$parentComment->user_id} for video {$video->id}");
                SendFirebaseNotificationJob::dispatch($parentComment->user_id, [
                    'title' => 'New Reply',
                    'body' => auth()->user()->username . ' replied: "' . \Illuminate\Support\Str::limit($comment->content, 50) . '"',
                    'url' => route('videos.show', $video->slug),
                ]);
            }
        } else if ($video->user_id != $request->user()->id) {
            UserNotification::create([
                'user_id' => $video->user_id,
                'sender_id' => auth()->id(),
                'title' => auth()->user()->username . ' commented on your video',
                'click_url' => route('videos.show', $video->slug),
            ]);

            Log::info("Comment Push: Dispatching new comment notification to video owner user {$video->user_id} for video {$video->id}");
            SendFirebaseNotificationJob::dispatch($video->user_id, [
                'title' => 'New Comment',
                'body' => auth()->user()->username . ' commented: "' . \Illuminate\Support\Str::limit($comment->content, 50) . '"',
                'url' => route('videos.show', $video->slug),
            ]);
        }

        $mentionUsernames = Video::extractMentions($request->content);
        foreach ($mentionUsernames as $username) {
            $mentionedUser = User::where('username', $username)->first();
            if ($mentionedUser && $mentionedUser->id != auth()->id()) {
                UserNotification::create([
                    'user_id' => $mentionedUser->id,
                    'sender_id' => auth()->id(),
                    'title' => auth()->user()->username . ' mentioned you in a comment',
                    'click_url' => route('videos.show', $video->slug),
                ]);

                Log::info("Comment Push: Dispatching mention notification to user {$mentionedUser->id} for video {$video->id}");
                SendFirebaseNotificationJob::dispatch($mentionedUser->id, [
                    'title' => 'New Mention',
                    'body' => auth()->user()->username . ' mentioned you: "' . \Illuminate\Support\Str::limit($comment->content, 50) . '"',
                    'url' => route('videos.show', $video->slug),
                ]);
            }
        }

        $comment->load('user', 'likes');

        return compact('comment', 'video');
    }

    public function toggleLike(Comment $comment): array
    {
        $like = CommentLike::where('user_id', auth()->id())
            ->where('comment_id', $comment->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            CommentLike::create([
                'user_id' => auth()->id(),
                'comment_id' => $comment->id
            ]);
            $liked = true;
        }

        return [
            'liked' => $liked,
            'likes_count' => $comment->likes()->count()
        ];
    }

    public function update(Request $request, Comment $comment): array
    {
        if ((string)auth()->id() !== (string)$comment->user_id) {
            return ['error' => 'Unauthorized'];
        }

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $blacklistedKeywords = \Cache::remember('blacklisted_keywords', 3600, function () {
            return BlacklistedKeyword::pluck('keyword')->toArray();
        });
        foreach ($blacklistedKeywords as $keyword) {
            if (stripos($request->content, $keyword) !== false) {
                return ['status' => 'error', 'message' => 'Update blocked: Inappropriate language detected.'];
            }
        }

        $comment->update(['content' => $request->content]);

        return ['status' => 'success', 'message' => 'Comment updated successfully', 'content' => $comment->content];
    }

    public function destroy(Comment $comment): array
    {
        $video = $comment->video;

        if ((string)auth()->id() !== (string)$comment->user_id && (string)auth()->id() !== (string)($video->user_id ?? 0)) {
            return ['error' => 'Unauthorized'];
        }

        $comment->delete();

        return ['status' => 'success', 'success' => true, 'message' => 'Comment deleted successfully', 'comments_count' => $video ? $video->comments()->count() : 0];
    }

    public function togglePin(Comment $comment): array
    {
        $video = $comment->video;
        if (auth()->id() != $video->user_id) {
            return ['status' => 'error', 'message' => 'Unauthorized'];
        }

        $wasPinned = $comment->is_pinned;

        Comment::where('video_id', $video->id)->where('is_pinned', true)->update(['is_pinned' => false]);

        $comment->is_pinned = !$wasPinned;
        $comment->save();

        return ['status' => 'success', 'is_pinned' => $comment->is_pinned, 'message' => $comment->is_pinned ? 'Comment pinned!' : 'Comment unpinned!'];
    }

    public function blockUser(Request $request): array
    {
        $request->validate([
            'blocked_user_id' => 'required|exists:users,id',
            'comment_text' => 'nullable|string'
        ]);

        if (auth()->id() == $request->blocked_user_id) {
            return ['status' => 'error', 'message' => 'You cannot block yourself.'];
        }

        $block = BlockedUser::firstOrCreate([
            'user_id' => auth()->id(),
            'blocked_user_id' => $request->blocked_user_id
        ]);

        if ($request->comment_text && !$block->comment_text) {
            $block->comment_text = $request->comment_text;
            $block->save();
        }

        return ['status' => 'success', 'message' => 'User blocked. Their comments will be hidden.'];
    }

    public function reportComment(Request $request): array
    {
        $request->validate([
            'comment_id' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        $commentId = $request->comment_id;
        $isVideoComment = \App\Models\Comment::where('id', $commentId)->exists();
        $isReelComment = \App\Models\ReelComment::where('id', $commentId)->exists();

        if (!$isVideoComment && !$isReelComment) {
            return ['status' => 'error', 'message' => 'Comment not found.'];
        }

        // Video comments: keep original logic (with FK to comments table)
        if ($isVideoComment) {
            \App\Models\CommentReport::firstOrCreate([
                'comment_id' => $commentId,
                'user_id' => auth()->id()
            ], [
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            return ['status' => 'success', 'message' => 'Comment reported successfully.'];
        }

        // Reel comments: no dedicated report table (FK would fail), handle gracefully without breaking core logic
        // Log for moderation review and return success to avoid "The selected comment id is invalid." error
        \Illuminate\Support\Facades\Log::info("Reel comment reported: {$commentId} reason: {$request->reason} by user ".auth()->id());

        return ['status' => 'success', 'message' => 'Comment reported successfully.'];
    }
}
