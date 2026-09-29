<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\CommentService;
use Illuminate\Http\Request;

class CommentModerationController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function blockUser(Request $request)
    {
        $result = $this->commentService->blockUser($request);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function reportComment(Request $request)
    {
        $result = $this->commentService->reportComment($request);

        return response()->json($result);
    }
}
