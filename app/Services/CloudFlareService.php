<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CloudFlareService
{
    private $client;
    private $zoneId;
    private $apiToken;
    private $cdnUrl;

    public function __construct()
    {
        $this->client   = new Client();
        $cloudflare     = gs('cloudflare_config');
        
        // Accessing keys dynamically from General Settings
        $this->zoneId   = @$cloudflare->zone_id; // Added zone_id if needed in future
        $this->apiToken = @$cloudflare->api_token; // Added api_token if needed in future
        $this->cdnUrl   = @$cloudflare->url;
    }


    /**
     * Generate a CDN URL for an asset.
     */
    public function asset($path)
    {
        if (!$this->cdnUrl) {
            return asset($path);
        }
        $path = ltrim($path, '/');
        return rtrim($this->cdnUrl, '/') . '/' . $path;
    }

    /**
     * Purge Cloudflare cache for specified files.
     */
    public function purgeCache($files = [])
    {
        if (!$this->zoneId || !($this->apiToken)) {
            return true;
        }

        try {
            $response = $this->client->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/purge_cache", [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiToken}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'files' => $files,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return isset($result['success']) && $result['success'];
        } catch (\Exception $e) {
            Log::error("CloudFlare Cache Purge Error: " . $e->getMessage());
            return false;
        }
    }
}
