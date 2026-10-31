<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Bunny Storage Worker Auth Service
 *
 * Generates and verifies short-lived HMAC-signed tokens for
 * Browser -> Cloudflare Worker -> Bunny Storage direct uploads.
 *
 * Token binds: version | userId | reelId | storagePath | operation | expiry
 * Secret is shared between Laravel and Worker via WORKER_SIGNING_SECRET.
 * Bunny Storage AccessKey NEVER leaves server/Worker.
 */
class BunnyStorageAuthService
{
    private string $signingSecret;
    private string $version = 'v1';

    public function __construct()
    {
        $this->signingSecret = env('WORKER_SIGNING_SECRET', env('APP_KEY', ''));
        // Prefer explicit worker secret; fallback to APP_KEY only if not set (dev)
        if (empty(env('WORKER_SIGNING_SECRET'))) {
            $secret = config('bunny.worker_signing_secret');
            if (!empty($secret)) {
                $this->signingSecret = $secret;
            }
        }
    }

    /**
     * Generate a signed authorization token for Worker upload.
     *
     * @param int $userId
     * @param int $reelId
     * @param string $storagePath e.g. reels/7/123/uuid.mp4
     * @param string $operation upload|final
     * @param int $ttlSeconds default 300
     * @return array{token:string, expiry:int, path:string, operation:string}
     */
    public function generateToken(int $userId, int $reelId, string $storagePath, string $operation = 'upload', int $ttlSeconds = 300): array
    {
        $expiry = time() + $ttlSeconds;
        $payload = implode('|', [$this->version, $userId, $reelId, $storagePath, $operation, $expiry]);
        $token = hash_hmac('sha256', $payload, $this->signingSecret);

        return [
            'token' => $token,
            'expiry' => $expiry,
            'path' => $storagePath,
            'operation' => $operation,
        ];
    }

    /**
     * Verify a token (used for testing / optional server-side confirm).
     */
    public function verifyToken(string $token, int $userId, int $reelId, string $storagePath, string $operation, int $expiry): bool
    {
        if (time() > $expiry) {
            return false;
        }
        $payload = implode('|', [$this->version, $userId, $reelId, $storagePath, $operation, $expiry]);
        $expected = hash_hmac('sha256', $payload, $this->signingSecret);
        return hash_equals($expected, $token);
    }

    /**
     * Generate a server-controlled storage path for a user's reel.
     * Path is NEVER taken from client input.
     *
     * @param int $userId
     * @param int $reelId
     * @param string $type source|final
     * @return string
     */
    public function generateStoragePath(int $userId, int $reelId, string $type = 'source'): string
    {
        $uuid = (string) Str::uuid();
        $filename = $type === 'final' ? 'final.mp4' : $uuid . '.mp4';
        // For source we use uuid per upload; final is deterministic per reel
        if ($type === 'final') {
            return "reels/{$userId}/{$reelId}/final.mp4";
        }
        return "reels/{$userId}/{$reelId}/{$uuid}.mp4";
    }

    /**
     * Validate storage path format strictly.
     */
    public function isValidPath(string $path, int $expectedUserId): bool
    {
        // reels/{userId}/{reelId}/{uuid}.mp4 or reels/{userId}/{reelId}/final.mp4
        if (!preg_match('#^reels/(\d+)/(\d+)/([a-f0-9\-]{36}\.mp4|final\.mp4|source\.mp4)$#', $path, $m)) {
            return false;
        }
        if ((int)$m[1] !== $expectedUserId) {
            return false;
        }
        // Prevent traversal
        if (str_contains($path, '..') || str_contains($path, '\\') || str_contains($path, '%2f') || str_contains($path, '%5c')) {
            return false;
        }
        return true;
    }

    /**
     * Get Worker upload URL (public edge). Never includes AccessKey.
     * Kept for rollback; active Reels now use S3 presigned PUT.
     */
    public function getWorkerUploadUrl(string $storagePath, string $token, int $expiry, string $operation): string
    {
        $workerBase = config('bunny.worker_url') ?: env('WORKER_UPLOAD_URL', '');
        if (empty($workerBase)) {
            // Fallback to local placeholder - will be replaced once Worker deployed
            $workerBase = url('/api/worker-placeholder');
        }
        $workerBase = rtrim($workerBase, '/');
        return $workerBase . '/upload?path=' . urlencode($storagePath) . '&token=' . $token . '&expiry=' . $expiry . '&op=' . urlencode($operation);
    }

    /**
     * Generate AWS SigV4 presigned PUT URL for Bunny S3 (Reels only).
     * Browser PUTs directly to S3 without exposing permanent AccessKey.
     * Requires S3-compatible zone (de) with endpoint https://de-s3.storage.bunnycdn.com
     *
     * @return array{presignedUrl:string, storage_path:string, expiry:int, bucket:string}
     */
    public function generatePresignedPutUrl(string $storagePath, int $ttlSeconds = 300): array
    {
        $zone = gs('bunny_reels_storage_zone') ?: config('bunny.reels_storage_zone') ?: config('bunny.storage_zone');
        $accessKey = gs('bunny_reels_storage_access_key') ?: config('bunny.reels_storage_access_key') ?: config('bunny.storage_access_key');
        $region = gs('bunny_reels_storage_region') ?: config('bunny.reels_storage_region') ?: config('bunny.storage_region') ?: 'de';
        $regionClean = strtolower(trim($region));
        $endpoint = match($regionClean) {
            'ny', 'us-east', 'newyork' => 'https://ny-s3.storage.bunnycdn.com',
            'sg', 'singapore' => 'https://sg-s3.storage.bunnycdn.com',
            default => 'https://de-s3.storage.bunnycdn.com',
        };
        if (empty($zone) || empty($accessKey)) {
            throw new \RuntimeException('Bunny S3 not configured (zone/key missing)');
        }
        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => true,
            'credentials' => ['key' => $zone, 'secret' => $accessKey],
            'http' => ['verify' => false],
        ]);
        $expiry = time() + $ttlSeconds;
        $cmd = $s3->getCommand('PutObject', [
            'Bucket' => $zone,
            'Key' => $storagePath,
            'ContentType' => 'video/mp4',
        ]);
        $request = $s3->createPresignedRequest($cmd, '+'.$ttlSeconds.' seconds');
        $presignedUrl = (string) $request->getUri();
        return [
            'presignedUrl' => $presignedUrl,
            'storage_path' => $storagePath,
            'expiry' => $expiry,
            'bucket' => $zone,
        ];
    }

    /**
     * Get signing secret for worker verification (never expose).
     */
    public function getSigningSecret(): string
    {
        return $this->signingSecret;
    }
}
