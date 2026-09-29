<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Playlist;
use App\Models\Video;
use App\Services\Frontend\PlaylistService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    protected PlaylistService $playlistService;

    public function __construct(PlaylistService $playlistService)
    {
        $this->playlistService = $playlistService;
    }

    public function index()
    {
        $data = $this->playlistService->index();

        return view('frontend.playlists.index', $data);
    }

    public function show($username, Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        $data = $this->playlistService->show($username, $playlist);

        return view('frontend.playlists.show', $data);
    }

    public function store(Request $request)
    {
        $result = $this->playlistService->store($request);
        $playlist = $result['playlist'];

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Playlist created!',
                'playlist' => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'is_member' => false
                ]
            ]);
        }

        $notify[] = ['success', 'Playlist created!'];
        return back()->withNotify($notify);
    }

    public function toggleVideo(Request $request, Video $video)
    {
        $result = $this->playlistService->toggleVideo($request, $video);

        return response()->json($result);
    }

    public function toggleReel(Request $request, $reel)
    {
        $result = $this->playlistService->toggleReel($request, $reel);

        return response()->json($result);
    }

    public function watchLater(Request $request, $id)
    {
        $result = $this->playlistService->watchLater($request, $id);

        $statusCode = isset($result['status']) && $result['status'] === 'error' ? 404 : 200;

        return response()->json($result, $statusCode);
    }

    public function clearWatchLater(Request $request)
    {
        $result = $this->playlistService->clearWatchLater($request);

        return response()->json($result);
    }

    /**
     * Idempotent remove from Watch Later (DELETE-only, never re-adds).
     */
    public function removeWatchLater(Request $request, $id)
    {
        $result = $this->playlistService->removeWatchLater($request, $id);

        return response()->json($result);
    }

    public function getMembershipStatus(Request $request, $id)
    {
        $result = $this->playlistService->getMembershipStatus($request, $id);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $this->playlistService->destroy($playlist);

        $notify[] = ['success', 'Playlist deleted successfully'];
        return back()->withNotify($notify);
    }
}
