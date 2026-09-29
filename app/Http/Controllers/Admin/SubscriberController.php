<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ChannelService;

class SubscriberController extends Controller
{
    protected $service;

    public function __construct(ChannelService $service)
    {
        $this->service = $service;
    }

    public function index(\Illuminate\Http\Request $request, $id = null)
    {
        $pageTitle = 'List of Channels';
        
        $channels = $this->service->getSubscriberChannels($request->search, $id, getPaginate());

        return view('admin.subscriber.index', compact('pageTitle', 'channels'));
    }
}
