<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CommentService;
use Illuminate\Http\Request;

class CommentReportController extends Controller
{
    protected $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Comment Moderation';
        return view('admin.comment_reports.index', compact('pageTitle') + $this->service->getCommentReports(getPaginate()));
    }

    public function destroy($id)
    {
        $this->service->dismissReport($id);
        $notify[] = ['success', 'Report dismissed successfully'];
        return back()->withNotify($notify);
    }

    public function deleteComment($id)
    {
        $this->service->deleteReportedComment($id);
        $notify[] = ['success', 'Comment deleted successfully'];
        return back()->withNotify($notify);
    }
}
