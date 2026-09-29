<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\AdvertisementAnalytics;
use App\Models\BannerAd;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Impression;
use App\Models\Like;
use App\Models\Playlist;
use App\Models\Subtitle;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCommission;
use App\Models\VideoProcessingJob;
use App\Models\VideoProcessingOption;
use App\Models\VideoResolution;
use App\Models\VideoTag;
use App\Models\ViewLog;
use App\Services\BunnyStreamService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VideoService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function createVideo(array $data, ?array $files = []): Video
    {
        $video = new Video();
        $video->user_id = $data['user_id'];
        $video->title = $data['title'];
        $video->slug = Str::slug($data['title']) . '-' . Str::random(5);
        $video->description = $data['description'] ?? null;
        $video->category_id = $data['category_id'] ?? null;
        $video->visibility = $data['visibility'] ?? 'public';
        $video->is_premium = $data['is_premium'] ?? 0;
        $video->price = $data['price'] ?? 0;
        $video->language = $data['language'] ?? null;
        $video->location = $data['location'] ?? null;
        $video->duration = $data['duration'] ?? null;
        $video->status = $data['status'] ?? Status::VIDEO_PROCESSING;
        $video->is_age_restricted = $data['is_age_restricted'] ?? 0;
        $video->audience = $data['audience'] ?? 0;
        $video->stock_video = $data['stock_video'] ?? 0;

        if (isset($data['scheduled_at'])) {
            $video->scheduled_at = $data['scheduled_at'];
        }

        if (isset($files['video'])) {
            $video->video_path = fileUploader($files['video'], getFilePath('video'));
        }

        if (isset($files['thumb_image'])) {
            $video->thumb_image = fileUploader($files['thumb_image'], getFilePath('thumbnail'), getFileSize('thumbnail'), null, getFileThumb('thumbnail'));
        }

        if (isset($files['thumbnail'])) {
            $video->thumbnail_path = fileUploader($files['thumbnail'], getFilePath('thumbnail'));
        }

        $video->save();

        if (isset($data['category_ids'])) {
            $video->categories()->attach($data['category_ids']);
        } elseif (isset($data['category_id'])) {
            $video->categories()->attach($data['category_id']);
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $videoTag = new VideoTag();
                $videoTag->video_id = $video->id;
                $videoTag->tag = $tag;
                $videoTag->save();
            }
        }

        return $video;
    }

    public function updateVideo(Video $video, array $data, ?array $files = []): Video
    {
        $video->title = $data['title'] ?? $video->title;
        $video->description = $data['description'] ?? $video->description;
        $video->category_id = $data['category_id'] ?? $video->category_id;
        $video->visibility = $data['visibility'] ?? $video->visibility;
        $video->is_premium = $data['is_premium'] ?? $video->is_premium;
        $video->price = $data['price'] ?? $video->price;
        $video->language = $data['language'] ?? $video->language;
        $video->location = $data['location'] ?? $video->location;
        $video->duration = $data['duration'] ?? $video->duration;
        $video->status = $data['status'] ?? $video->status;
        $video->is_age_restricted = $data['is_age_restricted'] ?? $video->is_age_restricted;
        $video->audience = $data['audience'] ?? $video->audience;
        $video->stock_video = $data['stock_video'] ?? $video->stock_video;
        $video->is_featured = $data['is_featured'] ?? $video->is_featured;
        $video->is_trending = $data['is_trending'] ?? $video->is_trending;
        $video->user_id = $data['user_id'] ?? $video->user_id;

        if (isset($data['slug'])) {
            $video->slug = $data['slug'];
        }

        if (isset($data['scheduled_at'])) {
            $video->scheduled_at = $data['scheduled_at'];
        }

        if (isset($files['thumb_image'])) {
            $video->thumb_image = fileUploader($files['thumb_image'], getFilePath('thumbnail'), getFileSize('thumbnail'), $video->thumb_image, getFileThumb('thumbnail'));
        }

        if (isset($files['thumbnail'])) {
            $video->thumbnail_path = fileUploader($files['thumbnail'], getFilePath('thumbnail'), null, $video->thumbnail_path);
        }

        $video->save();

        if (isset($data['category_ids'])) {
            $video->categories()->sync($data['category_ids']);
        } elseif (isset($data['category_id'])) {
            $video->categories()->sync([$data['category_id']]);
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $video->tags()->delete();
            foreach ($data['tags'] as $tag) {
                $videoTag = new VideoTag();
                $videoTag->video_id = $video->id;
                $videoTag->tag = $tag;
                $videoTag->save();
            }
        }

        if (isset($files['subtitle_files']) && isset($data['captions']) && isset($data['language_codes'])) {
            $this->syncSubtitles($video, $files['subtitle_files'], $data['captions'], $data['language_codes'], $data['old_subtitle_ids'] ?? []);
        }

        $this->syncBunnyVideo($video, ['title' => $video->title]);

        return $video;
    }

    public function deleteVideo(Video $video): void
    {
        $video->deleteWithAssets();
    }

    public function toggleFeatured(Video $video): string
    {
        $video->is_featured = !$video->is_featured;
        $video->save();

        return $video->is_featured ? 'featured' : 'unfeatured';
    }

    public function toggleTrending(Video $video): string
    {
        $video->is_trending = !$video->is_trending;
        $video->save();

        return $video->is_trending ? 'trending' : 'normal';
    }

    public function toggleAgeRestriction(Video $video): string
    {
        $video->is_age_restricted = !$video->is_age_restricted;
        $video->save();

        return $video->is_age_restricted ? 'age restricted' : 'unrestricted';
    }

    public function approveVideo(Video $video): void
    {
        $video->moderation_status = 'approved';
        $video->save();
    }

    public function rejectVideo(Video $video): void
    {
        $video->moderation_status = 'rejected';
        $video->save();
    }

    public function bulkAction(array $ids, string $action): void
    {
        $videos = Video::whereIn('id', $ids)->get();

        foreach ($videos as $video) {
            match ($action) {
                'delete' => $this->deleteVideo($video),
                'featured' => $video->update(['is_featured' => 1]),
                'unfeatured' => $video->update(['is_featured' => 0]),
                'trending' => $video->update(['is_trending' => 1]),
                'untrending' => $video->update(['is_trending' => 0]),
                'approve' => $video->update(['moderation_status' => 'approved']),
                'reject' => $video->update(['moderation_status' => 'rejected']),
                default => null,
            };
        }
    }

    public function getVideoAnalytics(Video $video, string $startDate, string $endDate): array
    {
        $diffInDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        $dates = $groupBy === 'days'
            ? $this->getAllDates($startDate, $endDate)
            : $this->getAllMonths($startDate, $endDate);

        $totalViews = ViewLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)
            ->selectRaw('COUNT(*) AS views')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $totalLike = Like::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)
            ->selectRaw('COUNT(*) AS is_like')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $totalComment = Comment::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)
            ->selectRaw('COUNT(*) AS comment')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $clicks = AdvertisementAnalytics::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('SUM(click) AS click')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $impressions = AdvertisementAnalytics::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('SUM(impression) AS impression')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $earningFromAds = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)->where('remark', 'ads_revenue')
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $earningFromVideos = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)->where('remark', 'earn_from_video')
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $totalEarning = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('video_id', $video->id)->whereIn('remark', ['earn_from_video', 'ads_revenue'])
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->orderByDesc('created_on')->groupBy('created_on')->get();

        $data = collect($dates)->map(fn($date) => [
            'created_on' => showDateTime($date, 'd-M-y'),
            'total_views' => $totalViews->where('created_on', $date)->first()?->views ?? 0,
            'total_like' => $totalLike->where('created_on', $date)->first()?->is_like ?? 0,
            'total_comment' => $totalComment->where('created_on', $date)->first()?->comment ?? 0,
            'total_clicks' => $clicks->where('created_on', $date)->first()?->click ?? 0,
            'total_impressions' => $impressions->where('created_on', $date)->first()?->impression ?? 0,
            'total_ads_earning' => getAmount($earningFromAds->where('created_on', $date)->first()?->amount ?? 0),
            'total_purchased_earning' => getAmount($earningFromVideos->where('created_on', $date)->first()?->amount ?? 0),
            'totalEarning' => getAmount($totalEarning->where('created_on', $date)->first()?->amount ?? 0),
        ]);

        return [
            'report' => [
                'created_on' => $data->pluck('created_on'),
                'data' => [
                    ['name' => 'Clicks', 'data' => $data->pluck('total_clicks')],
                    ['name' => 'Impressions', 'data' => $data->pluck('total_impressions')],
                    ['name' => 'Ads', 'data' => $data->pluck('total_ads_earning')],
                    ['name' => 'Sales', 'data' => $data->pluck('total_purchased_earning')],
                    ['name' => 'Total', 'data' => $data->pluck('totalEarning')],
                ],
            ],
            'pieData' => [$data->sum('total_like'), 0, $data->sum('total_comment'), $data->sum('total_views')],
        ];
    }

    public function checkSlug(string $slug): bool
    {
        return Video::where('slug', $slug)->exists();
    }

    public function createProcessingOption(array $data): VideoProcessingOption
    {
        return VideoProcessingOption::create($data);
    }

    public function updateProcessingOption(VideoProcessingOption $option, array $data): VideoProcessingOption
    {
        $option->update($data);
        return $option;
    }

    public function toggleProcessingOption(VideoProcessingOption $option): string
    {
        $option->is_enabled = !$option->is_enabled;
        $option->save();

        return $option->is_enabled ? 'enabled' : 'disabled';
    }

    public function retryProcessingJob(VideoProcessingJob $job): void
    {
        $job->status = 'pending';
        $job->error_message = null;
        $job->started_at = null;
        $job->completed_at = null;
        $job->save();
    }

    public function deleteProcessingJob(VideoProcessingJob $job): void
    {
        $job->delete();
    }

    public function saveResolution(?int $id, array $data): VideoResolution
    {
        if ($id) {
            $resolution = VideoResolution::findOrFail($id);
            $resolution->update($data);
        } else {
            $resolution = VideoResolution::create($data);
        }

        return $resolution;
    }

    public function toggleResolutionStatus(int $id): mixed
    {
        return VideoResolution::changeStatus($id);
    }

    public function deleteResolution(int $id): void
    {
        $resolution = VideoResolution::findOrFail($id);
        $resolution->delete();
    }

    public function getVideoLikes(Video $video, int $perPage = 15)
    {
        return $video->likes()->with('user')->paginate($perPage);
    }

    public function deleteLike(int $id): void
    {
        Like::findOrFail($id)->delete();
    }

    public function getVideoComments(Video $video, int $perPage = 15)
    {
        return $video->allComments()->with('user')->paginate($perPage);
    }

    public function getVideoPlaylists(Video $video, int $perPage = 15)
    {
        return Playlist::whereHas('videos', fn($q) => $q->where('video_id', $video->id))
            ->with('user')->paginate($perPage);
    }

    public function removeVideoFromPlaylist(Video $video, int $playlistId): void
    {
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->videos()->detach($video->id);
    }

    public function getVideoCommissionList(int $perPage = 15)
    {
        return VideoCommission::with(['video', 'purchaser', 'creator'])->latest()->paginate($perPage);
    }

    public function getUserStatsForVideo(Video $video): array
    {
        return [
            'total_videos' => Video::where('user_id', $video->user_id)->count(),
            'total_likes' => Like::whereHas('video', fn($q) => $q->where('user_id', $video->user_id))->count(),
            'total_views' => Video::where('user_id', $video->user_id)->sum('views_count'),
            'subscribers' => \App\Models\Subscription::where('user_id', $video->user_id)->count(),
        ];
    }

    public function getVideosByScope(?string $scope = null, ?int $userId = null, int $perPage = 15)
    {
        $query = $scope ? Video::$scope() : Video::query();

        if ($userId) {
            $query = $query->where('user_id', $userId);
        }

        return $query
            ->searchable(['title', 'user:username', 'user:channel_name'])
            ->with('user', 'tags')
            ->filter(['status', 'visibility'])
            ->sortFilter('id', 'desc')
            ->paginate($perPage);
    }

    public function getVideoWatchLaterPlaylists(Video $video, int $perPage = 15)
    {
        return Playlist::where('name', 'Watch Later')
            ->whereHas('videos', fn($q) => $q->where('video_id', $video->id))
            ->with('user')
            ->paginate($perPage);
    }

    public function getActiveBannersForSlots(): array
    {
        $now = now();

        return [
            'slot1' => BannerAd::where('slot', 'slot1')->where('status', Status::ENABLE)
                ->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get(),
            'slot2' => BannerAd::where('slot', 'slot2')->where('status', Status::ENABLE)
                ->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get(),
        ];
    }

    protected function syncSubtitles(Video $video, array $files, array $captions, array $languageCodes, array $keepIds): void
    {
        $removeIds = $video->subtitles()->pluck('id')->diff($keepIds)->values()->toArray();

        $video->subtitles()->whereIn('id', $removeIds)->get()->each(function ($subtitle) {
            $filePath = getFilePath('subtitle') . '/' . $subtitle->file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $subtitle->delete();
        });

        foreach ($files as $key => $file) {
            $subtitle = new Subtitle();
            $subtitle->file = fileUploader($file, getFilePath('subtitle'));
            $subtitle->video_id = $video->id;
            $subtitle->caption = $captions[$key] ?? '';
            $subtitle->language_code = $languageCodes[$key] ?? '';
            $subtitle->save();
        }
    }

    protected function syncBunnyVideo(Video $video, array $data): void
    {
        if ($video->isBunnyVideo()) {
            try {
                app(BunnyStreamService::class)->updateVideo($video->bunny_id, $data);
            } catch (\Exception $e) {
                \Log::warning('Failed to update video in Bunny Stream: ' . $e->getMessage());
            }
        }
    }

    protected function deleteBunnyVideo(Video $video): void
    {
        if ($video->isBunnyVideo()) {
            try {
                app(BunnyStreamService::class)->deleteVideo($video->bunny_id);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete video from Bunny Stream: ' . $e->getMessage());
            }
        }
    }

    protected function getAllDates(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('d-F-Y');
            $current->addDay();
        }

        return $dates;
    }

    protected function getAllMonths(string $startDate, string $endDate): array
    {
        $months = [];
        $current = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->startOfMonth();

        while ($current->lte($end)) {
            $months[] = $current->format('F-Y');
            $current->addMonth();
        }

        return $months;
    }
}
