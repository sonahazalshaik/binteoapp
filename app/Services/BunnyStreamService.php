<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BunnyStreamService
{
    protected string $apiKey;
    protected string $libraryId;
    protected string $apiBase;
    protected string $tusEndpoint;

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getLibraryId(): string
    {
        return $this->libraryId;
    }

    public function __construct()
    {
        $this->apiKey           = gs('bunny_api_key') ?: config('bunny.api_key');
        $this->libraryId        = gs('bunny_video_library_id') ?: config('bunny.library_id');
        $this->videoCollectionId = gs('bunny_video_collection_id') ?: config('bunny.video_collection_id');
        $this->reelCollectionId  = gs('bunny_reel_collection_id') ?: config('bunny.reel_collection_id');
        $this->apiBase          = config('bunny.api_base');
        $this->tusEndpoint      = config('bunny.tus_endpoint');
    }

    /**
     * Switch to Reel Library ID.
     */
    public function useReelLibrary(): self
    {
        $this->libraryId = gs('bunny_reel_library_id') ?: $this->libraryId;
        return $this;
    }

    /**
     * Switch to Video Library ID.
     */
    public function useVideoLibrary(): self
    {
        $this->libraryId = gs('bunny_video_library_id') ?: config('bunny.library_id');
        return $this;
    }

    /**
     * Get the video collection ID with fallbacks.
     */
    public function getVideoCollectionId(): ?string
    {
        return $this->videoCollectionId;
    }

    /**
     * Get the reel collection ID with fallbacks.
     */
    public function getReelCollectionId(): ?string
    {
        return $this->reelCollectionId;
    }

    /**
     * Create a video placeholder in Bunny Stream.
     * This reserves a slot before the TUS upload begins.
     *
     * @param string $title
     * @param string|null $collectionId
     * @return array{guid: string, ...}
     * @throws \Exception
     */
    public function createVideo(string $title, ?string $collectionId = null): array
    {
        $payload = ['title' => $title];
        if ($collectionId) {
            $payload['collectionId'] = $collectionId;
        }

        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->apiBase}/library/{$this->libraryId}/videos", $payload);

        if ($response->failed()) {
            Log::error('Bunny Stream: Failed to create video placeholder', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to create video in Bunny Stream: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Fetch video details from Bunny Stream.
     *
     * @param string $videoId  The Bunny GUID
     * @return array
     */
    public function getVideo(string $videoId): array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
        ])->get("{$this->apiBase}/library/{$this->libraryId}/videos/{$videoId}");

        if ($response->failed()) {
            Log::error('Bunny Stream: Failed to fetch video', [
                'videoId' => $videoId,
                'status' => $response->status(),
            ]);
            return [];
        }

        return $response->json();
    }

    /**
     * Get Bunny Stream statistics (views) for a video.
     * Single source: Bunny counts actual plays, app will sync to this.
     */
    public function getVideoStatistics(string $videoId): array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
        ])->get("{$this->apiBase}/library/{$this->libraryId}/videos/{$videoId}/statistics");

        if ($response->failed()) {
            // Fallback: try to get views from main getVideo payload (some libs include views)
            $video = $this->getVideo($videoId);
            if (isset($video['views'])) {
                return ['views' => (int) $video['views']];
            }
            return [];
        }

        return $response->json();
    }

    /**
     * Delete a video from Bunny Stream.
     *
     * @param string $videoId  The Bunny GUID
     * @return bool
     */
    public function deleteVideo(string $videoId): bool
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
        ])->delete("{$this->apiBase}/library/{$this->libraryId}/videos/{$videoId}");

        return $response->successful();
    }

    /**
     * Update video details in Bunny Stream.
     *
     * @param string $videoId
     * @param array $data (e.g. ['title' => 'New Title'])
     * @return bool
     */
    public function updateVideo(string $videoId, array $data): bool
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->apiBase}/library/{$this->libraryId}/videos/{$videoId}", $data);

        if ($response->failed()) {
            Log::error('Bunny Stream: Failed to update video', [
                'videoId' => $videoId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        }

        return true;
    }

    /**
     * Generate the SHA-256 authorization signature for TUS uploads.
     *
     * Bunny requires: SHA256(library_id + api_key + expiration_time + video_id)
     *
     * @param string $videoId
     * @param int $expirationTime  Unix timestamp
     * @return string
     */
    public function generateTusSignature(string $videoId, int $expirationTime): string
    {
        return hash('sha256', $this->libraryId . $this->apiKey . $expirationTime . $videoId);
    }

    /**
     * Get the TUS upload parameters needed by the frontend.
     *
     * @param string $videoId
     * @return array{endpoint: string, headers: array}
     */
    public function getTusUploadParams(string $videoId): array
    {
        $expirationTime = time() + 86400; // 24 hours from now
        $signature = $this->generateTusSignature($videoId, $expirationTime);

        return [
            'endpoint' => url('/tus-proxy'),
            'headers' => [
                'AuthorizationSignature' => $signature,
                'AuthorizationExpire' => $expirationTime,
                'VideoId' => $videoId,
                'LibraryId' => $this->libraryId,
            ],
        ];
    }

    public function getEmbedUrl(string $videoId, bool $autoplay = false): string
    {
        $cdnHostname = config('bunny.cdn_hostname', 'iframe.mediadelivery.net');
        $baseUrl = "https://{$cdnHostname}/embed/{$this->libraryId}/{$videoId}";
        
        $params = [
            'autoplay' => $autoplay ? 'true' : 'false',
        ];
        
        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * Get the direct play URL (HLS) for a Bunny Stream video.
     *
     * @param string $videoId
     * @return string
     */
    public function getPlayUrl(string $videoId): string
    {
        $cdnHostname = $this->getCdnHostname();
        return "https://{$cdnHostname}/play/{$this->libraryId}/{$videoId}";
    }

    /**
     * Get the thumbnail URL for a Bunny Stream video.
     *
     * @param string $videoId
     * @return string
     */
    public function getThumbnailUrl(string $videoId, int $expirationTime = 0): string
    {
        $cdnHostname = $this->getCdnHostname();
        $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
        
        if (!$expirationTime) {
            $expirationTime = time() + 3600;
        }

        $path = "/{$videoId}/thumbnail.jpg";
        $hashable = $securityKey . $path . $expirationTime;
        $token = hash('sha256', $hashable);
        
        return "https://{$cdnHostname}{$path}?token={$token}&expires={$expirationTime}";
    }

    /**
     * Get the animated preview URL for a Bunny Stream video.
     *
     * @param string $videoId
     * @return string
     */
    public function getPreviewUrl(string $videoId, int $expirationTime = 0): string
    {
        $cdnHostname = $this->getCdnHostname();
        $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
        
        if (!$expirationTime) {
            $expirationTime = time() + 3600;
        }

        $path = "/{$videoId}/preview.webp";
        $hashable = $securityKey . $path . $expirationTime;
        $token = hash('sha256', $hashable);
        
        return "https://{$cdnHostname}{$path}?token={$token}&expires={$expirationTime}";
    }

    /**
     * List all videos in the library (paginated).
     *
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function listVideos(int $page = 1, int $perPage = 25): array
    {
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
        ])->get("{$this->apiBase}/library/{$this->libraryId}/videos", [
            'page' => $page,
            'itemsPerPage' => $perPage,
        ]);

        if ($response->failed()) {
            return ['items' => [], 'totalItems' => 0];
        }

        return $response->json();
    }

    /**
     * Generate a signed URL for Bunny Stream playback.
     * 
     * @param string $videoId
     * @param int $expirationTime Unix timestamp
     * @return string
     */
    public function generateSignedUrl(string $videoId, int $expirationTime = 0): string
    {
        $cdnHostname = $this->getCdnHostname();
        $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
        
        if (!$expirationTime) {
            $expirationTime = time() + 3600; // Default 1 hour
        }

        $path = "/{$videoId}/playlist.m3u8";
        
        // Bunny Stream Token Auth Algorithm
        // token = base64(sha256(SecurityKey + Path + Expiration))
        $hashable = $securityKey . $path . $expirationTime;
        $token = hash('sha256', $hashable);
        
        return "https://{$cdnHostname}{$path}?token={$token}&expires={$expirationTime}";
    }

    /**
     * Resolve CDN hostname from config for thumbnail URLs.
     */
    protected function getCdnHostname(): string
    {
        return gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname', 'iframe.mediadelivery.net');
    }
}
