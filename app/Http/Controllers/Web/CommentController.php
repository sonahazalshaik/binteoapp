<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Video;
use App\Models\Comment;
use App\Services\Frontend\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request, Video $video)
    {
        $data = $this->commentService->index($request, $video);

        if ($request->ajax()) {
            $html = '';
            foreach ($data['comments'] as $comment) {
                $html .= view('frontend.partials.comment_item', [
                    'comment' => $comment,
                    'video' => $video
                ])->render();
            }

            if (empty($html)) {
                $html = '<p class="no-comments-msg text-[14px] text-gray-500 dark:text-[#AAAAAA]">No comments yet.</p>';
            }

            return response()->json([
                'status' => 'success',
                'html' => $html,
                'count' => $data['comments']->count()
            ]);
        }

        return back();
    }

    public function store(Request $request, Video $video)
    {
        $result = $this->commentService->store($request, $video);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 422);
        }

        if ($request->ajax()) {
            $html = view('frontend.partials.comment_item', [
                'comment' => $result['comment'],
                'video' => $video
            ])->render();

            return response()->json([
                'status' => 'success',
                'html' => $html,
                'comment_id' => $result['comment']->id,
                'parent_id' => $result['comment']->parent_id,
                'comments_count' => $video->comments()->count()
            ]);
        }

        return back();
    }

    public function toggleLike(Comment $comment)
    {
        $result = $this->commentService->toggleLike($comment);

        if (request()->ajax()) {
            return response()->json($result);
        }

        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        $result = $this->commentService->update($request, $comment);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function destroy(Comment $comment)
    {
        $result = $this->commentService->destroy($comment);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        if (request()->ajax()) {
            return response()->json($result);
        }

        $notify[] = ['success', 'Comment deleted successfully'];
        return back()->withNotify($notify);
    }

    public function togglePin(Comment $comment)
    {
        $result = $this->commentService->togglePin($comment);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }
}
