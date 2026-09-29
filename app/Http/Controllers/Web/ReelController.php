<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Reel;
use App\Models\ReelComment;
use App\Models\ReelMusic;
use App\Rules\FileTypeValidate;
use App\Services\Frontend\ReelService;
use Illuminate\Http\Request;

class ReelController extends Controller
{
    protected ReelService $reelService;

    public function __construct(ReelService $reelService)
    {
        $this->reelService = $reelService;
    }

    public function index(Request $request)
    {
        $data = $this->reelService->index($request);
        return view('frontend.reels.index', $data);
    }

    public function show($reel)
    {
        $result = $this->reelService->show($reel);

        if (isset($result['notify'])) {
            $notify[] = $result['notify'];
            return redirect()->to($result['redirect'])->withNotify($notify);
        }

        return redirect()->to($result['redirect']);
    }

    public function create()
    {
        $result = $this->reelService->create();

        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with('error', 'You need to create a channel before uploading reels.');
        }

        return view('frontend.reels.create', $result);
    }

    public function searchMusic(Request $request)
    {
        $data = $this->reelService->searchMusic($request);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->channel) {
            return redirect()->route('channels.create')->with('error', 'You need to create a channel before uploading reels.');
        }

        $request->validate([
            'video' => ['required', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2200',
            'thumbnail' => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'category_id' => 'nullable|exists:categories,id',
            'music_id' => 'nullable|exists:reel_music,id',
            'music_file' => ['nullable', 'file', 'mimes:mp3,wav,aac,m4a,mp4,ogg,m4b,3gp,mpeg', 'max:10240'],
            'audio_name' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'visibility' => 'required|in:0,1',
            'location' => 'nullable|string|max:255',
            'is_age_restricted' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'allow_duet' => 'nullable|boolean',
            'allow_stitch' => 'nullable|boolean',
        ]);

        $reel = $this->reelService->store($request);

        if (isset($reel['redirect'])) {
            return redirect()->to($reel['redirect']);
        }

        if (isset($reel['error'])) {
            $notify[] = ['error', $reel['error']];
            return back()->withNotify($notify)->withInput();
        }

        $notify[] = ['success', 'Reel uploaded successfully!'];
        return redirect()->route('reels.show', $reel->slug)->withNotify($notify);
    }

    public function like($reel)
    {
        $result = $this->reelService->like($reel);

        if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
            return response()->json($result);
        }

        return back();
    }

    /**
     * Idempotent remove from Liked videos (DELETE-only, never re-likes).
     */
    public function removeLike($reel)
    {
        $result = $this->reelService->removeLike($reel);

        return response()->json($result);
    }

    public function comment(Request $request, $reel)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:reel_comments,id',
        ]);

        $data = $this->reelService->comment($request, $reel);

        if (isset($data['error'])) {
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json(['error' => $data['error']], 403);
            }
            return back()->withErrors(['comment' => $data['error']]);
        }

        $comment = $data['comment'];
        $reel = $data['reel'];

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            $responseArray = [
                'comment' => [
                    'id' => $comment->id,
                    'username' => auth()->user()->username ?? auth()->user()->fullname,
                    'user_avatar' => auth()->user()->channel?->avatar
                        ? getImage(getFilePath('channelAvatar') . '/' . auth()->user()->channel->avatar)
                        : (auth()->user()->image ? getImage(getFilePath('userProfile') . '/' . auth()->user()->image) : null),
                    'comment' => $comment->content,
                    'user_id' => $comment->user_id,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'likes_count' => 0,
                    'is_liked' => false,
                    'is_edited' => false,
                    'is_pinned' => false,
                    'parent_id' => $comment->parent_id,
                    'replies' => [],
                    'showReplies' => false,
                ],
                'comments_count' => $reel->fresh()->comments_count,
            ];
            \Illuminate\Support\Facades\Log::info("DEBUG REEL COMMENT RESPONSE:", $responseArray);
            return response()->json($responseArray);
        }

        $notify[] = ['success', 'Comment posted!'];
        return back()->withNotify($notify);
    }

    public function likeComment(ReelComment $comment)
    {
        $result = $this->reelService->likeComment($comment);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }

    public function updateComment(Request $request, ReelComment $comment)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $result = $this->reelService->updateComment($request, $comment);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }

    public function deleteComment(ReelComment $comment)
    {
        $result = $this->reelService->deleteComment($comment);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }

    public function share(Request $request, Reel $reel)
    {
        $result = $this->reelService->share($request, $reel);

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return response()->json($result);
        }

        return back();
    }

    public function report(Request $request, $reel)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $result = $this->reelService->report($request, $reel);

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return response()->json($result);
        }

        $notify[] = ['success', 'Reel has been reported'];
        return back()->withNotify($notify);
    }

    public function recordView($reel)
    {
        $result = $this->reelService->recordView($reel);
        return response()->json($result);
    }

    public function logDwell(Request $request, $reel)
    {
        $result = $this->reelService->logDwell($request, $reel);
        return response()->json($result);
    }

    public function getComments($reel)
    {
        $comments = $this->reelService->getComments($reel);
        return response()->json($comments);
    }

    public function watchLater($reel)
    {
        $result = $this->reelService->watchLater($reel);
        return response()->json($result);
    }

    public function info($id)
    {
        $result = $this->reelService->info($id);

        if (isset($result['error'])) {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    public function audio($id)
    {
        $data = $this->reelService->audio($id);
        return view('frontend.reels.audio', $data);
    }

    public function togglePinComment(ReelComment $comment)
    {
        $result = $this->reelService->togglePinComment($comment);
        return response()->json($result);
    }

    public function notInterested(Reel $reel)
    {
        $result = $this->reelService->notInterested($reel);

        if (isset($result['error'])) {
            return response()->json($result, 401);
        }

        return response()->json($result);
    }
}
