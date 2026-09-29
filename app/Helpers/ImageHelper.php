<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    /**
     * Check whether an R2 object exists, caching the result to avoid
     * a network round-trip on every request.
     */
    public static function r2Exists($key)
    {
        $cacheKey = 'r2_exists_' . md5($key);
        return Cache::store('file')->remember($cacheKey, 86400, function () use ($key) {
            return Storage::disk('r2')->exists($key);
        });
    }
    /**
     * Upload an image to Cloudflare R2.
     */
    public static function uploadToR2($file, $folder)
    {
        $ext = method_exists($file, 'getClientOriginalExtension') ? $file->getClientOriginalExtension() : $file->extension();
        $ext = $ext ?: 'jpg';
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        // NOTE: r2 disk has 'throw' => false, so a failed put returns false
        // instead of throwing - detect it here so callers never store a
        // garbage path thinking the upload succeeded.
        $r2Path = Storage::disk('r2')->putFileAs($folder, $file, $imageName);
        if (!$r2Path) {
            throw new \RuntimeException('R2 upload failed for folder "' . $folder . '". Check R2 credentials/bucket.');
        }
        Cache::store('file')->forget('r2_exists_' . md5($r2Path));
        return Storage::disk('r2')->url($r2Path);
    }

    /**
     * Map local paths to R2 folder keys based on FileInfo.
     */
    public static function getR2Path($path)
    {
        $fileInfo = new \App\Constants\FileInfo();
        foreach ($fileInfo->fileInfo() as $key => $info) {
            $infoPath = ltrim($info['path'] ?? '', '/');
            $cleanPath = ltrim($path, '/');
            if (!empty($infoPath) && strpos($cleanPath, $infoPath) === 0) {
                $filename = ltrim(substr($cleanPath, strlen($infoPath)), '/');
                // Return key/filename which is the structure used in R2
                return $key . '/' . $filename;
            }
        }
        return $path;
    }

    /**
     * Whether a given URL belongs to the configured R2 endpoint or Cloudflare R2 host.
     */
    public static function isR2EndpointUrl($url)
    {
        if (!$url || strpos($url, 'http') !== 0) {
            return false;
        }
        $r2 = gs('cloudflare_config');
        $endpoint = @$r2->endpoint ?: config('filesystems.disks.r2.endpoint');
        $customUrl = @$r2->url ?: config('filesystems.disks.r2.url');

        if ($endpoint && strpos($url, $endpoint) === 0) {
            return true;
        }
        if ($customUrl && strpos($url, $customUrl) === 0) {
            return true;
        }
        return strpos($url, '.r2.cloudflarestorage.com') !== false;
    }

    /**
     * Get the displayable URL for an image (handles R2 and local).
     */
    public static function getPhotoUrl($path)
    {
        static $urlCache = [];
        if (isset($urlCache[$path])) {
            return $urlCache[$path];
        }

        if (!$path) {
            return $urlCache[$path] = asset('assets/images/default.png');
        }

        $r2 = gs('cloudflare_config');
        $endpoint = @$r2->endpoint ?: config('filesystems.disks.r2.endpoint');
        $bucket = @$r2->bucket ?: config('filesystems.disks.r2.bucket');
        $customUrl = @$r2->url ?: config('filesystems.disks.r2.url');

        // If path contains a full URL, extract the relative key
        if (strpos($path, 'http') !== false) {
            $fullUrl = substr($path, strpos($path, 'http'));
            $extracted = null;

            // Try custom URL prefix first
            if ($customUrl && strpos($fullUrl, $customUrl) === 0) {
                $extracted = substr($fullUrl, strlen(rtrim($customUrl, '/')) + 1);
            }
            // Try endpoint + bucket prefix
            if ($extracted === null && $endpoint) {
                $baseToRemove = rtrim($endpoint, '/') . '/' . $bucket . '/';
                $candidate = str_replace($baseToRemove, '', $fullUrl);
                if ($candidate !== $fullUrl) {
                    $extracted = $candidate;
                } else {
                    $cleanEndpoint = preg_replace('/^https?:\/\//', '', $endpoint);
                    $cleanUrl = preg_replace('/^https?:\/\//', '', $fullUrl);
                    $candidate = str_replace($cleanEndpoint . '/' . $bucket . '/', '', $cleanUrl);
                    if ($candidate !== $cleanUrl) {
                        $extracted = $candidate;
                    }
                }
            }

            if ($extracted !== null) {
                $path = $extracted;
            } else {
                return $urlCache[$path] = $fullUrl;
            }
        }

        // 1. Check local storage
        if (Storage::disk('public')->exists($path)) {
            return $urlCache[$path] = asset('storage/' . $path);
        }

        // 2. Handle R2
        $r2Path = self::getR2Path($path);

        // Try signed URL for any S3-compatible endpoint with credentials
        if ($bucket && config('filesystems.disks.r2.key')) {
            try {
                if (self::r2Exists($r2Path)) {
                    return $urlCache[$path] = Storage::disk('r2')->temporaryUrl($r2Path, now()->addDays(1));
                }
                // Fallback: Check original path directly in R2
                if (self::r2Exists($path)) {
                    return $urlCache[$path] = Storage::disk('r2')->temporaryUrl($path, now()->addDays(1));
                }
                // Object confirmed missing on R2 → avoid dead URL, show default placeholder
                return $urlCache[$path] = asset('assets/images/default.png');
            } catch (\Exception $e) {
                Log::error("R2 Signed URL Error: " . $e->getMessage());
            }
        }

        // Fallback to public URL
        if ($customUrl) {
            return $urlCache[$path] = rtrim($customUrl, '/') . '/' . ltrim($r2Path, '/');
        }

        $configUrl = config('filesystems.disks.r2.url');
        if ($configUrl && strpos($configUrl, 'http') === 0) {
             return $urlCache[$path] = rtrim($configUrl, '/') . '/' . ltrim($r2Path, '/');
        }

        return $urlCache[$path] = asset('storage/' . $path);
    }

    /**
     * Delete an image from R2.
     */
    public static function deleteImage($path)
    {
        if (strpos($path, 'http') === 0) {
            $r2 = gs('cloudflare_config');
            $endpoint = @$r2->endpoint ?: config('filesystems.disks.r2.endpoint');
            $bucket = @$r2->bucket ?: config('filesystems.disks.r2.bucket');
            $customUrl = @$r2->url ?: config('filesystems.disks.r2.url');
            
            $relativePath = null;
            
            // Try to extract from custom URL
            if ($customUrl && strpos($path, $customUrl) !== false) {
                $relativePath = str_replace(rtrim($customUrl, '/') . '/', '', $path);
            } 
            // Try to extract from endpoint URL (https://endpoint/bucket/path)
            elseif ($endpoint) {
                $baseToRemove = rtrim($endpoint, '/') . '/' . $bucket . '/';
                if (strpos($path, $baseToRemove) !== false) {
                    $relativePath = str_replace($baseToRemove, '', $path);
                }
            }

            if ($relativePath) {
                return Storage::disk('r2')->delete($relativePath);
            }
        }
        return Storage::disk('public')->delete($path);
    }

}
