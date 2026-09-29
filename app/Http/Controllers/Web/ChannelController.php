<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Channel;
use App\Services\Frontend\ChannelService;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    protected ChannelService $channelService;

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    public function show($slug)
    {
        $data = $this->channelService->show($slug);

        if (isset($data['redirect'])) {
            return redirect($data['redirect']);
        }

        return view('frontend.channels.show', $data);
    }

    public function showByUsername($username)
    {
        $result = $this->channelService->showByUsername($username);

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
            return redirect()->route('home')->withNotify($notify);
        }

        return $this->show($result['slug']);
    }

    public function checkAvailability(Request $request)
    {
        $result = $this->channelService->checkAvailability($request);

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function create()
    {
        $data = $this->channelService->create();

        if (isset($data['redirect'])) {
            return redirect($data['redirect']);
        }

        return view('frontend.channels.create', $data);
    }

    public function store(Request $request)
    {
        $result = $this->channelService->store($request);

        if (isset($result['validation_error'])) {
            $notify[] = ['error', $result['validation_error']];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($result['error'])) {
            $notify[] = ['error', $result['error']];
            return back()->withNotify($notify)->withInput();
        }

        $notify[] = ['success', 'Channel created successfully! Welcome to your Studio.'];
        return redirect()->route('studio.dashboard')->withNotify($notify);
    }

    public function report(Request $request, Channel $channel)
    {
        $result = $this->channelService->report($request, $channel);

        return response()->json($result);
    }
}
