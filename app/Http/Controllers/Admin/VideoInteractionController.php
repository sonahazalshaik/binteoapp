<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Services\Admin\VideoService;

class VideoInteractionController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function likes(Video $video)
    {
        $pageTitle = 'Manage Likes: ' . $video->title;
        $likes = $this->service->getVideoLikes($video);
        return view('admin.videos.interactions.likes', compact('pageTitle', 'video', 'likes'));
    }

    public function destroyLike($id)
    {
        $this->service->deleteLike($id);
        $notify[] = ['success', 'Interaction removed successfully'];
        return back()->withNotify($notify);
    }

    public function comments(Video $video)
    {
        $pageTitle = 'Manage Comments: ' . $video->title;
        $comments = $this->service->getVideoComments($video);
        return view('admin.videos.interactions.comments', compact('pageTitle', 'video', 'comments'));
    }

    public function playlists(Video $video)
    {
        $pageTitle = 'Playlist Presence: ' . $video->title;
        $playlists = $this->service->getVideoPlaylists($video);
        
        return view('admin.videos.interactions.playlists', compact('pageTitle', 'video', 'playlists'));
    }

    public function removeFromPlaylist(Request $request, Video $video)
    {
        $this->service->removeVideoFromPlaylist($video, $request->playlist_id);
        $notify[] = ['success', 'Video removed from target playlist'];
        return back()->withNotify($notify);
    }

    public function watchLater(Video $video)
    {
        $pageTitle = 'Watch Later List: ' . $video->title;
        $playlists = $this->service->getVideoWatchLaterPlaylists($video);

        return view('admin.videos.interactions.watch_later', compact('pageTitle', 'video', 'playlists'));
    }
}
