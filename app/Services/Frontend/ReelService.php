<?php

namespace App\Services\Frontend;

use App\Constants\Status;
use App\Jobs\CompressReel;
use App\Jobs\SendFirebaseNotificationJob;
use App\Lib\ClientInfo;
use App\Models\BlockedUser;
use App\Models\Category;
use App\Models\Reel;
use App\Models\ReelComment;
use App\Models\ReelCommentLike;
use App\Models\ReelHashtag;
use App\Models\ReelLike;
use App\Models\ReelMention;
use App\Models\ReelMusic;
use App\Models\ReelShare;
use App\Models\ReelTag;
use App\Models\ReelView;
use App\Models\BlacklistedKeyword;
use App\Models\NotInterestedReel;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\ViewLog;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReelService
{
    public function checkVisibility(?Reel $reel): void
    {
        if (!$reel) {
            abort(404, 'Reel not found.');
        }

        if ((string)$reel->visibility === (string)Status::PRIVATE) {
            if (!auth()->check() || (string)auth()->id() !== (string)$reel->user_id) {
                abort(403, 'Unauthorized access.');
            }
        }
    }

    public function findReel($reel): Reel
    {
        if ($reel instanceof Reel) {
            return $reel;
        }

        return Reel::where('id', $reel)->orWhere('slug', $reel)->firstOrFail();
    }

    public function index(Request $request): array
    {
        $reelSlug = $request->reel;
        $search = $request->q;
        $hashtag = $request->hashtag;

        if (!$hashtag && $search && str_starts_with($search, '#')) {
            $hashtag = ltrim($search, '#');
        }

        if ($reelSlug) {
            $checkReel = Reel::where('slug', $reelSlug)->first();
            if ($checkReel) {
                $this->checkVisibility($checkReel);
            }
        }

        $query = Reel::query();
        if (auth()->check()) {
            $query->where(function ($q) {
                $q->forUser()
                    ->orWhere(function ($sq) {
                        $sq->where('user_id', auth()->id())
                            ->where('visibility', '!=', Status::PRIVATE)
                            ->activeCreator()
                            ->published();
                    });
            });
            // Global hide: ensure not-interested reels are always excluded, even own-reel OR branch
            $query->whereDoesntHave('notInterestedBy', function ($q) {
                $q->where('user_id', auth()->id());
            });
        } else {
            $query->forUser();
        }

        $query->with(['user.channel', 'music', 'hashtags', 'likes', 'channel', 'category'])
            ->withCount(['comments', 'likes', 'shares']);

        if ($hashtag) {
            $tag = ltrim($hashtag, '#');
            $query->whereHas('hashtags', function ($q) use ($tag) {
                $q->where('hashtag', $tag);
            });
        } elseif ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        if ($reelSlug) {
            $specificReel = Reel::with(['user.channel', 'music', 'hashtags', 'likes', 'channel', 'category'])
                                ->withCount(['comments', 'likes', 'shares'])
                                ->where('slug', $reelSlug)
                                ->first();

            $canView = false;
            if ($specificReel) {
                $isReady = $specificReel->status == Status::PUBLISHED 
                           && $specificReel->bunny_status == 'ready';
                
                if ($isReady) {
                    if (in_array(strtolower($specificReel->visibility), ['public', '0'])) {
                        $canView = true;
                    } elseif (auth()->check() && $specificReel->user_id == auth()->id()) {
                        $canView = true;
                    }
                }
                // Prevent forced inclusion of reels the user marked as not interested
                if ($canView && auth()->check() && NotInterestedReel::where('user_id', auth()->id())->where('reel_id', $specificReel->id)->exists()) {
                    $canView = false;
                }
            }

            if ($canView && $specificReel) {
                $otherReels = (clone $query)->where('id', '!=', $specificReel->id)
                    ->inRandomOrder()
                    ->limit(49)
                    ->get();
                $reels = collect([$specificReel])->merge($otherReels);
            } else {
                $reels = $query->inRandomOrder()->limit(50)->get();
            }
        } else {
            $reels = $query->inRandomOrder()->limit(50)->get();
        }

        $moreQuery = Reel::query();
        if (auth()->check()) {
            $moreQuery->where(function ($q) {
                $q->forUser()
                    ->orWhere(function ($sq) {
                        $sq->where('user_id', auth()->id())
                            ->where('visibility', '!=', Status::PRIVATE)
                            ->activeCreator()
                            ->published();
                    });
            });
            $moreQuery->whereDoesntHave('notInterestedBy', function ($q) {
                $q->where('user_id', auth()->id());
            });
        } else {
            $moreQuery->forUser();
        }

        if ($hashtag) {
            $tag = ltrim($hashtag, '#');
            $moreQuery->whereHas('hashtags', function ($q) use ($tag) {
                $q->where('hashtag', $tag);
            });
        } elseif ($search) {
            $moreQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        $moreReels = $moreQuery->with(['user', 'user.channel'])
            ->withCount(['comments', 'likes', 'shares'])
            ->where('status', Status::PUBLISHED)
            ->where('bunny_status', 'ready')
            ->inRandomOrder()
            ->limit(15)
            ->get();

        $playlistData = [];
        $watchLaterReelIds = [];
        if (auth()->check()) {
            $reelIds = $reels->pluck('id')->toArray();
            if (!empty($reelIds)) {
                $userPlaylists = auth()->user()->playlists()->with(['reels' => function ($q) use ($reelIds) {
                    $q->whereIn('reels.id', $reelIds);
                }])->get();

                foreach ($userPlaylists as $playlist) {
                    foreach ($playlist->reels as $reel) {
                        $playlistData[$reel->id][] = $playlist->id;
                        if ($playlist->name === 'Watch Later') {
                            $watchLaterReelIds[] = $reel->id;
                        }
                    }
                }
            }
        }

        return compact('reels', 'moreReels', 'playlistData', 'watchLaterReelIds');
    }

    public function show($reel)
    {
        if ($reel instanceof Reel) {
            $reelModel = $reel;
        } else {
            $reelModel = Reel::where('id', $reel)->orWhere('slug', $reel)->first();
        }

        if (!$reelModel) {
            return ['redirect' => route('reels.index'), 'notify' => ['error', 'This reel is no longer available.']];
        }

        $this->checkVisibility($reelModel);

        return ['redirect' => route('reels.index', ['reel' => $reelModel->slug])];
    }

    public function create(): array
    {
        if (!auth()->user()->channel) {
            return ['redirect' => route('channels.create')];
        }

        $categories = Category::active()->get();
        $musicTracks = ReelMusic::active()->latest()->take(50)->get();

        $musicSlug = request()->music;
        if ($musicSlug) {
            $selectedMusic = ReelMusic::active()->where('slug', $musicSlug)->first();
            if ($selectedMusic && !$musicTracks->contains('id', $selectedMusic->id)) {
                $musicTracks->prepend($selectedMusic);
            }
        }

        $duetSlug = request()->duet;
        $duetReel = null;
        if ($duetSlug) {
            $duetReel = Reel::query()->with('user')->where('slug', $duetSlug)->first();
            if ($duetReel) {
                $this->checkVisibility($duetReel);
            }
        }

        return compact('categories', 'musicTracks', 'duetReel');
    }

    public function searchMusic(Request $request): array
    {
        $query = $request->q;
        $tracks = ReelMusic::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('artist', 'LIKE', "%$query%");
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($m) {
                $file = $m->file_path ?? $m->audio_path;
                $reel = Reel::where('video_path', $file)
                    ->orWhere('compressed_video_path', $file)
                    ->first();
                if ($reel) {
                    $url = $reel->getVideoUrl();
                    if ($reel->isBunnyReel()) {
                        $url = str_replace('play_1080p.mp4', 'play_360p.mp4', $url);
                    }
                    $thumbnail = $reel->user->image
                        ? getImage(getFilePath('userProfile') . '/' . $reel->user->image)
                        : null;
                } else {
                    $filePath = getFilePath('reelMusic') . '/' . $file;
                    if (!file_exists(public_path($filePath))) {
                        $fallbackPath = getFilePath('reel') . '/' . $file;
                        if (file_exists(public_path($fallbackPath))) {
                            $filePath = $fallbackPath;
                        }
                    }
                    $url = asset($filePath);
                    $thumbnail = $m->cover_image ? asset(getFilePath('reelMusic') . '/' . $m->cover_image) : null;
                }

                return [
                    'id' => $m->id,
                    'title' => $m->title,
                    'artist' => $m->artist ?? 'Unknown',
                    'thumbnail' => $thumbnail,
                    'url' => $url,
                ];
            });

        return compact('tracks');
    }

    public function store(Request $request)
    {
        Log::info('ReelService@store hit! User: ' . auth()->id());

        if (!auth()->user()->channel) {
            return ['redirect' => route('channels.create')];
        }

        $reel = new Reel;
        $reel->user_id = auth()->id();
        $reel->title = $request->title ?? 'Draft Reel ' . date('Y-m-d H:i');
        $reel->slug = Str::slug($reel->title) . '-' . Str::random(6);
        $reel->description = $request->description;
        $reel->category_id = $request->category_id;
        $reel->visibility = $request->visibility;
        $reel->status = $request->boolean('is_draft') ? Status::DRAFT : Status::PUBLISHED;
        $reel->location = $request->location;
        $reel->is_age_restricted = $request->boolean('is_age_restricted');
        $reel->allow_comments = $request->boolean('allow_comments', true);
        $reel->allow_duet = $request->boolean('allow_duet', true);
        $reel->allow_stitch = $request->boolean('allow_stitch', true);

        $parentId = null;
        if ($request->parent_id) {
            $parentReel = Reel::where('id', $request->parent_id)->orWhere('slug', $request->parent_id)->first();
            $parentId = $parentReel ? $parentReel->id : null;
        }
        $reel->parent_id = $parentId;
        $reel->is_duet = $parentId ? 1 : 0;

        $originalReelId = null;
        if ($request->original_reel_id) {
            $originalReel = Reel::where('id', $request->original_reel_id)->orWhere('slug', $request->original_reel_id)->first();
            $originalReelId = $originalReel ? $originalReel->id : null;
        }
        $reel->original_reel_id = $originalReelId;

        $reel->is_compressed = false;
        $reel->compression_status = 0;
        $reel->music_source = $request->music_source ?? 'none';
        $reel->mic_volume = $request->mic_volume ?? 1.0;
        $reel->music_volume = $request->music_volume ?? 1.0;

        if (in_array($reel->music_source, ['global', 'original'])) {
            $reel->global_music_url = $request->global_music_url;
            $reel->global_music_title = $request->global_music_title;
            $reel->global_music_artist = $request->global_music_artist;
            // Strip query string from thumbnail to prevent SQL 1406 Data too long error for signed URLs
            $thumbnail = $request->global_music_thumbnail;
            $reel->global_music_thumbnail = $thumbnail ? strtok($thumbnail, '?') : null;
            $reel->audio_name = $request->global_music_title;
        }

        if ($request->hasFile('video')) {
            try {
                $reel->video_path = fileUploader($request->video, getFilePath('reel'));
            } catch (\Exception $e) {
                return ['error' => 'Could not upload the reel video'];
            }
        }

        if ($request->hasFile('thumbnail')) {
            try {
                $reel->thumbnail_path = fileUploader($request->thumbnail, getFilePath('reelThumbnail'), getFileSize('reelThumbnail'));
            } catch (\Exception $e) {
                return ['error' => 'Could not upload the thumbnail'];
            }
        }

        if ($request->music_id) {
            $music = ReelMusic::where('id', $request->music_id)->orWhere('slug', $request->music_id)->first();
            $reel->music_id = $music ? $music->id : null;
            if ($music) {
                $music->increment('usage_count');
            }
        } elseif ($request->hasFile('music_file')) {
            try {
                $audioPath = fileUploader($request->music_file, getFilePath('reelMusic'));
                $reel->audio_path = $audioPath;
                $reel->audio_name = $request->audio_name ?? $request->file('music_file')->getClientOriginalName();
            } catch (\Exception $e) {
                return ['error' => 'Could not upload the audio file'];
            }
        }

        $reel->duration = $request->input('duration', 0);
        $reel->save();

        $hashtags = Reel::extractHashtags($request->description);
        if ($request->has('hashtags')) {
            $inputHashtags = is_array($request->hashtags) ? $request->hashtags : json_decode($request->hashtags, true);
            if (is_array($inputHashtags)) {
                foreach ($inputHashtags as $tag) {
                    $tag = ltrim(trim($tag), '#');
                    if ($tag && !in_array($tag, $hashtags)) {
                        $hashtags[] = $tag;
                    }
                }
            }
        }
        foreach ($hashtags as $tag) {
            ReelHashtag::create(['reel_id' => $reel->id, 'hashtag' => $tag]);
        }

        foreach (Reel::extractMentions($request->description) as $username) {
            $mentionedUser = User::where('username', $username)->first();
            if ($mentionedUser) {
                ReelMention::create([
                    'reel_id' => $reel->id,
                    'mentioned_user_id' => $mentionedUser->id,
                    'source' => 'description',
                ]);
            }
        }

        $reelTags = $request->tags ?? [];
        if (empty($reelTags) && isset($hashtags) && is_array($hashtags)) {
            $reelTags = $hashtags;
        }
        foreach ($reelTags as $tag) {
            ReelTag::create(['reel_id' => $reel->id, 'tag' => $tag]);
        }

        CompressReel::dispatch($reel);

        // Notify subscribers in background
        try {
            $user = auth()->user();
            if ($user->channel) {
                $channelName = $user->channel->name ?? $user->username;

                Log::info('[NOTIFY-PIPELINE][STAGE-1] Dispatching NotifySubscribers job from ReelService.', [
                    'trigger' => 'reel_create',
                    'reel_id' => $reel->id,
                    'reel_title' => $reel->title,
                    'channel' => $channelName,
                    'user_id' => $user->id,
                ]);

                \App\Jobs\NotifySubscribers::dispatch(
                    $user->id,
                    $channelName,
                    $channelName . ' uploaded a new reel: ' . $reel->title,
                    route('reels.show', $reel->slug),
                    'New Reel Uploaded!',
                    'reel'
                );

                Log::info('[NOTIFY-PIPELINE][STAGE-1] NotifySubscribers job dispatched successfully from ReelService.');
            } else {
                Log::info('[NOTIFY-PIPELINE][STAGE-1] Reel #{id} — user has no channel, skipping notification.', ['id' => $reel->id]);
            }
        } catch (\Exception $e) {
            Log::error('[NOTIFY-PIPELINE][STAGE-1][ERROR] ReelService notification dispatch failed: ' . $e->getMessage());
        }

        return $reel;
    }

    public function like($reel): array
    {
        $reel = $this->findReel($reel);
        $this->checkVisibility($reel);

        session_write_close(); // Release session lock early for instant response

        $existing = ReelLike::where('reel_id', $reel->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            $existing->delete();
            $reel->decrement('likes_count');
            $liked = false;
        } else {
            ReelLike::create([
                'reel_id' => $reel->id,
                'user_id' => auth()->id(),
                'is_like' => 1,
            ]);
            $reel->increment('likes_count');
            $liked = true;
        }

        return [
            'liked' => $liked,
            'likes_count' => $reel->fresh()->likes_count,
        ];
    }

    /**
     * Idempotent remove-from-liked (delete-only, never creates).
     * Safe under rapid repeat clicks: N calls always end at "removed".
     */
    public function removeLike($reel): array
    {
        $reel = $this->findReel($reel);

        session_write_close(); // Release session lock early for instant response

        $deleted = ReelLike::where('reel_id', $reel->id)
            ->where('user_id', auth()->id())
            ->delete();

        if ($deleted) {
            $reel->decrement('likes_count');
        }

        return [
            'removed' => true,
            'likes_count' => $reel->fresh()->likes_count,
        ];
    }

    public function comment(Request $request, $reel)
    {
        $reel = $this->findReel($reel);
        $this->checkVisibility($reel);

        $blacklistedKeywords = \Cache::remember('blacklisted_keywords', 3600, function () {
            return BlacklistedKeyword::pluck('keyword')->toArray();
        });
        foreach ($blacklistedKeywords as $keyword) {
            if (stripos($request->comment, $keyword) !== false) {
                return ['error' => 'Comment blocked: Inappropriate language detected.'];
            }
        }

        $comment = ReelComment::create([
            'reel_id' => $reel->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->comment,
        ]);

        $reel->increment('comments_count');

        // Process @mentions
        foreach (Reel::extractMentions($request->comment) as $username) {
            $mentionedUser = User::where('username', $username)->first();
            if ($mentionedUser) {
                ReelMention::create([
                    'reel_id' => $reel->id,
                    'mentioned_user_id' => $mentionedUser->id,
                    'source' => 'comment',
                    'comment_id' => $comment->id,
                ]);

                if ($mentionedUser->id != auth()->id()) {
                    UserNotification::create([
                        'user_id' => $mentionedUser->id,
                        'sender_id' => auth()->id(),
                        'title' => auth()->user()->username . ' mentioned you in a reel comment',
                        'click_url' => route('reels.show', $reel->slug),
                    ]);

                    Log::info("Reel Comment Push: Dispatching mention notification to user {$mentionedUser->id} for reel {$reel->id}");
                    SendFirebaseNotificationJob::dispatch($mentionedUser->id, [
                        'title' => 'New Mention on Reel',
                        'body' => auth()->user()->username . ' mentioned you: "' . Str::limit($comment->content, 50) . '"',
                        'url' => route('reels.show', $reel->slug),
                    ]);
                }
            }
        }

        // Notify parent comment author (reply)
        if ($comment->parent_id) {
            $parentComment = ReelComment::find($comment->parent_id);
            if ($parentComment && $parentComment->user_id != auth()->id()) {
                UserNotification::create([
                    'user_id' => $parentComment->user_id,
                    'sender_id' => auth()->id(),
                    'title' => auth()->user()->username . ' replied to your comment on a reel',
                    'click_url' => route('reels.show', $reel->slug),
                ]);

                Log::info("Reel Comment Push: Dispatching reply notification to user {$parentComment->user_id} for reel {$reel->id}");
                SendFirebaseNotificationJob::dispatch($parentComment->user_id, [
                    'title' => 'New Reply on Reel',
                    'body' => auth()->user()->username . ' replied: "' . Str::limit($comment->content, 50) . '"',
                    'url' => route('reels.show', $reel->slug),
                ]);
            }
        } elseif ($reel->user_id != auth()->id()) {
            // Notify reel owner (top-level comment)
            UserNotification::create([
                'user_id' => $reel->user_id,
                'sender_id' => auth()->id(),
                'title' => auth()->user()->username . ' commented on your reel',
                'click_url' => route('reels.show', $reel->slug),
            ]);

            Log::info("Reel Comment Push: Dispatching new comment notification to reel owner user {$reel->user_id} for reel {$reel->id}");
            SendFirebaseNotificationJob::dispatch($reel->user_id, [
                'title' => 'New Comment on Reel',
                'body' => auth()->user()->username . ' commented: "' . Str::limit($comment->content, 50) . '"',
                'url' => route('reels.show', $reel->slug),
            ]);
        }

        return compact('comment', 'reel');
    }

    public function likeComment(ReelComment $comment): array
    {
        $this->checkVisibility($comment->reel);

        $like = ReelCommentLike::where('user_id', auth()->id())
            ->where('comment_id', $comment->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ReelCommentLike::create([
                'user_id' => auth()->id(),
                'comment_id' => $comment->id,
            ]);
            $liked = true;

            if ($comment->user_id != auth()->id()) {
                UserNotification::create([
                    'user_id' => $comment->user_id,
                    'sender_id' => auth()->id(),
                    'title' => auth()->user()->username . ' liked your comment',
                    'click_url' => route('reels.show', $comment->reel->slug),
                ]);
            }
        }

        return [
            'success' => true,
            'liked' => $liked,
            'likes_count' => $comment->likes()->count(),
        ];
    }

    public function updateComment(Request $request, ReelComment $comment): array
    {
        $this->checkVisibility($comment->reel);

        if ((string)auth()->id() !== (string)$comment->user_id) {
            return ['error' => 'Unauthorized'];
        }

        $blacklistedKeywords = \Cache::remember('blacklisted_keywords', 3600, function () {
            return BlacklistedKeyword::pluck('keyword')->toArray();
        });
        foreach ($blacklistedKeywords as $keyword) {
            if (stripos($request->content, $keyword) !== false) {
                return ['error' => 'Update blocked: Inappropriate language detected.'];
            }
        }

        $comment->update(['content' => $request->content]);

        return [
            'status' => 'success',
            'success' => true,
            'message' => 'Comment updated successfully',
            'content' => $comment->content,
        ];
    }

    public function deleteComment(ReelComment $comment): array
    {
        $reel = $comment->reel;
        $this->checkVisibility($reel);

        if ((string)auth()->id() !== (string)$comment->user_id && (string)auth()->id() !== (string)$reel->user_id) {
            return ['error' => 'Unauthorized'];
        }

        $comment->delete();
        $reel->decrement('comments_count');

        return [
            'status' => 'success',
            'success' => true,
            'comments_count' => $reel->fresh()->comments_count,
        ];
    }

    public function share(Request $request, Reel $reel): array
    {
        $this->checkVisibility($reel);

        ReelShare::create([
            'reel_id' => $reel->id,
            'user_id' => auth()->id(),
            'platform' => $request->input('platform', 'copy_link'),
        ]);

        $reel->increment('shares_count');

        return ['shares_count' => $reel->fresh()->shares_count];
    }

    public function report(Request $request, $reel): array
    {
        $reel = $this->findReel($reel);
        if ((string)$reel->user_id === (string)auth()->id()) {
            return ['success' => false, 'message' => 'You cannot report your own reel'];
        }
        $this->checkVisibility($reel);

        $reel->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return ['success' => true, 'message' => 'Reel reported'];
    }

    public function recordView($reel): array
    {
        $reel = $this->findReel($reel);
        $this->checkVisibility($reel);

        $ip = request()->ip();
        $userId = auth()->id();
        $userAgent = request()->userAgent();
        $device = 'Desktop';

        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/mobile|iphone|ipod|android.*mobile|blackberry|windows phone/i', $userAgent)) {
            $device = 'Mobile';
        }

        ReelView::create([
            'reel_id' => $reel->id,
            'user_id' => $userId,
            'ip' => $ip,
        ]);

        if ($userId) {
            $info = ClientInfo::ipInfo();
            $raw = $info['country'] ?? [];
            $country = is_array($raw) ? null : (string)$raw;
            ViewLog::updateOrCreate(
                ['user_id' => $userId, 'reel_id' => $reel->id],
                ['updated_at' => now(), 'device' => $device, 'country' => $country]
            );
        }

        $reel->increment('views_count');

        return [
            'success' => true,
            'counted' => true,
            'views_count' => $reel->fresh()->views_count,
        ];
    }

    public function logDwell(Request $request, $reel): array
    {
        $reel = $this->findReel($reel);
        $dwell = (int)$request->input('dwell_seconds', 0);

        $userId = auth()->id();
        if ($userId) {
            ReelView::where('reel_id', $reel->id)
                ->where('user_id', $userId)
                ->latest()
                ->first()
                ?->update(['dwell_seconds' => $dwell]);
        }

        return ['success' => true];
    }

    public function getComments($reel): array
    {
        $reel = $this->findReel($reel);
        $this->checkVisibility($reel);

        $blockedUsers = auth()->check() ? BlockedUser::where('user_id', auth()->id())->pluck('blocked_user_id')->toArray() : [];

        $comments = $reel->comments()->whereNull('parent_id')->with(['user', 'likes', 'replies' => function ($q) use ($blockedUsers) {
            $q->when(!empty($blockedUsers), function ($q2) use ($blockedUsers) {
                $q2->whereNotIn('user_id', $blockedUsers);
            })->with(['user', 'likes'])->latest();
        }])
            ->when(!empty($blockedUsers), function ($q) use ($blockedUsers) {
                $q->whereNotIn('user_id', $blockedUsers);
            })
            ->orderBy('is_pinned', 'desc')->latest()->get()->map(function ($comment) {
                $formatComment = function($c) {
                    return [
                        'id' => $c->id,
                        'username' => $c->user->username ?? $c->user->fullname,
                        'user_avatar' => $c->user->channel?->avatar
                            ? getImage(getFilePath('channelAvatar') . '/' . $c->user->channel->avatar)
                            : ($c->user->image ? getImage(getFilePath('userProfile') . '/' . $c->user->image) : null),
                        'comment' => $c->content,
                        'user_id' => $c->user_id,
                        'created_at' => $c->created_at->diffForHumans(),
                        'likes_count' => $c->likes->count(),
                        'is_liked' => $c->likes->where('user_id', auth()->id())->count() > 0,
                        'is_edited' => $c->created_at != $c->updated_at,
                        'is_pinned' => (bool)$c->is_pinned,
                        'parent_id' => $c->parent_id,
                    ];
                };

                $formatted = $formatComment($comment);
                $formatted['replies'] = $comment->replies->map($formatComment)->toArray();
                $formatted['showReplies'] = false;
                
                return $formatted;
            });

        return $comments->toArray();
    }

    public function watchLater($reel): array
    {
        $reel = $this->findReel($reel);
        $this->checkVisibility($reel);

        session_write_close(); // Release session lock early for instant response

        $user = auth()->user();
        $playlist = $user->playlists()
            ->where(function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['watch later'])
                    ->orWhere('name', 'Watch Later');
            })
            ->first();

        if (!$playlist) {
            $playlist = $user->playlists()->create(['name' => 'Watch Later']);
        }

        if ($playlist->reels()->where('reel_id', $reel->id)->exists()) {
            $playlist->reels()->detach($reel->id);
            $added = false;
        } else {
            $playlist->reels()->attach($reel->id);
            $added = true;
        }

        return [
            'status' => $added ? 'added' : 'removed',
            'message' => $added ? 'Saved to Watch Later' : 'Removed from Watch Later',
        ];
    }

    public function info($id): array
    {
        $reel = Reel::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $this->checkVisibility($reel);

        $audioUrl = null;
        if ($reel->music_id) {
            $file = $reel->music->file_path ?? $reel->music->audio_path;
            $sourceReel = Reel::where('video_path', $file)
                ->orWhere('compressed_video_path', $file)
                ->first();
            if ($sourceReel) {
                $audioUrl = $sourceReel->getVideoUrl();
            } else {
                $filePath = getFilePath('reelMusic') . '/' . $file;
                if (!file_exists(public_path($filePath))) {
                    $fallbackPath = getFilePath('reel') . '/' . $file;
                    if (file_exists(public_path($fallbackPath))) {
                        $filePath = $fallbackPath;
                    }
                }
                $audioUrl = asset($filePath);
            }
        } elseif ($reel->audio_path) {
            $filePath = getFilePath('reelMusic') . '/' . $reel->audio_path;
            if (!file_exists(public_path($filePath))) {
                $fallbackPath = getFilePath('reel') . '/' . $reel->audio_path;
                if (file_exists(public_path($fallbackPath))) {
                    $filePath = $fallbackPath;
                }
            }
            $audioUrl = asset($filePath);
        } else {
            $audioUrl = $reel->getVideoUrl();
        }

        if ($audioUrl && strpos($audioUrl, 'play_1080p.mp4') !== false) {
            $audioUrl = str_replace('play_1080p.mp4', 'play_360p.mp4', $audioUrl);
        }

        return [
            'success' => true,
            'title' => $reel->audio_name ?? $reel->music->title ?? 'Original Audio',
            'audio_url' => $audioUrl,
        ];
    }

    public function audio($id): array
    {
        $sort = request('sort', 'latest');

        if (strpos($id, 'original_') === 0) {
            $reelSlug = str_replace('original_', '', $id);
            $sourceReel = Reel::with('user')->where('slug', $reelSlug)->firstOrFail();
            $this->checkVisibility($sourceReel);

            $music = (object)[
                'id' => 'original_' . $sourceReel->slug,
                'slug' => 'original_' . $sourceReel->slug,
                'title' => $sourceReel->audio_name ?? 'Original Audio',
                'artist' => $sourceReel->user->username,
                'cover_image' => $sourceReel->user->image ? getImage(getFilePath('userProfile') . '/' . $sourceReel->user->image) : null,
                'is_original' => true,
                'usage_count' => Reel::where('original_reel_id', $sourceReel->id)->forUser()->count() + 1,
            ];

            $query = Reel::where(function ($q) use ($sourceReel) {
                $q->where('original_reel_id', $sourceReel->id)
                    ->orWhere('id', $sourceReel->id);
            })->forUser();
        } else {
            $music = ReelMusic::where('slug', $id)->orWhere('id', $id)->firstOrFail();
            $music->is_original = false;
            $query = Reel::where('music_id', $music->id)->forUser();
        }

        if ($sort === 'popular') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $reels = $query->paginate(24)->appends(['sort' => $sort]);

        return compact('music', 'reels', 'sort');
    }

    public function togglePinComment(ReelComment $comment): array
    {
        $reel = $comment->reel;
        if (auth()->id() != $reel->user_id) {
            return ['status' => 'error', 'message' => 'Unauthorized'];
        }

        $wasPinned = $comment->is_pinned;

        ReelComment::where('reel_id', $reel->id)->where('is_pinned', true)->update(['is_pinned' => false]);

        $comment->is_pinned = !$wasPinned;
        $comment->save();

        return [
            'status' => 'success',
            'is_pinned' => $comment->is_pinned,
            'message' => $comment->is_pinned ? 'Comment pinned!' : 'Comment unpinned!',
        ];
    }

    public function notInterested(Reel $reel): array
    {
        if (!auth()->check()) {
            return ['error' => 'Unauthenticated'];
        }
        if ((string)$reel->user_id === (string)auth()->id()) {
            return ['error' => 'You cannot hide your own reel'];
        }
        $this->checkVisibility($reel);

        \App\Models\NotInterestedReel::firstOrCreate([
            'user_id' => auth()->id(),
            'reel_id' => $reel->id,
        ]);

        $userId = auth()->id();
        \Illuminate\Support\Facades\Cache::forget("home_reels_v3_user_{$userId}");
        // reels page doesn't cache yet, but just in case
        \Illuminate\Support\Facades\Cache::forget("reels_feed_v3_user_{$userId}");

        return [
            'success' => true,
            'message' => 'Reel removed from your feed',
        ];
    }

    public function saveAudio($slug): array
    {
        $reel = Reel::where('slug', $slug)->firstOrFail();
        $this->checkVisibility($reel);

        $musicId = $reel->music_id;

        if (!$musicId) {
            $audioSource = '';
            $sourceType = '';

            if ($reel->global_music_url) {
                $audioSource = $reel->global_music_url;
                $sourceType = 'global_music_url';
            } elseif ($reel->audio_path) {
                $audioSource = $reel->audio_path;
                $sourceType = 'local_audio_path';
            } else {
                $audioSource = $reel->getVideoUrl();
                $sourceType = 'video_url';
            }

            $music = ReelMusic::where('user_id', $reel->user_id)
                ->where('title', $reel->audio_name ?: 'Original Audio')
                ->where('file_path', $audioSource)
                ->first();

            if (!$music) {
                $music = ReelMusic::create([
                    'user_id' => $reel->user_id,
                    'title' => $reel->audio_name ?: 'Original Audio',
                    'artist' => $reel->user->username ?? $reel->user->fullname,
                    'file_path' => $audioSource,
                    'duration' => $reel->duration,
                    'status' => true,
                ]);

                $reel->music_id = $music->id;
                $reel->save();
            }
            $musicId = $music->id;
        }

        return $this->saveAudioDirect($musicId);
    }

    public function saveAudioDirect($musicId): array
    {
        $userId = auth()->id();
        $saved = \App\Models\SavedAudio::where('user_id', $userId)->where('reel_music_id', $musicId)->first();

        if ($saved) {
            $saved->delete();
            return ['message' => 'Audio removed from library', 'is_saved' => false];
        }

        \App\Models\SavedAudio::create([
            'user_id' => $userId,
            'reel_music_id' => $musicId,
        ]);

        return ['message' => 'Audio saved to library', 'is_saved' => true];
    }

    public function useAudio($slug): array
    {
        $reel = Reel::where('slug', $slug)->firstOrFail();
        $this->checkVisibility($reel);

        if ($reel->music_id) {
            return ['redirect' => route('reels.create', ['music' => $reel->music->slug])];
        }

        return ['redirect' => route('reels.create', ['original_reel' => $reel->slug])];
    }

    public function duet($slug): array
    {
        $reel = Reel::where('slug', $slug)->firstOrFail();
        $this->checkVisibility($reel);

        if (!$reel->allow_duet) {
            return ['error' => 'This user does not allow duets'];
        }

        return ['redirect' => route('reels.create', ['duet' => $reel->slug])];
    }

}
