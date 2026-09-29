<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Admin\ChannelService;

class ChannelController extends Controller
{
    protected $service;

    public function __construct(ChannelService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'All Channels';
        $channels = $this->service->channelQuery()->paginate(getPaginate());
        return view('admin.channels.index', compact('pageTitle', 'channels'));
    }

    public function featured()
    {
        $pageTitle = 'Featured Channels';
        $channels = $this->service->channelQuery()->where('is_featured', 1)->paginate(getPaginate());
        return view('admin.channels.index', compact('pageTitle', 'channels'));
    }

    public function trending()
    {
        $pageTitle = 'Trending Channels';
        $channels = $this->service->channelQuery()->where('is_trending', 1)->paginate(getPaginate());
        return view('admin.channels.index', compact('pageTitle', 'channels'));
    }

    public function toggleFeatured($id)
    {
        $channel = Channel::findOrFail($id);
        $status = $this->service->toggleFeatured($channel);

        $notify[] = ['success', "Channel $status successfully"];
        return back()->withNotify($notify);
    }

    public function toggleTrending($id)
    {
        $channel = Channel::findOrFail($id);
        $status = $this->service->toggleTrending($channel);

        $notify[] = ['success', "Channel marked as $status successfully"];
        return back()->withNotify($notify);
    }

    public function create()
    {
        $pageTitle = 'Architect Broadcast Frequency';
        // Pull users who don't have a channel yet, or just all users for selection
        $users = User::active()->orderBy('username')->get();
        return view('admin.channels.create', compact('pageTitle', 'users'));
    }

    public function show(Channel $channel)
    {
        $pageTitle = 'Channel Details: ' . $channel->name;
        $channel = $this->service->getChannelDetail($channel);
        return view('admin.channels.view', compact('channel', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'user_id'       => 'required|exists:users,id|unique:channels,user_id',
            'avatar'        => 'nullable|image|max:10240',
            'banner'        => 'nullable|image|max:20480',
            'social_links'  => 'nullable|array',
        ]);

        $files = [];
        if ($request->hasFile('avatar')) {
            $files['avatar'] = $request->avatar;
        }
        if ($request->hasFile('banner')) {
            $files['banner'] = $request->banner;
        }

        $this->service->createChannel($request->only(['name', 'description', 'user_id', 'social_links']), $files);

        $notify[] = ['success', 'Broadcast signal initialized successfully'];
        return redirect()->route('admin.channels.index')->withNotify($notify);
    }

    public function edit(Channel $channel)
    {
        $pageTitle = 'Refine Frequency: ' . $channel->name;
        $users = User::active()->orderBy('username')->get();
        $countries = json_decode('[{"country":"India","dial_code":"91","code":"IN"},{"country":"United States","dial_code":"1","code":"US"},{"country":"United Kingdom","dial_code":"44","code":"GB"}]');
        return view('admin.channels.edit', compact('channel', 'pageTitle', 'users', 'countries'));
    }

    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'avatar'        => 'nullable|image|max:10240',
            'banner'        => 'nullable|image|max:20480',
            'social_links'  => 'nullable|array',
            'country'       => 'required|string',
            'country_code'  => 'required|string',
        ]);

        $files = [];
        if ($request->hasFile('avatar')) {
            $files['avatar'] = $request->avatar;
        }
        if ($request->hasFile('banner')) {
            $files['banner'] = $request->banner;
        }

        $this->service->updateChannel($channel, $request->only(['name', 'description', 'social_links', 'country', 'country_code']), $files);

        $notify[] = ['success', 'Frequency parameters updated successfully'];
        return back()->withNotify($notify);
    }

    public function toggleStatus(Channel $channel)
    {
        $this->service->toggleStatus($channel);

        $notify[] = ['success', 'Channel status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function destroy(Channel $channel)
    {
        $this->service->deleteChannel($channel);
        $notify[] = ['success', 'Channel deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids'    => 'required|array',
            'ids.*'  => 'required',
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        if (!$ids || count($ids) == 0) {
            $notify[] = ['error', 'No channels selected'];
            return back()->withNotify($notify);
        }

        $this->service->bulkAction($ids, $action);

        $notify[] = ['success', 'Bulk action executed successfully'];
        return back()->withNotify($notify);
    }
}
