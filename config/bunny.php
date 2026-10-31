<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bunny Stream Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Bunny.net Stream video hosting integration.
    | Get these values from your Bunny.net Stream dashboard:
    |   Stream > Your Library > API > Library API Key
    |
    */

    'library_id' => env('BUNNY_LIBRARY_ID', ''),

    'api_key' => env('BUNNY_STREAM_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Bunny CDN Hostname
    |--------------------------------------------------------------------------
    |
    | The hostname used for the iframe player embeds. Found in your Bunny
    | Stream library settings under "Player" > "Embed Domain".
    |
    */

    'cdn_hostname' => env('BUNNY_CDN_HOSTNAME', 'iframe.mediadelivery.net'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */

    'api_base' => 'https://video.bunnycdn.com',

    'tus_endpoint' => 'https://video.bunnycdn.com/tusupload',

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret (Optional)
    |--------------------------------------------------------------------------
    |
    | If you set a webhook signing key in your Bunny dashboard, put it here
    | to verify incoming webhook payloads.
    |
    */

    'webhook_secret' => env('BUNNY_WEBHOOK_SECRET', ''),

    'video_collection_id' => env('BUNNY_VIDEO_COLLECTION_ID', ''),
    'reel_collection_id' => env('BUNNY_REEL_COLLECTION_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Worker + Bunny Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Worker acts as streaming gateway: Browser -> Worker -> Bunny Storage.
    | Worker holds BUNNY_STORAGE_ACCESS_KEY secret; browser never sees it.
    | Laravel signs HMAC tokens with WORKER_SIGNING_SECRET (shared with Worker).
    |
    */
    'worker_url' => env('WORKER_UPLOAD_URL', ''),
    'worker_signing_secret' => env('WORKER_SIGNING_SECRET', ''),
    'wasm_enabled' => env('REEL_WASM_PROCESSING_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Bunny Storage Configuration (Pull Zone + Reels Storage)
    |--------------------------------------------------------------------------
    |
    | Reels Worker storage uses dedicated env keys. Fallbacks to generic
    | BUNNY_STORAGE_* kept for backwards compat. Never expose AccessKey to frontend.
    |
    */
    'storage_zone' => env('BUNNY_STORAGE_ZONE', ''),
    'reels_storage_zone' => env('BUNNY_REELS_STORAGE_ZONE', env('BUNNY_STORAGE_ZONE', '')),
    'reels_storage_access_key' => env('BUNNY_REELS_STORAGE_ACCESS_KEY', env('BUNNY_STORAGE_ACCESS_KEY', '')),
    'reels_storage_region' => env('BUNNY_REELS_STORAGE_REGION', env('BUNNY_STORAGE_REGION', '')),
    'storage_region' => env('BUNNY_STORAGE_REGION', ''),
    'pull_zone' => env('BUNNY_PULL_ZONE', ''),
    'reels_pull_zone' => env('BUNNY_REELS_PULL_ZONE', env('BUNNY_PULL_ZONE', '')),
    'security_key' => env('BUNNY_SECURITY_KEY', ''),
];
