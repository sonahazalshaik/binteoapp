<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VideoCommission;
use App\Services\Admin\VideoService;

class VideoCommissionController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'PPV Video Sales & Commissions';
        $commissions = $this->service->getVideoCommissionList(getPaginate());
        return view('admin.video_commissions.index', compact('pageTitle', 'commissions'));
    }
}
