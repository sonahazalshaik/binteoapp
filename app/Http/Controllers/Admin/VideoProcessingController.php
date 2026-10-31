<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoProcessingOption;
use App\Models\VideoProcessingJob;
use Illuminate\Http\Request;
use App\Services\Admin\VideoService;

class VideoProcessingController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $options = VideoProcessingOption::all();
        $jobs = VideoProcessingJob::with('video')->latest()->paginate(10);
        
        return view('admin.processing.index', compact('options', 'jobs'));
    }

    public function create()
    {
        $pageTitle = 'Configure New Resolution';
        return view('admin.processing.create', compact('pageTitle'));
    }

    public function edit($id)
    {
        $option = VideoProcessingOption::findOrFail($id);
        $pageTitle = 'Modify Quality';
        return view('admin.processing.edit', compact('pageTitle', 'option'));
    }

    public function show($id)
    {
        $job = VideoProcessingJob::with('video')->findOrFail($id);
        $pageTitle = 'Job Intelligence';
        return view('admin.processing.show', compact('pageTitle', 'job'));
    }

    public function toggleOption(VideoProcessingOption $option)
    {
        $this->service->toggleProcessingOption($option);

        $notify[] = ['success', 'Processing resolution updated.'];
        return back()->withNotify($notify);
    }

    public function retryJob(VideoProcessingJob $job)
    {
        $this->service->retryProcessingJob($job);

        $notify[] = ['success', 'Job rescheduled for processing.'];
        return back()->withNotify($notify);
    }

    public function destroyJob(VideoProcessingJob $job)
    {
        $this->service->deleteProcessingJob($job);
        $notify[] = ['success', 'Processing job removed.'];
        return back()->withNotify($notify);
    }
}
