<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'All Comments';
        $comments = $this->service->getAllComments(20);
        return view('admin.comments.index', compact('comments', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Add New Comment';
        $users = \App\Models\User::creators()->orderBy('username')->get();
        return view('admin.comments.create', compact('pageTitle', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment'    => 'required|string',
            'user_id'    => 'required|exists:users,id',
            'comment_type' => 'required|in:video,reel',
            'video_id'   => 'required_if:comment_type,video|exists:videos,id',
            'reel_id'    => 'required_if:comment_type,reel|exists:reels,id',
        ]);

        $this->service->createComment($request->all());

        $notify[] = ['success', 'Community signal synchronized successfully.'];
        return redirect()->route('admin.comments.index')->withNotify($notify);
    }
    public function show(Request $request, $id)
    {
        $type = $request->type ?? 'video';
        $pageTitle = 'Comment Details';
        
        $comment = $this->service->getComment($type, $id);
        return view('admin.comments.view', compact('comment', 'pageTitle'));
    }

    public function edit(Request $request, $id)
    {
        $type = $request->type ?? 'video';
        $pageTitle = 'Revise Comment';
        $users = \App\Models\User::creators()->orderBy('username')->get();
        
        $comment = $this->service->getComment($type, $id);
        return view('admin.comments.edit', compact('comment', 'pageTitle', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment'    => 'required|string',
            'user_id'    => 'required|exists:users,id',
            'comment_type' => 'required|in:video,reel',
            'video_id'   => 'required_if:comment_type,video|exists:videos,id',
            'reel_id'    => 'required_if:comment_type,reel|exists:reels,id',
        ]);

        $this->service->updateComment($id, $request->all());

        $notify[] = ['success', 'Comment framework updated successfully.'];
        return redirect()->route('admin.comments.index')->withNotify($notify);
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->type ?? 'video';
        $this->service->deleteComment($type, $id);
        $notify[] = ['success', 'Comment deleted successfully.'];
        return back()->withNotify($notify);
    }
}
