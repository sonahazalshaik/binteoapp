<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Reel;
use App\Models\ReelMusic;
use App\Models\ReelHashtag;
use App\Models\ReelTag;
use App\Models\ReelMention;
use App\Models\User;
use App\Rules\FileTypeValidate;
use App\Services\Admin\ReelService;
use App\Services\BunnyStorageAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ManageReelController extends Controller
{
    protected $service;

    public function __construct(ReelService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'All Reels';
        $reels     = $this->reelData();
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function published()
    {
        $pageTitle = 'Published Reels';
        $reels     = $this->reelData('published');
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function draft()
    {
        $pageTitle = 'Draft Reels';
        $reels     = $this->reelData('draft');
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Reels';
        $reels     = $this->reelData('rejected');
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function trending()
    {
        $pageTitle = 'Trending Reels';
        $reels     = $this->reelData('trending');
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function liked()
    {
        $pageTitle = 'Liked Reels';
        $reels     = $this->reelData('liked');
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    public function duets()
    {
        $pageTitle = 'Duets & Remixes';
        $reels     = $this->service->getDuets(getPaginate());
        return view('admin.reels.index', compact('pageTitle', 'reels'));
    }

    protected function reelData($scope = null)
    {
        return $this->service->reelQuery($scope)->paginate(getPaginate());
    }

    public function create()
    {
        $pageTitle  = 'Upload New Reel';
        $categories = Category::active()->get();
        $users      = User::active()->orderBy('username')->get();
        $musicTracks = ReelMusic::active()->latest()->get();
        return view('admin.reels.create', compact('pageTitle', 'categories', 'users', 'musicTracks'));
    }

    // === ADMIN REEL TUS/S3 flow — mirrors UploadService::prepareReelUpload but for selected creator ===

    public function prepareUpload(Request $request)
    {
        $request->validate([
            'user'               => 'required|exists:users,id',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string|max:2200',
            'category_id'        => 'nullable|exists:categories,id',
            'visibility'         => 'nullable|in:0,1',
            'location'           => 'nullable|string|max:255',
            'duration'           => 'nullable|string',
            'language'           => 'nullable|string|max:255',
            'music_id'           => 'nullable',
            'music_source'       => 'nullable|string|max:20',
            'music_start_time'   => 'nullable|numeric|min:0',
            'original_reel_id'   => 'nullable',
            'parent_id'          => 'nullable',
            'music_volume'       => 'nullable|numeric',
            'mic_volume'         => 'nullable|numeric',
            'global_music_url'   => 'nullable|string',
            'global_music_title' => 'nullable|string',
            'global_music_artist'=> 'nullable|string',
            'global_music_thumbnail' => 'nullable|string',
            'is_age_restricted'  => 'nullable',
            'allow_comments'     => 'nullable',
            'allow_duet'         => 'nullable',
            'allow_stitch'       => 'nullable',
            'status'             => 'nullable',
        ]);

        $targetUser = User::findOrFail($request->input('user'));

        Log::info('Admin Reel: Preparing Reel Upload', ['admin_id'=>auth()->guard('admin')->id(), 'target_user_id'=>$targetUser->id, 'title'=>$request->title]);

        $bunnyId = null;
        $slug = Str::slug($request->title);
        if (Reel::where('slug', $slug)->exists()) $slug = $slug.'-'.Str::random(6);

        $reel = new Reel();
        $reel->user_id = $targetUser->id;
        $reel->title = $request->title;
        $reel->slug = $slug;
        $reel->description = $request->description;
        $reel->video_path = '';
        $reel->bunny_id = $bunnyId;
        $reel->bunny_status = null;
        $reel->compression_status = 0;
        $reel->status = $request->input('status') == '0' || $request->boolean('is_draft') ? \App\Constants\Status::DRAFT : \App\Constants\Status::PUBLISHED;
        $reel->visibility = $request->visibility ?? \App\Constants\Status::PUBLIC;
        $reel->location = $request->location;
        $reel->language = $request->language;
        $reel->category_id = $request->category_id;
        $reel->duration = $request->duration;
        $reel->allow_comments = $request->has('allow_comments') ? $request->boolean('allow_comments') : true;
        $reel->is_age_restricted = $request->has('is_age_restricted') ? $request->boolean('is_age_restricted') : false;
        $reel->allow_duet = $request->has('allow_duet') ? $request->boolean('allow_duet') : true;
        $reel->allow_stitch = $request->has('allow_stitch') ? $request->boolean('allow_stitch') : true;

        if (in_array($request->music_source, ['global','original','upload'])) $reel->music_id = null;
        else {
            $reel->music_id = null;
            if ($request->music_id) {
                $m = ReelMusic::where('id',$request->music_id)->orWhere('slug',$request->music_id)->first();
                if ($m) { $reel->music_id = $m->id; $m->increment('usage_count'); }
            }
        }
        $reel->music_source = $request->music_source;
        $reel->music_start_time = $request->music_start_time ?? 0;

        if ($request->parent_id) {
            $pr = Reel::where('id',$request->parent_id)->orWhere('slug',$request->parent_id)->first();
            $reel->parent_id = $pr ? $pr->id : null;
        }
        $reel->is_duet = $reel->parent_id ? 1 : 0;

        if ($request->original_reel_id) {
            $or = Reel::where('id',$request->original_reel_id)->orWhere('slug',$request->original_reel_id)->first();
            $reel->original_reel_id = $or ? $or->id : null;
        }

        $reel->music_volume = $request->music_volume ?? 1.0;
        $reel->mic_volume = $request->mic_volume ?? 1.0;

        if (in_array($request->music_source, ['global','original'])) {
            $reel->global_music_url = $request->global_music_url;
            $reel->global_music_title = $request->global_music_title;
            $reel->global_music_artist = $request->global_music_artist;
            $thumbnail = $request->global_music_thumbnail;
            $reel->global_music_thumbnail = $thumbnail ? strtok($thumbnail,'?') : null;
            $reel->audio_name = $request->global_music_title;
        } elseif ($request->music_source=='upload' && $request->hasFile('music_file')) {
            try { $audioPath = fileUploader($request->music_file, getFilePath('reelMusic')); $reel->audio_path=$audioPath; $reel->audio_name=$request->audio_name ?? $request->file('music_file')->getClientOriginalName(); } catch (\Exception $e) { Log::error('Admin Reel: Could not upload audio '.$e->getMessage()); }
        }

        $reel->save();

        $hashtags = Reel::extractHashtags($request->description);
        if ($request->has('hashtags')) {
            $inputHashtags = is_array($request->hashtags) ? $request->hashtags : json_decode($request->hashtags,true);
            if (is_array($inputHashtags)) {
                foreach ($inputHashtags as $tag) { $tag=ltrim(trim($tag),'#'); if ($tag && !in_array($tag,$hashtags)) $hashtags[]=$tag; }
            }
        }
        foreach ($hashtags as $tag) { ReelHashtag::create(['reel_id'=>$reel->id,'hashtag'=>$tag]); ReelTag::create(['reel_id'=>$reel->id,'tag'=>$tag]); }
        foreach (Reel::extractMentions($request->description) as $username) {
            $mentionedUser = User::where('username',$username)->first();
            if ($mentionedUser) ReelMention::create(['reel_id'=>$reel->id,'mentioned_user_id'=>$mentionedUser->id,'source'=>'description']);
        }

        $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');
        $apiKey = app(\App\Services\BunnyStreamService::class)->getApiKey();
        $tusParams = ['endpoint'=>'https://video.bunnycdn.com/tusupload','headers'=>['LibraryId'=>$libraryId,'AccessKey'=>$apiKey]];
        $directUrl = "";

        return response()->json([
            'success'=>true,
            'reel_id'=>$reel->id,
            'reel_slug'=>$reel->slug,
            'bunny_id'=>$bunnyId,
            'transport'=>'direct',
            'tus'=>$tusParams,
            'direct'=>['url'=>$directUrl],
        ]);
    }

    public function presign(Request $request, $reel, BunnyStorageAuthService $authService)
    {
        $request->validate(['operation'=>'required|in:upload,final']);
        $reel = Reel::withoutGlobalScopes()->where('slug',$reel)->orWhere('id',$reel)->firstOrFail();
        $operation = $request->input('operation');
        $userId = (int)$reel->user_id;
        $reelId = (int)$reel->id;
        $type = $operation==='final' ? 'final' : 'source';
        $storagePath = $authService->generateStoragePath($userId,$reelId,$type);
        if ($operation==='upload' && empty($reel->storage_path)) $reel->update(['video_path'=>$storagePath]);
        try { $presign = $authService->generatePresignedPutUrl($storagePath,300); } catch (\Throwable $e) { Log::channel('reels')->error('[ADMIN-S3-PRESIGN] failed',['reel_id'=>$reelId,'error'=>$e->getMessage()]); return response()->json(['error'=>'Presign failed: '.$e->getMessage()],500); }
        Log::channel('reels')->info('[ADMIN-S3-PRESIGN] generated',['reel_id'=>$reelId,'user_id'=>$userId,'operation'=>$operation,'path'=>$storagePath]);
        return response()->json(['success'=>true,'reel_id'=>$reelId,'reel_slug'=>$reel->slug,'storage_path'=>$storagePath,'presignedUrl'=>$presign['presignedUrl'],'expiry'=>$presign['expiry'],'operation'=>$operation]);
    }

    public function confirm(Request $request, $reel, BunnyStorageAuthService $authService)
    {
        $request->validate(['storage_path'=>'required|string|max:255']);
        $reel = Reel::withoutGlobalScopes()->where('slug',$reel)->orWhere('id',$reel)->firstOrFail();
        $storagePath = $request->input('storage_path');
        $userId = (int)$reel->user_id;
        if (!$authService->isValidPath($storagePath,$userId)) return response()->json(['error'=>'Invalid storage path'],422);
        if (!str_starts_with($storagePath, "reels/".$userId."/".$reel->id."/")) return response()->json(['error'=>'Path does not match reel'],422);
        $exists = $this->verifyBunnyStorageFileAdmin($storagePath);
        if (!$exists) return response()->json(['error'=>'File not found on storage - upload may have failed'],404);
        $reel->update(['storage_path'=>$storagePath,'bunny_status'=>'ready','compression_status'=>2,'is_compressed'=>true,'status'=>$reel->status===\App\Constants\Status::DRAFT ? $reel->status : \App\Constants\Status::PUBLISHED]);
        Log::channel('reels')->info('[ADMIN-WORKER-AUTH] confirm SUCCESS',['reel_id'=>$reel->id,'path'=>$storagePath]);
        return response()->json(['success'=>true,'reel_slug'=>$reel->slug,'storage_path'=>$storagePath,'message'=>'Reel confirmed and ready']);
    }

    private function verifyBunnyStorageFileAdmin(string $storagePath): bool
    {
        $storageZone = gs('bunny_reels_storage_zone') ?: config('bunny.reels_storage_zone') ?: config('bunny.storage_zone');
        $accessKey = gs('bunny_reels_storage_access_key') ?: config('bunny.reels_storage_access_key') ?: config('bunny.storage_access_key');
        $region = gs('bunny_reels_storage_region') ?: config('bunny.reels_storage_region') ?: config('bunny.storage_region');
        if (empty($storageZone) || empty($accessKey)) return false;
        $regionClean = strtolower(trim($region ?? ''));
        $host = (empty($regionClean) || in_array($regionClean,['de','main','falkenstein'])) ? 'storage.bunnycdn.com' : "{$regionClean}.storage.bunnycdn.com";
        $parts = explode('/',$storagePath); $filename=array_pop($parts); $dirPath=implode('/',$parts); $dirUrl="https://{$host}/{$storageZone}/{$dirPath}/";
        for ($attempt=1;$attempt<=3;$attempt++) {
            try {
                $response = Http::withHeaders(['AccessKey'=>$accessKey,'Accept'=>'application/json'])->timeout(10)->withOptions(['verify'=>false])->get($dirUrl);
                if ($response->successful()) {
                    $files=$response->json(); if (is_array($files)) foreach ($files as $fileObj) if (isset($fileObj['ObjectName']) && $fileObj['ObjectName']===$filename) return true;
                }
            } catch (\Throwable $e) {}
            if ($attempt<3) sleep(5);
        }
        return false;
    }

    public function uploadThumbnail(Request $request, $reel)
    {
        $request->validate(['thumbnail'=>'required|image|max:20480']);
        $reel = Reel::withoutGlobalScopes()->where('slug',$reel)->orWhere('id',$reel)->firstOrFail();
        $thumbnailPath = fileUploader($request->file('thumbnail'), getFilePath('reelThumbnail'));
        $reel->update(['thumbnail_path'=>$thumbnailPath]);
        Log::channel('reels')->info('[ADMIN-REEL-THUMB] uploaded',['reel_id'=>$reel->id,'path'=>$thumbnailPath]);
        return response()->json(['success'=>true,'thumbnail_url'=>asset(getFilePath('reelThumbnail').'/'.$thumbnailPath)]);
    }

    public function directUpload(Request $request)
    {
        $request->validate(['video'=>'required|file','reel_id'=>'required|integer']);
        $reel = Reel::withoutGlobalScopes()->where('id',$request->reel_id)->first();
        if (!$reel) return response()->json(['success'=>false,'message'=>'Reel not found'],404);
        try {
            $tempDir = storage_path("app/reels/temp/{$reel->id}"); if (!file_exists($tempDir)) mkdir($tempDir,0755,true);
            $file = $request->file('video'); $file->move($tempDir,'source.mp4');
            $videoPath = "reels/temp/{$reel->id}/source.mp4";
            $reel->update(['video_path'=>$videoPath,'bunny_status'=>null,'compression_status'=>0]);
            \App\Jobs\CompressReel::dispatch($reel);
            return response()->json(['success'=>true,'message'=>'Reel uploaded successfully and queued for processing.']);
        } catch (\Exception $e) {
            Log::error('[ADMIN-REEL-TEMP] cPanel upload failed',['reel_id'=>$reel->id,'error'=>$e->getMessage()]);
            return response()->json(['success'=>false,'message'=>'Upload error: '.$e->getMessage()],500);
        }
    }

    public function logError(Request $request)
    {
        $request->validate(['error_message'=>'required|string','type'=>'required|string|in:video,reel','details'=>'nullable|array']);
        Log::error("TUS Upload Error ({$request->type}) [ADMIN REEL]: {$request->error_message}", ['admin_id'=>auth()->guard('admin')->id(),'details'=>$request->details,'ip'=>$request->ip(),'user_agent'=>$request->userAgent()]);
        if ($request->type==='reel') Log::channel('reels')->error('[ADMIN-REEL-WORKER] fallback/error',['error'=>$request->error_message,'details'=>$request->details,'admin_id'=>auth()->guard('admin')->id()]);
        return response()->json(['success'=>true]);
    }

    public function wasmStatus(Request $request)
    {
        $wasmSupported = $request->boolean('wasmSupported');
        $crossOriginIsolated = $request->boolean('crossOriginIsolated');
        $hasSharedArrayBuffer = $request->boolean('hasSharedArrayBuffer');
        Log::channel('reels')->info('[ADMIN-WASM-STATUS] ffmpeg.wasm check',['wasmSupported'=>$wasmSupported,'crossOriginIsolated'=>$crossOriginIsolated,'hasSharedArrayBuffer'=>$hasSharedArrayBuffer,'admin_id'=>auth()->guard('admin')->id(),'ip'=>$request->ip()]);
        return response()->json(['success'=>true,'implemented'=>false,'wasmSupported'=>$wasmSupported]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user'             => 'required|exists:users,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:2200',
            'video'            => ['required', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
            'thumbnail'        => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'category_id'      => 'nullable|exists:categories,id',
            'music_id'         => 'nullable|exists:reel_music,id',
            'music_file'       => ['nullable', 'file', 'mimes:mp3,wav,aac,m4a,mp4,ogg,m4b,3gp,mpeg', 'max:10240'],
            'audio_name'       => 'nullable|string|max:255',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:100',
            'visibility'       => 'required|in:0,1',
            'status'           => 'required|in:0,1,2',
            'location'         => 'nullable|string|max:255',
            'is_age_restricted' => 'nullable|boolean',
            'is_trending'      => 'nullable|boolean',
            'music_source'     => 'nullable|string|max:20',
            'music_start_time' => 'nullable|numeric|min:0',
            'original_reel_id' => 'nullable|exists:reels,id',
            'duration'         => 'nullable|numeric|min:0',
            'global_music_url' => 'nullable|string',
            'global_music_title' => 'nullable|string',
            'global_music_artist' => 'nullable|string',
            'global_music_thumbnail' => 'nullable|string',
            'language'               => 'nullable|string|max:255',
        ]);

        $data = [
            'user_id' => $request->user,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'visibility' => $request->visibility,
            'status' => $request->status,
            'location' => $request->location,
            'is_age_restricted' => $request->boolean('is_age_restricted'),
            'is_trending' => $request->boolean('is_trending'),
            'music_source' => $request->music_source,
            'music_start_time' => $request->music_start_time ?? 0,
            'original_reel_id' => $request->original_reel_id,
            'language' => $request->language,
            'music_volume' => $request->input('music_volume', 1.0),
            'mic_volume' => $request->input('mic_volume', 1.0),
            'duration' => $request->input('duration', 0),
            'global_music_url' => $request->global_music_url,
            'global_music_title' => $request->global_music_title,
            'global_music_artist' => $request->global_music_artist,
            'global_music_thumbnail' => $request->global_music_thumbnail,
            'music_id' => $request->music_id,
            'audio_name' => $request->audio_name,
            'tags' => $request->tags,
        ];

        $files = [];
        if ($request->hasFile('video')) $files['video'] = $request->file('video');
        if ($request->hasFile('thumbnail')) $files['thumbnail'] = $request->file('thumbnail');
        if ($request->hasFile('music_file')) $files['music_file'] = $request->file('music_file');

        try {
            $reel = $this->service->createReel($data, $files);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Something went wrong while uploading the reel'];
            return back()->withNotify($notify);
        }

        if (class_exists('App\Jobs\CompressReel')) {
            \App\Jobs\CompressReel::dispatch($reel);
        }

        $notify[] = ['success', 'Reel uploaded successfully'];
        return to_route('admin.reels.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $reel       = Reel::with('user', 'tags', 'hashtags', 'music', 'category')->where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $categories = Category::active()->get();
        $musicTracks = ReelMusic::active()->latest()->get();
        $pageTitle  = 'Edit - ' . $reel->title;
        return view('admin.reels.edit', compact('pageTitle', 'reel', 'categories', 'musicTracks'));
    }

    public function update(Request $request, $id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:2200',
            'slug'             => 'required|string|unique:reels,slug,' . $reel->id,
            'thumbnail'        => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'category_id'      => 'nullable|exists:categories,id',
            'music_id'         => 'nullable|exists:reel_music,id',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:100',
            'hashtags'         => 'nullable|array',
            'hashtags.*'       => 'string|max:100',
            'visibility'       => 'required|in:0,1',
            'status'           => 'required|in:0,1,2',
            'is_age_restricted' => 'nullable|boolean',
            'is_trending'      => 'nullable|boolean',
            'allow_comments'   => 'nullable|boolean',
            'music_source'     => 'nullable|string|max:20',
            'music_start_time' => 'nullable|numeric|min:0',
            'original_reel_id' => 'nullable|exists:reels,id',
            'global_music_url' => 'nullable|string',
            'global_music_title' => 'nullable|string',
            'global_music_artist' => 'nullable|string',
            'global_music_thumbnail' => 'nullable|string',
            'language'               => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'visibility' => $request->visibility,
            'status' => $request->status,
            'is_age_restricted' => $request->boolean('is_age_restricted'),
            'is_trending' => $request->boolean('is_trending'),
            'allow_comments' => $request->boolean('allow_comments'),
            'music_source' => $request->music_source,
            'music_start_time' => $request->music_start_time ?? 0,
            'original_reel_id' => $request->original_reel_id,
            'language' => $request->language,
            'global_music_url' => $request->global_music_url,
            'global_music_title' => $request->global_music_title,
            'global_music_artist' => $request->global_music_artist,
            'global_music_thumbnail' => $request->global_music_thumbnail,
            'music_id' => $request->music_id,
            'tags' => $request->tags,
            'hashtags' => $request->input('hashtags', []),
        ];

        if ($request->has('music_volume')) $data['music_volume'] = $request->music_volume;
        if ($request->has('mic_volume')) $data['mic_volume'] = $request->mic_volume;

        $files = [];
        if ($request->hasFile('thumbnail')) $files['thumbnail'] = $request->file('thumbnail');

        $this->service->updateReel($reel, $data, $files);

        $notify[] = ['success', 'Reel updated successfully'];
        return back()->withNotify($notify);
    }

    public function approve($id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $this->service->approveReel($reel);

        $notify[] = ['success', 'Reel approved and published'];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $this->service->rejectReel($reel);

        $notify[] = ['success', 'Reel rejected'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $this->service->deleteReel($reel);

        $notify[] = ['success', 'Reel deleted permanently'];
        return back()->withNotify($notify);
    }

    public function toggleTrending($id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();
        $status = $this->service->toggleTrending($reel);

        $notify[] = ['success', "Reel {$status}"];
        return back()->withNotify($notify);
    }

    public function deleteThumbnail($id)
    {
        $reel = Reel::where('slug', $id)->orWhere('id', $id)->firstOrFail();

        if ($this->service->deleteThumbnail($reel)) {
            $notify[] = ['success', 'Thumbnail deleted successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'No thumbnail found to delete'];
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
            $notify[] = ['error', 'No reels selected'];
            return back()->withNotify($notify);
        }

        $this->service->bulkAction($ids, $action);

        $notify[] = ['success', 'Bulk action executed successfully'];
        return back()->withNotify($notify);
    }
}
