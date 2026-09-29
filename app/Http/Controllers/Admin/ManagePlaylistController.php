<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PlaylistService;
use Illuminate\Http\Request;

class ManagePlaylistController extends Controller {
    protected $service;

    public function __construct(PlaylistService $service)
    {
        $this->service = $service;
    }

    public function index() {
        $pageTitle = "Playlist Intelligence Repository";
        $playlists = $this->service->getAllPlaylists();
        return view('admin.playlist.index', compact('pageTitle', 'playlists'));
    }

    public function create() {
        $pageTitle = "Initialize Playlist Framework";
        return view('admin.playlist.create', compact('pageTitle'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility'  => 'required|in:0,1',
            'price'       => 'nullable|numeric|min:0',
            'playlist_subscription' => 'nullable',
            'video_ids'   => 'nullable|array',
            'video_ids.*' => 'exists:videos,id',
            'reel_ids'    => 'nullable|array',
            'reel_ids.*'  => 'exists:reels,id',
        ]);

        $this->service->createPlaylist($request);

        $notify[] = ['success', 'Playlist initialized successfully with content.'];
        return to_route('admin.playlist.index')->withNotify($notify);
    }

    public function getUserVideos($userId)
    {
        $data = $this->service->getUserContent($userId);
        $data['success'] = true;
        return response()->json($data);
    }

    public function edit($id) {
        $playlist = $this->service->findPlaylist($id);
        $pageTitle = "Modify Playlist Intelligence: " . ($playlist->title ?? $playlist->name);
        return view('admin.playlist.edit', compact('pageTitle', 'playlist'));
    }

    public function update(Request $request, $id = 0) {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility'  => 'required|in:0,1',
            'price'       => 'nullable|numeric|min:0',
            'playlist_subscription'  => 'nullable',
            'video_ids'   => 'nullable|array',
            'video_ids.*' => 'exists:videos,id',
            'reel_ids'    => 'nullable|array',
            'reel_ids.*'  => 'exists:reels,id',
        ]);

        $this->service->updatePlaylist($request, $id);

        $notify[] = ['success', 'Playlist intelligence updated with content.'];
        return back()->withNotify($notify);
    }

    public function show($id) {
        $playlist = $this->service->findPlaylist($id);
        $playlist->load(['user', 'videos', 'reels']);
        $pageTitle = "Playlist Analysis: " . ($playlist->title ?? $playlist->name);
        return view('admin.playlist.show', compact('pageTitle', 'playlist'));
    }

    public function destroy($id) {
        $this->service->deletePlaylist($id);

        $notify[] = ['success', 'Playlist decommissioned successfully.'];
        return back()->withNotify($notify);
    }

    public function videosList($id) {
        $playlist  = $this->service->findPlaylist($id);
        $pageTitle = "Videos in " . ($playlist->title ?? $playlist->name);
        $videos    = $this->service->getPlaylistVideos($id);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }
}
