<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Video;
use App\Models\Playlist;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Services\Frontend\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    protected VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function showInPlaylist(Request $request, $username, Playlist $playlist, $video)
    {
        $request->merge(['list' => $playlist->id]);
        return $this->show($request, $video, $playlist->slug);
    }

    public function showVirtualPlaylist(Request $request, $virtualSlug, $video)
    {
        $request->merge(['list' => $virtualSlug]);
        return $this->show($request, $video);
    }

    public function show(Request $request, $video, $playlist = null)
    {
        $data = $this->videoService->show($request, $video, $playlist);

        if (isset($data['redirect'])) {
            $notify[] = $data['notify'];
            return redirect()->to($data['redirect'])->withNotify($notify);
        }

        return view('frontend.videos.show', $data);
    }

    public function related(Request $request, $id)
    {
        $data = $this->videoService->related($request, $id);

        if (isset($data['html'])) {
            return $data['html'];
        }

        return view('frontend.partials.related_videos', $data);
    }

    public function download(Video $video)
    {
        $data = $this->videoService->download($video);

        if ($data['type'] === 'bunny_stream') {
            $url = $data['url'];
            $fileName = $data['fileName'];
            return response()->streamDownload(function () use ($url) {
                set_time_limit(0);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
                    echo $data;
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                    return strlen($data);
                });
                curl_exec($ch);
                curl_close($ch);
            }, $fileName, [], 'inline');
        }

        if ($data['type'] === 'local') {
            return response()->download(public_path($data['path']), $data['fileName']);
        }

        return redirect($data['url']);
    }

    public function recordAdImpression($video)
    {
        $result = $this->videoService->recordAdImpression($video);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    public function history()
    {
        $data = $this->videoService->history();
        return view('frontend.videos.history', $data);
    }

    public function removeHistory($id)
    {
        $isAjax = request()->ajax();
        $result = $this->videoService->removeHistory($id, $isAjax);

        if ($isAjax) {
            return response()->json($result);
        }

        $notify[] = $result['notify'];
        return back()->withNotify($notify);
    }

    public function removeReelHistory($id)
    {
        $isAjax = request()->ajax();
        $result = $this->videoService->removeReelHistory($id, $isAjax);

        if ($isAjax) {
            return response()->json($result);
        }

        $notify[] = $result['notify'];
        return back()->withNotify($notify);
    }

    public function clearHistory()
    {
        $result = $this->videoService->clearHistory();
        $notify[] = $result['notify'];
        return back()->withNotify($notify);
    }

    public function liked()
    {
        $data = $this->videoService->liked();
        return view('frontend.videos.liked', $data);
    }

    public function watchLater()
    {
        $data = $this->videoService->watchLater();
        return view('frontend.videos.watch_later', $data);
    }

    public function create(Request $request)
    {
        $result = $this->videoService->create($request);

        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with('error', 'You need to create a channel before uploading videos.');
        }

        return view('frontend.videos.create', $result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:1024000',
            'thumbnail' => 'nullable|image|max:20480',
            'visibility' => 'nullable|in:0,1',
        ]);

        $result = $this->videoService->store($request);

        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with('error', 'You need to create a channel before uploading videos.');
        }

        $notify[] = $result['notify'];
        return redirect()->to($result['redirect'])->withNotify($notify);
    }

    public function toggleLike(Video $video)
    {
        $result = $this->videoService->toggleLike($video);
        return response()->json($result);
    }

    public function createOrder(Request $request, Video $video)
    {
        $result = $this->videoService->createVideoOrder($request, $video);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function purchase(Request $request, Video $video)
    {
        $result = $this->videoService->purchase($request, $video);

        if (isset($result['error'])) {
            return response()->json($result, 401);
        }

        return response()->json($result);
    }

    public function trending()
    {
        $data = $this->videoService->trending();
        $data['pageTitle'] = 'Trending Videos';
        return view('frontend.trending', $data);
    }

    public function notInterested(Video $video)
    {
        $result = $this->videoService->notInterested($video);

        if (isset($result['error'])) {
            return response()->json($result, 401);
        }

        return response()->json($result);
    }

    public function report(Request $request, Video $video)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $result = $this->videoService->report($request, $video);
        return response()->json($result);
    }

    public function recordImpression($video)
    {
        $result = $this->videoService->recordImpression($video);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }
}
