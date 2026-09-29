<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\Admin\VideoService;
use App\Services\BunnyStreamService;
use Carbon\Carbon;

class VideoController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Video Library';
        $videos = Video::with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function draft()
    {
        $pageTitle = 'Draft Videos';
        $videos = Video::draft()->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function premium()
    {
        $pageTitle = 'Premium Videos';
        $videos = Video::where('is_premium', 1)->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function featured()
    {
        $pageTitle = 'Featured Videos';
        $videos = Video::where('is_featured', 1)->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function liked()
    {
        $pageTitle = 'Liked Videos';
        $videos = Video::has('likes')->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function trending()
    {
        $pageTitle = 'Trending Videos';
        $videos = Video::trending()->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function toggleTrending(Video $video)
    {
        $video->is_trending = !$video->is_trending;
        $video->save();

        $status = $video->is_trending ? 'marked as trending' : 'removed from trending';
        $notify[] = ['success', "Video {$status} successfully"];
        return back()->withNotify($notify);
    }

    public function public()
    {
        $pageTitle = 'Public Videos';
        $videos = Video::public()->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function private()
    {
        $pageTitle = 'Private Videos';
        $videos = Video::private()->with(['user', 'likes', 'allComments', 'playlists'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function show(Video $video)
    {
        $pageTitle = 'Video Details: ' . $video->title;
        $video->load(['user', 'likes', 'allComments.user', 'playlists', 'categories']);
        
        $now = now();
        $slot1Banners = \App\Models\BannerAd::where('slot', 'slot1')
            ->where('status', Status::ENABLE)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();
            
        $slot2Banners = \App\Models\BannerAd::where('slot', 'slot2')
            ->where('status', Status::ENABLE)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        // Dynamic Related Sections
        $creatorVideos = Video::where('user_id', $video->user_id)
            ->where('id', '!=', $video->id)
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $categoryIds = $video->categories->pluck('id')->toArray();
        $relatedVideos = Video::whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->where('id', '!=', $video->id)
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $userStats = [
            'total_videos' => Video::where('user_id', $video->user_id)->count(),
            'total_likes'  => \App\Models\Like::whereHas('video', function($q) use ($video) {
                $q->where('user_id', $video->user_id);
            })->count(),
            'total_views'  => Video::where('user_id', $video->user_id)->sum('views_count'),
            'subscribers'  => \App\Models\Subscription::where('user_id', $video->user_id)->count(),
        ];

        return view('admin.videos.view', compact('pageTitle', 'video', 'slot1Banners', 'slot2Banners', 'creatorVideos', 'relatedVideos', 'userStats'));
    }

    public function create()
    {
        $pageTitle  = 'Upload New Video';
        $categories = Category::active()->get();
        $users      = User::active()->has('channel')->orderBy('username')->get();
        $general    = gs();
        return view('admin.videos.create', compact('pageTitle', 'categories', 'users', 'general'));
    }

    /**
     * Prepare Bunny TUS upload for admin (browser -> Bunny, zero server bytes)
     * Mirrors UploadService::prepareUpload but assigns video to selected creator
     */
    public function prepareUpload(Request $request, BunnyStreamService $bunny)
    {
        $request->validate([
            'user'               => 'required|exists:users,id',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category_id'        => 'required|exists:categories,id',
            'visibility'         => 'nullable|in:0,1',
            'is_age_restricted'  => 'nullable|boolean',
            'location'           => 'nullable|string|max:255',
            'duration'           => 'nullable|string',
            'language'           => 'nullable|string|max:255',
            'pricing_tier'       => 'nullable|string|in:free,premium,exclusive',
            'price'              => 'nullable|numeric|min:0',
            'tags'               => 'nullable',
            'schedule_video'     => 'nullable',
            'schedule_date'      => 'nullable|string',
            'schedule_time'      => 'nullable|string',
            'is_draft'           => 'nullable',
            'captions'           => 'nullable|file|mimes:vtt,srt|max:5120',
        ]);

        $targetUser = User::findOrFail($request->input('user'));

        Log::info('Admin BunnyUpload: Preparing Video Upload', [
            'admin_id'  => auth()->guard('admin')->id(),
            'target_user_id' => $targetUser->id,
            'title'     => $request->title,
        ]);

        $bunny->useVideoLibrary();
        $bunnyResponse = $bunny->createVideo($request->title, $bunny->getVideoCollectionId());
        $bunnyId = $bunnyResponse['guid'];

        $slug = Str::slug($request->title);
        if (Video::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(5);
        }

        $video = new Video();
        $video->user_id = $targetUser->id;
        $video->title = $request->title;
        $video->slug = $slug;
        $video->description = $request->description;
        $video->video_path = '';
        $video->bunny_id = $bunnyId;
        $video->bunny_status = 'uploading';
        // Map frontend is_draft / policy draft
        $isDraft = $request->boolean('is_draft') || $request->input('policy') === 'draft' || $request->status === '0' || $request->status === 0;
        $video->status = $isDraft ? Status::DRAFT : Status::PUBLISHED;
        $video->visibility = $request->visibility ?? Status::PUBLIC;
        // visibility 0=public 1=private
        if ($request->has('visibility')) {
            $video->visibility = $request->visibility == '0' ? Status::PUBLIC : Status::PRIVATE;
            // Legacy constant: Status::PUBLIC = 1 ? Check actual value. Use raw string fallback
            // Keep compatible with existing: 'public'/'private'
            if ($video->visibility == Status::PUBLIC || $video->visibility == '1' || $video->visibility == 1) {
                // keep as is for service check
            }
            // Normalize to string expected by Video model: 'public'/'private'
            $video->visibility = $request->visibility == '0' ? 'public' : 'private';
        }
        $video->location = $request->location;
        $video->language = $request->language;
        $video->is_age_restricted = $request->boolean('is_age_restricted') || $request->has('is_age_restricted') && $request->is_age_restricted == '1';
        $video->duration = $request->duration ?: '00:00';

        if ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time) {
            $video->scheduled_at = Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'));
        }

        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $pricingTierStr = $request->pricing_tier ?? 'free';
        $pricingTierInt = $tierMap[$pricingTierStr] ?? 0;
        $video->pricing_tier = $pricingTierInt;
        // Admin bypass: allow premium even if user has no access (admin override)
        $video->is_premium = in_array($pricingTierStr, ['premium', 'exclusive']) ? 1 : 0;
        $video->price = $video->is_premium ? ($request->price ?? 0) : 0;

        if ($request->hasFile('captions')) {
            try { $video->captions_path = fileUploader($request->file('captions'), getFilePath('captions')); } catch (\Exception $e) { Log::warning('Admin: captions upload failed '.$e->getMessage()); }
        }

        $video->save();
        $video->categories()->attach($request->category_id);

        if ($request->tags) {
            $tags = is_array($request->tags) ? $request->tags : json_decode($request->tags, true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (trim($tag)) { $video->tags()->create(['tag' => trim($tag)]); }
                }
            }
        }

        $bunny->useVideoLibrary();
        $tusParams = $bunny->getTusUploadParams($bunnyId);

        // Notify subscribers if not draft (mirrors UploadService)
        if (!$isDraft) {
            try {
                $user = $video->user;
                if ($user && $user->channel) {
                    \App\Jobs\NotifySubscribers::dispatch($user->id, $user->channel->name ?? $user->username, ($user->channel->name ?? $user->username).' uploaded a new video: '.$video->title, route('videos.show', $video->slug), ($user->channel->name ?? $user->username).' uploaded a new video!', 'video');
                }
            } catch (\Exception $e) { Log::error('Admin notify failed '.$e->getMessage()); }
        }

        \App\Models\AdminNotification::create([
            'user_id' => $targetUser->id,
            'title' => 'New video uploading (Bunny Stream, via Admin): ' . $video->title,
            'click_url' => route('admin.videos.index'),
        ]);

        return response()->json([
            'success' => true,
            'video_id' => $video->id,
            'video_slug' => $video->slug,
            'bunny_id' => $bunnyId,
            'tus' => $tusParams,
            'direct' => [
                'url' => "https://video.bunnycdn.com/library/{$tusParams['headers']['LibraryId']}/videos/{$bunnyId}",
                'access_key' => $bunny->getApiKey()
            ]
        ]);
    }

    public function uploadThumbnail(Request $request, Video $video)
    {
        $request->validate(['thumbnail' => 'required|image|max:20480']);
        // Admin can update any video
        $thumbnailPath = fileUploader($request->file('thumbnail'), getFilePath('thumbnail'));
        $video->update(['thumbnail_path' => $thumbnailPath]);
        return response()->json(['success' => true, 'thumbnail_url' => asset(getFilePath('thumbnail').'/'.$thumbnailPath)]);
    }

    public function directUpload(Request $request, BunnyStreamService $bunny)
    {
        $request->validate(['video' => 'required|file', 'video_id' => 'required|integer']);
        $video = Video::where('id', $request->video_id)->first();
        if (!$video || !$video->bunny_id) {
            return response()->json(['success'=>false,'message'=>'Video not found'], 404);
        }
        $bunny->useVideoLibrary();
        $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
        $libraryId = gs('bunny_video_library_id') ?: config('bunny.library_id');
        try {
            $file = $request->file('video');
            $fp = @fopen($file->getRealPath(), 'rb');
            if ($fp===false) return response()->json(['success'=>false,'message'=>'Local source file could not be read.'],500);
            $response = Http::withHeaders(['AccessKey'=>$apiKey,'Content-Type'=>'application/octet-stream'])->timeout(3600)->withBody($fp,'application/octet-stream')->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$video->bunny_id}");
            if ($response->successful()) { Log::info('Admin DirectUpload success', ['video_id'=>$video->id]); return response()->json(['success'=>true,'message'=>'Video uploaded successfully']); }
            Log::error('Admin DirectUpload Bunny rejected', ['status'=>$response->status(),'body'=>$response->body()]);
            return response()->json(['success'=>false,'message'=>'Upload failed: '.$response->body()],500);
        } catch (\Exception $e) {
            Log::error('Admin DirectUpload exception '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Upload error: '.$e->getMessage()],500);
        }
    }

    public function logError(Request $request)
    {
        $request->validate(['error_message'=>'required|string','type'=>'required|string|in:video,reel','details'=>'nullable|array']);
        Log::error("TUS Upload Error ({$request->type}) [ADMIN]: {$request->error_message}", ['admin_id'=>auth()->guard('admin')->id(),'details'=>$request->details,'ip'=>$request->ip(),'user_agent'=>$request->userAgent()]);
        return response()->json(['success'=>true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user'          => 'required|exists:users,id',
            'title'         => 'required|string|max:255',
            'category'      => 'required|exists:categories,id',
            'language'      => 'nullable|string|max:255',
            'video'         => 'required|file|mimes:mp4,mov,avi,wmv|max:1024000',
            'thumb_image'   => 'nullable|image|max:10240',
            'visibility'    => 'required|in:0,1',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'duration'      => 'nullable|string|max:20',
            'tags'          => 'nullable|array',
            'tags.*'        => 'string|max:100',
            'pricing_tier'  => 'nullable|in:free,premium,exclusive',
            'price'         => 'nullable|numeric|min:0',
            'captions'      => 'nullable|file|mimes:vtt,srt|max:5120',
        ]);

        $video    = new Video();
        $user     = User::findOrFail($request->input('user'));
        $category = Category::findOrFail($request->category);

        $video->user_id     = $user->id;
        $video->title       = $request->title;
        $video->slug        = Str::slug($request->title) . '-' . Str::random(5);
        $video->description = $request->description;
        $video->visibility  = $request->visibility == '0' ? 'public' : 'private';
        $video->language    = $request->language;
        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $video->pricing_tier = $tierMap[$request->pricing_tier ?? 'free'] ?? 0;
        $video->is_premium  = in_array($request->pricing_tier, ['premium', 'exclusive']) ? 1 : 0;
        $video->price       = in_array($request->pricing_tier, ['premium', 'exclusive']) ? ($request->price ?? 0) : 0;
        $video->status      = $request->status; 
        $video->location    = $request->location;
        $video->duration    = $request->duration ?: '00:00';

        if ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time) {
            $video->scheduled_at = \Carbon\Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'));
        } else {
            $video->scheduled_at = null;
        }
        $video->is_age_restricted = $request->has('is_age_restricted');

        if ($request->hasFile('video')) {
            $video->video_path = fileUploader($request->video, getFilePath('video'));
        }

        if ($request->hasFile('thumb_image')) {
            $video->thumbnail_path = fileUploader($request->thumb_image, getFilePath('thumbnail'));
        }

        if ($request->hasFile('captions')) {
            $video->captions_path = fileUploader($request->captions, getFilePath('captions'));
        }

        $video->save();
        $video->categories()->attach($category->id);

        if ($request->tags) {
            foreach ($request->tags as $tag) {
                if (trim($tag)) {
                    $videoTag = new \App\Models\VideoTag();
                    $videoTag->video_id = $video->id;
                    $videoTag->tag = trim($tag);
                    $videoTag->save();
                }
            }
        }

        // Upload to Bunny CDN
        try {
            $bunny = app(\App\Services\BunnyStreamService::class);
            $bunny->useVideoLibrary();
            $bunnyResponse = $bunny->createVideo($request->title);
            $bunnyId = $bunnyResponse['guid'];

            $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
            $libraryId = gs('bunny_video_library_id') ?: config('bunny.library_id');

            $localPath = public_path(getFilePath('video') . '/' . $video->video_path);
            if (file_exists($localPath)) {
                set_time_limit(0);
                $fileContent = file_get_contents($localPath);
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'AccessKey' => $apiKey,
                    'Content-Type' => 'application/octet-stream',
                ])->timeout(3600)->withBody($fileContent, 'application/octet-stream')
                  ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$bunnyId}");

                if ($response->successful()) {
                    $video->update(['bunny_id' => $bunnyId, 'bunny_status' => 'processing']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Admin: Bunny CDN upload failed for video #' . $video->id . ': ' . $e->getMessage());
        }

        if (class_exists('App\Jobs\ProcessVideo')) {
            \App\Jobs\ProcessVideo::dispatch($video);
        }

        $notify[] = ['success', 'Video uploaded and is now being processed'];
        return redirect()->route('admin.videos.index')->withNotify($notify);
    }

    public function edit(Video $video)
    {
        $pageTitle  = 'Edit Video: ' . $video->title;
        $categories = Category::active()->get();
        $users      = User::active()->has('channel')->orderBy('username')->get();
        $video->load(['categories', 'tags']);
        return view('admin.videos.edit', compact('pageTitle', 'video', 'categories', 'users'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|exists:categories,id',
            'language'    => 'nullable|string|max:255',
            'thumb_image' => 'nullable|image|max:10240',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'duration'    => 'nullable|string|max:20',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string|max:100',
            'pricing_tier' => 'nullable|in:free,premium,exclusive',
            'price'       => 'nullable|numeric|min:0',
            'captions'    => 'nullable|file|mimes:vtt,srt|max:5120',
        ]);

        $video->title       = $request->title;
        $video->description = $request->description;
        $video->language    = $request->language;
        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $video->pricing_tier = $tierMap[$request->pricing_tier ?? 'free'] ?? 0;
        $video->is_premium  = in_array($request->pricing_tier, ['premium', 'exclusive']) ? 1 : 0;
        $video->price       = in_array($request->pricing_tier, ['premium', 'exclusive']) ? ($request->price ?? 0) : 0;

        if ($request->hasFile('thumb_image')) {
            $video->thumbnail_path = fileUploader($request->thumb_image, getFilePath('thumbnail'), null, $video->thumbnail_path);
        }

        if ($request->hasFile('captions')) {
            $video->captions_path = fileUploader($request->captions, getFilePath('captions'));
        }

        $video->status      = $request->status;
        $video->visibility  = $request->visibility == '0' ? 'public' : 'private';
        $video->user_id     = $request->input('user') ?? $video->user_id;
        $video->is_age_restricted = $request->has('is_age_restricted');
        $video->location    = $request->location;
        $video->duration    = $request->duration ?: $video->duration;
        if ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time) {
            $video->scheduled_at = \Carbon\Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'));
        } else {
            $video->scheduled_at = null;
        }
        $video->save();
        $video->categories()->sync([$request->category]);

        if ($request->tags) {
            $video->tags()->delete();
            foreach ($request->tags as $tag) {
                if (trim($tag)) {
                    $videoTag = new \App\Models\VideoTag();
                    $videoTag->video_id = $video->id;
                    $videoTag->tag = trim($tag);
                    $videoTag->save();
                }
            }
        }

        if ($video->isBunnyVideo()) {
            try {
                app(\App\Services\BunnyStreamService::class)->useVideoLibrary()->updateVideo($video->bunny_id, ['title' => $request->title]);
            } catch (\Exception $e) {
                \Log::warning('Admin: Failed to update video in Bunny Stream: ' . $e->getMessage());
            }
        }

        $notify[] = ['success', 'Video details updated successfully'];
        return back()->withNotify($notify);
    }

    public function approve(Video $video)
    {
        $video->moderation_status = 'approved';
        $video->save();

        $notify[] = ['success', 'Video approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Video $video)
    {
        $video->moderation_status = 'rejected';
        $video->save();

        $notify[] = ['success', 'Video rejected successfully'];
        return back()->withNotify($notify);
    }

    public function destroy(Video $video)
    {
        $video->deleteWithAssets();
        $notify[] = ['success', 'Video deleted successfully'];
        return back()->withNotify($notify);
    }

    public function toggleFeatured(Video $video)
    {
        $video->is_featured = !$video->is_featured;
        $video->save();

        $status = $video->is_featured ? 'featured' : 'unfeatured';
        $notify[] = ['success', "Video $status successfully"];
        return back()->withNotify($notify);
    }

    public function toggleAgeRestriction(Video $video)
    {
        $video->is_age_restricted = !$video->is_age_restricted;
        $video->save();

        $status = $video->is_age_restricted ? 'age restricted' : 'unrestricted';
        $notify[] = ['success', "Video $status successfully"];
        return back()->withNotify($notify);
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'required',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if (!$ids || count($ids) == 0) {
            $notify[] = ['error', 'No videos selected'];
            return back()->withNotify($notify);
        }

        $videos = Video::whereIn('id', $ids)->get();

        foreach ($videos as $video) {
            if ($action == 'delete') {
                $video->deleteWithAssets();
            } elseif ($action == 'featured') {
                $video->is_featured = 1;
                $video->save();
            } elseif ($action == 'unfeatured') {
                $video->is_featured = 0;
                $video->save();
            } elseif ($action == 'trending') {
                $video->is_trending = 1;
                $video->save();
            } elseif ($action == 'untrending') {
                $video->is_trending = 0;
                $video->save();
            } elseif ($action == 'approve') {
                $video->moderation_status = 'approved';
                $video->save();
            } elseif ($action == 'reject') {
                $video->moderation_status = 'rejected';
                $video->save();
            }
        }

        $notify[] = ['success', 'Bulk action executed successfully'];
        return back()->withNotify($notify);
    }
}
