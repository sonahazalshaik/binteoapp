<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\Reel;
use App\Models\ReelHashtag;
use App\Models\ReelMention;
use App\Models\ReelMusic;
use App\Models\ReelTag;
use App\Models\SavedAudio;
use App\Models\User;
use App\Services\BunnyStreamService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ReelService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function reelQuery(?string $scope = null)
    {
        $query = $scope ? Reel::$scope() : Reel::query();
        return $query->searchable(['title', 'user:username'])
            ->with('user', 'tags', 'music', 'category')
            ->filter(['status', 'visibility'])
            ->sortFilter('id', 'desc');
    }

    public function getDuets(int $perPage = 15)
    {
        return Reel::where('is_duet', true)
            ->searchable(['title', 'user:username'])
            ->with('user', 'tags', 'music', 'category')
            ->filter(['status', 'visibility'])
            ->sortFilter('id', 'desc')
            ->paginate($perPage);
    }

    public function createReel(array $data, ?array $files = []): Reel
    {
        $reel = new Reel();
        $reel->user_id = $data['user_id'];
        $reel->title = $data['title'];
        $reel->slug = Str::slug($data['title']) . '-' . Str::random(6);
        $reel->description = $data['description'] ?? null;
        $reel->category_id = $data['category_id'] ?? null;
        $reel->visibility = $data['visibility'] ?? 1;
        $reel->status = $data['status'] ?? Status::PUBLISHED;
        $reel->location = $data['location'] ?? null;
        $reel->is_age_restricted = $data['is_age_restricted'] ?? false;
        $reel->is_trending = $data['is_trending'] ?? false;
        $reel->music_source = $data['music_source'] ?? null;
        $reel->music_start_time = $data['music_start_time'] ?? 0;
        $reel->original_reel_id = $data['original_reel_id'] ?? null;
        $reel->language = $data['language'] ?? null;
        $reel->music_volume = $data['music_volume'] ?? 1.0;
        $reel->mic_volume = $data['mic_volume'] ?? 1.0;
        $reel->duration = $data['duration'] ?? 0;

        if (in_array($data['music_source'] ?? null, ['global', 'original'])) {
            $reel->global_music_url = $data['global_music_url'] ?? null;
            $reel->global_music_title = $data['global_music_title'] ?? null;
            $reel->global_music_artist = $data['global_music_artist'] ?? null;
            $reel->global_music_thumbnail = $data['global_music_thumbnail'] ?? null;
            $reel->audio_name = $data['global_music_title'] ?? null;
        }

        if (isset($files['video'])) {
            $reel->video_path = fileUploader($files['video'], getFilePath('reel'));
        }
        if (isset($files['thumbnail'])) {
            $reel->thumbnail_path = fileUploader($files['thumbnail'], getFilePath('reelThumbnail'), getFileSize('reelThumbnail'));
        }

        if (isset($data['music_id'])) {
            $reel->music_id = $data['music_id'];
            ReelMusic::where('id', $data['music_id'])->increment('usage_count');
        } elseif (isset($files['music_file'])) {
            $reel->audio_path = fileUploader($files['music_file'], getFilePath('reelMusic'));
            $reel->audio_name = $data['audio_name'] ?? $files['music_file']->getClientOriginalName();
        }

        $reel->save();

        $this->syncHashtags($reel, $data['description'] ?? '');
        $this->syncMentions($reel, $data['description'] ?? '');
        $this->syncTags($reel, $data['tags'] ?? []);

        // Upload to Bunny CDN
        if (isset($files['video'])) {
            try {
                $bunny = app(BunnyStreamService::class);
                $bunny->useReelLibrary();
                $bunnyResponse = $bunny->createVideo('Reel: ' . $data['title']);
                $bunnyId = $bunnyResponse['guid'];

                $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
                $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');

                $localPath = public_path(getFilePath('reel') . '/' . $reel->video_path);
                if (file_exists($localPath)) {
                    set_time_limit(0);
                    $fileContent = file_get_contents($localPath);
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'AccessKey' => $apiKey,
                        'Content-Type' => 'application/octet-stream',
                    ])->timeout(3600)->withBody($fileContent, 'application/octet-stream')
                      ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$bunnyId}");

                    if ($response->successful()) {
                        $reel->update(['bunny_id' => $bunnyId, 'bunny_status' => 'processing']);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Admin: Bunny CDN upload failed for reel #' . $reel->id . ': ' . $e->getMessage());
            }
        }

        return $reel;
    }

    public function updateReel(Reel $reel, array $data, ?array $files = []): Reel
    {
        $reel->title = $data['title'] ?? $reel->title;
        $reel->slug = $data['slug'] ?? $reel->slug;
        $reel->description = $data['description'] ?? $reel->description;
        $reel->category_id = $data['category_id'] ?? $reel->category_id;
        $reel->visibility = $data['visibility'] ?? $reel->visibility;
        $reel->status = $data['status'] ?? $reel->status;
        $reel->is_age_restricted = $data['is_age_restricted'] ?? $reel->is_age_restricted;
        $reel->is_trending = $data['is_trending'] ?? $reel->is_trending;
        $reel->allow_comments = $data['allow_comments'] ?? $reel->allow_comments;
        $reel->music_source = $data['music_source'] ?? $reel->music_source;
        $reel->music_start_time = $data['music_start_time'] ?? $reel->music_start_time;
        $reel->original_reel_id = $data['original_reel_id'] ?? $reel->original_reel_id;
        $reel->language = $data['language'] ?? $reel->language;

        if (isset($data['music_volume'])) $reel->music_volume = $data['music_volume'];
        if (isset($data['mic_volume'])) $reel->mic_volume = $data['mic_volume'];

        if (in_array($data['music_source'] ?? null, ['global', 'original'])) {
            $reel->global_music_url = $data['global_music_url'] ?? $reel->global_music_url;
            $reel->global_music_title = $data['global_music_title'] ?? $reel->global_music_title;
            $reel->global_music_artist = $data['global_music_artist'] ?? $reel->global_music_artist;
            $reel->global_music_thumbnail = $data['global_music_thumbnail'] ?? $reel->global_music_thumbnail;
            $reel->audio_name = $data['global_music_title'] ?? $reel->audio_name;
        }

        if (isset($files['thumbnail'])) {
            $reel->thumbnail_path = fileUploader($files['thumbnail'], getFilePath('reelThumbnail'), getFileSize('reelThumbnail'), $reel->thumbnail_path);
        }
        if (isset($data['music_id'])) {
            $reel->music_id = $data['music_id'];
        }

        $reel->save();

        $this->syncBunnyReel($reel, ['title' => $reel->title]);
        $this->syncTags($reel, $data['tags'] ?? []);
        $this->syncHashtags($reel, $data['description'] ?? '', $data['hashtags'] ?? []);
        $this->syncMentions($reel, $data['description'] ?? '');

        return $reel;
    }

    public function approveReel(Reel $reel): void
    {
        $reel->status = Status::PUBLISHED;
        $reel->save();
    }

    public function rejectReel(Reel $reel): void
    {
        $reel->status = Status::REJECTED;
        $reel->save();
    }

    public function toggleTrending(Reel $reel): string
    {
        $reel->is_trending = !$reel->is_trending;
        $reel->save();

        return $reel->is_trending ? 'marked as trending' : 'removed from trending';
    }

    public function deleteReel(Reel $reel): void
    {
        $reel->deleteWithAssets();
    }

    public function deleteThumbnail(Reel $reel): bool
    {
        if (!$reel->thumbnail_path) {
            return false;
        }

        $thumbPath = public_path(getFilePath('reelThumbnail') . '/' . $reel->thumbnail_path);
        if (file_exists($thumbPath)) {
            @unlink($thumbPath);
        }

        $reel->thumbnail_path = null;
        $reel->save();

        return true;
    }

    public function bulkAction(array $ids, string $action): void
    {
        $reels = Reel::whereIn('id', $ids)->get();

        foreach ($reels as $reel) {
            match ($action) {
                'delete' => $reel->deleteWithAssets(),
                'trending' => $reel->update(['is_trending' => 1]),
                'untrending' => $reel->update(['is_trending' => 0]),
                'approve' => $reel->update(['status' => Status::PUBLISHED]),
                'reject' => $reel->update(['status' => Status::REJECTED]),
                default => null,
            };
        }
    }

    public function getSavedAudios(int $perPage = 15)
    {
        return SavedAudio::with(['user', 'reelMusic'])
            ->searchable(['user:username', 'reelMusic:title'])
            ->latest()
            ->paginate($perPage);
    }

    public function deleteSavedAudio(int $id): void
    {
        SavedAudio::findOrFail($id)->delete();
    }

    protected function syncHashtags(Reel $reel, string $description, array $inputHashtags = []): void
    {
        $reel->hashtags()->delete();

        $hashtags = Reel::extractHashtags($description);

        foreach ($inputHashtags as $tag) {
            $tag = ltrim(trim($tag), '#');
            if ($tag && !in_array($tag, $hashtags)) {
                $hashtags[] = $tag;
            }
        }

        foreach ($hashtags as $tag) {
            ReelHashtag::create(['reel_id' => $reel->id, 'hashtag' => $tag]);
        }
    }

    protected function syncMentions(Reel $reel, string $description): void
    {
        $reel->mentions()->where('source', 'description')->delete();

        foreach (Reel::extractMentions($description) as $username) {
            $mentioned = User::where('username', $username)->first();
            if ($mentioned) {
                ReelMention::create([
                    'reel_id' => $reel->id,
                    'mentioned_user_id' => $mentioned->id,
                    'source' => 'description',
                ]);
            }
        }
    }

    protected function syncTags(Reel $reel, array $tags): void
    {
        $reel->tags()->delete();

        foreach ($tags as $tag) {
            ReelTag::create(['reel_id' => $reel->id, 'tag' => $tag]);
        }
    }

    protected function syncBunnyReel(Reel $reel, array $data): void
    {
        if ($reel->isBunnyReel()) {
            try {
                app(BunnyStreamService::class)->useReelLibrary()->updateVideo($reel->bunny_id, $data);
            } catch (\Exception $e) {
                \Log::warning('Failed to update reel in Bunny Stream: ' . $e->getMessage());
            }
        }
    }

    protected function deleteBunnyReel(Reel $reel): void
    {
        if ($reel->isBunnyReel()) {
            try {
                app(BunnyStreamService::class)->useReelLibrary()->deleteVideo($reel->bunny_id);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete reel from Bunny Stream: ' . $e->getMessage());
            }
        }
    }

    protected function deleteReelFiles(Reel $reel): void
    {
        $videoPath = public_path(getFilePath('reel') . '/' . $reel->video_path);
        if (file_exists($videoPath)) @unlink($videoPath);

        if ($reel->compressed_video_path) {
            $compressedPath = public_path(getFilePath('reel') . '/' . $reel->compressed_video_path);
            if (file_exists($compressedPath)) @unlink($compressedPath);
        }

        if ($reel->thumbnail_path) {
            $thumbPath = public_path(getFilePath('reelThumbnail') . '/' . $reel->thumbnail_path);
            if (file_exists($thumbPath)) @unlink($thumbPath);
        }

        if ($reel->audio_path) {
            $audioPath = public_path(getFilePath('reelMusic') . '/' . $reel->audio_path);
            if (file_exists($audioPath)) @unlink($audioPath);
        }
    }

    protected function getAllDates(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }

    protected function getAllMonths(string $startDate, string $endDate): array
    {
        $months = [];
        $current = \Carbon\Carbon::parse($startDate)->startOfMonth();
        $end = \Carbon\Carbon::parse($endDate)->startOfMonth();

        while ($current->lte($end)) {
            $months[] = $current->format('Y-m');
            $current->addMonth();
        }

        return $months;
    }
}
