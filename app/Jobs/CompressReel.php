<?php

namespace App\Jobs;

use App\Models\Reel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CompressReel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 3600; // 1 hour: duet+music+compress of large reels can run long
    public array $backoff = [30, 90, 180]; // progressive backoff on retries

    public function __construct(
        public Reel $reel
    ) {}

    /**
     * Prevent two CompressReel jobs from ever processing the same reel at the
     * same time (e.g. duplicate Bunny webhooks / duplicate dispatch attempts).
     */
    public function middleware(): array
    {
        // Prevent two CompressReel jobs from ever processing the same reel at the
        // same time. Expiry (7200s) is longer than the job timeout (3600s) so a
        // crashed worker cannot permanently lock the reel, while a still-running
        // job keeps its lock and blocks duplicate dispatch.
        return [(new WithoutOverlapping('compress-reel-' . $this->reel->id))->expireAfter(7200)];
    }

    /**
     * The maximum number of Bunny download attempts and the short bounded delay
     * between them (for transient 404 / CDN propagation after the ready status).
     */
    private const DOWNLOAD_MAX_ATTEMPTS = 3;
    private const DOWNLOAD_BACKOFF_SECONDS = 5;

    public function handle(): void
    {
        Log::info('[REEL-QUEUE] CompressReel started', ['reel_id' => $this->reel->id]);

        $reel = \App\Models\Reel::withoutGlobalScopes()->find($this->reel->id);
        if (!$reel) {
            Log::warning("CompressReel: Reel #{$this->reel->id} no longer exists; skipping");
            return;
        }
        $this->reel = $reel;

        if ((bool) $reel->is_compressed) {
            Log::info("CompressReel: Reel #{$reel->id} already compressed; skipping duplicate processing");
            return;
        }

        // Worker Storage reels must never be processed by server FFmpeg
        if (!empty($reel->storage_path)) {
            Log::info("CompressReel: Reel #{$reel->id} is a Worker Storage reel (storage_path set); skipping server FFmpeg", [
                'storage_path' => $reel->storage_path,
            ]);
            return;
        }

        Log::info("[REEL-PROCESS] processing claimed", [
            'reel_id' => $reel->id,
            'music_source' => $reel->music_source,
            'global_music_url' => $reel->global_music_url,
            'is_duet' => $reel->parent_id ? 'yes' : 'no'
        ]);

        $isLegacy = !empty($reel->bunny_id);

        if (!$isLegacy) {
            $inputPath = storage_path("app/" . $reel->video_path);
            $outputPath = storage_path("app/reels/temp/{$reel->id}/final.mp4");
            $outputName = "final.mp4";
        } else {
            $videoFilename = $reel->video_path ?: ($reel->bunny_id . '.mp4');
            $inputPath  = public_path(getFilePath('reel') . '/' . $videoFilename);
            $outputName = pathinfo($videoFilename, PATHINFO_FILENAME) . '_compressed.mp4';
            $outputPath = public_path(getFilePath('reel') . '/' . $outputName);
        }

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        // Set state to compression_status = 1 (mixing)
        $reel->update(['compression_status' => 1]);

        Log::info("[REEL-PROCESS] local source path resolved", [
            'reel_id' => $reel->id,
            'is_legacy' => $isLegacy ? 'yes' : 'no',
            'video_path' => $reel->video_path,
            'exists' => (!empty($reel->video_path) && file_exists($inputPath)) ? 'yes' : 'no',
        ]);

        try {
            $ffmpegPath = $this->getFFmpegPath();

            if (!$ffmpegPath) {
                Log::warning("CompressReel: FFmpeg not found for reel #{$reel->id}");
                $reel->update(['compression_status' => 3]);
                return;
            }

            if ($isLegacy) {
                // Legacy reels need to download source from Bunny if missing locally
                if (empty($reel->video_path) || !file_exists($inputPath) || is_dir($inputPath)) {
                    if (empty($reel->video_path)) {
                        $reel->update(['video_path' => $videoFilename]);
                    }

                    Log::info("CompressReel: Legacy local source missing, retrieving from BunnyCDN for reel #{$reel->id}", [
                        'bunny_id' => $reel->bunny_id,
                        'source_type' => 'bunny',
                    ]);
                    $bunny = app(\App\Services\BunnyStreamService::class);
                    $bunny->useReelLibrary();

                    $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
                    $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');

                    if (!file_exists(dirname($inputPath))) {
                        mkdir(dirname($inputPath), 0755, true);
                    }

                    $downloaded = false;
                    for ($attempt = 1; $attempt <= self::DOWNLOAD_MAX_ATTEMPTS; $attempt++) {
                        if ($attempt > 1) {
                            sleep(self::DOWNLOAD_BACKOFF_SECONDS);
                        }

                        $response = \Illuminate\Support\Facades\Http::withOptions(['stream' => true])
                            ->withHeaders([
                                'AccessKey' => $apiKey,
                                'Accept' => '*/*',
                            ])
                            ->timeout(300)
                            ->get("https://video.bunnycdn.com/library/{$libraryId}/videos/{$reel->bunny_id}/download");

                        if ($response->successful()) {
                            $fp = @fopen($inputPath, 'wb');
                            if ($fp !== false) {
                                try {
                                    foreach ($response->toPsrResponse()->getBody() as $chunk) {
                                        fwrite($fp, $chunk);
                                    }
                                } finally {
                                    fclose($fp);
                                }
                                $downloaded = true;
                                break;
                            }
                        } else {
                            Log::warning("CompressReel: Bunny source retrieval attempt {$attempt}/" . self::DOWNLOAD_MAX_ATTEMPTS . " failed", [
                                'reel_id' => $reel->id,
                                'bunny_id' => $reel->bunny_id,
                                'status' => $response->status(),
                            ]);
                        }
                    }

                    if (!$downloaded) {
                        Log::error("CompressReel: Failed to retrieve original from Bunny after " . self::DOWNLOAD_MAX_ATTEMPTS . " attempts", [
                            'reel_id' => $reel->id,
                            'bunny_id' => $reel->bunny_id,
                            'library_id' => $libraryId,
                        ]);
                        $this->release(30);
                        $reel->update(['compression_status' => 3]);
                        return;
                    }
                }
            } else {
                // New cPanel pipeline: source video MUST exist on disk
                if (empty($reel->video_path) || !file_exists($inputPath)) {
                    Log::error("CompressReel: Temporary source video not found on cPanel for reel #{$reel->id}", [
                        'path' => $inputPath,
                    ]);
                    $reel->update(['compression_status' => 3]);
                    return;
                }
            }

            $musicFile = null;
            $audioFilter = "";
            $duetVideoPath = null;
            
            if ($reel->parent_id) {
                Log::info("[REEL-DUET] reference reel ID", ['parent_id' => $reel->parent_id]);
                $parentReel = \App\Models\Reel::find($reel->parent_id);
                if ($parentReel) {
                    Log::info("[REEL-DUET] reference Bunny GUID", ['parent_bunny_id' => $parentReel->bunny_id]);
                    $duetName = 'duet_' . $parentReel->id . '_' . uniqid() . '.mp4';
                    $duetVideoPath = storage_path('app/public/temp/' . $duetName);
                    if (!file_exists(dirname($duetVideoPath))) mkdir(dirname($duetVideoPath), 0755, true);
                    
                    if ($parentReel->isBunnyReel()) {
                        $bunnyService = app(\App\Services\BunnyStreamService::class)->useReelLibrary();
                        $resolutions = ['play_480p.mp4', 'play_720p.mp4', 'play_360p.mp4', 'play_1080p.mp4'];
                        $downloaded = false;
                        
                        foreach($resolutions as $res) {
                            $unsignedPath = "/{$parentReel->bunny_id}/{$res}";
                            $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
                            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname');
                            
                            $audioUrl = "https://{$cdnHostname}{$unsignedPath}";
                            if ($securityKey) {
                                $expirationTime = time() + 7200;
                                $hashable = $securityKey . $unsignedPath . $expirationTime;
                                $token = hash('sha256', $hashable);
                                $audioUrl .= "?token={$token}&expires={$expirationTime}";
                            }
                            
                            $duetResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                ->withHeaders([
                                    'User-Agent' => 'Mozilla/5.0', 
                                    'ngrok-skip-browser-warning' => '1',
                                    'Referer' => url('/')
                                ])
                                ->get($audioUrl);
                                
                            if ($duetResponse->successful()) {
                                file_put_contents($duetVideoPath, $duetResponse->body());
                                $downloaded = true;
                                Log::info("[REEL-DUET] Bunny lookup result", [
                                    'success' => true,
                                    'resolution' => $res,
                                    'size' => filesize($duetVideoPath)
                                ]);
                                break;
                            } else {
                                Log::error("CompressReel: Download failed for $audioUrl", ['status' => $duetResponse->status(), 'body' => $duetResponse->body()]);
                            }
                        }
                        
                        if (!$downloaded) {
                            Log::error("[REEL-DUET] Bunny lookup result", ['success' => false]);
                            throw new \Exception("CompressReel: Failed to download duet parent video from BunnyCDN");
                        }
                    } else {
                        $baseAudioUrl = $parentReel->getVideoUrl();
                        $duetResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                ->withHeaders(['User-Agent' => 'Mozilla/5.0', 'ngrok-skip-browser-warning' => '1'])
                                ->get($baseAudioUrl);
                        if ($duetResponse->successful()) {
                            file_put_contents($duetVideoPath, $duetResponse->body());
                            Log::info("[REEL-DUET] Bunny lookup result (non-Bunny URL)", [
                                'success' => true,
                                'size' => filesize($duetVideoPath)
                            ]);
                        } else {
                            Log::error("CompressReel: Failed to download duet parent video from $baseAudioUrl");
                            throw new \Exception("CompressReel: Failed to download duet parent video from non-Bunny URL");
                        }
                    }

                    Log::info("[REEL-DUET] local reference path", ['path' => $duetVideoPath]);
                    Log::info("[REEL-DUET] local reference file size", ['size' => file_exists($duetVideoPath) ? filesize($duetVideoPath) : 'missing']);
                } else {
                    throw new \Exception("Duet parent reel ID {$reel->parent_id} not found in database.");
                }
            }

            // 1. Prepare Music if exists
            if ($reel->music_source && $reel->music_source != 'none') {
                $musicPath = null;
                if (in_array($reel->music_source, ['global', 'original']) && $reel->global_music_url) {
                    $musicName = 'global_' . $reel->id . '.mp3';
                    $musicPath = storage_path('app/public/temp/' . $musicName);
                    if (!file_exists(dirname($musicPath))) mkdir(dirname($musicPath), 0755, true);
                    
                    $baseUrl = url('/');
                    if (str_starts_with($reel->global_music_url, $baseUrl)) {
                        // Natively resolve local file paths
                        $relativePath = str_replace($baseUrl . '/', '', $reel->global_music_url);
                        $localPath = public_path(urldecode($relativePath));
                        if (file_exists($localPath)) {
                            $musicPath = $localPath;
                        } else {
                            Log::error("CompressReel: Local music file not found at $localPath (from {$reel->global_music_url})");
                            $musicPath = null;
                        }
                    } else {
                        // Download external music (e.g., iTunes)
                        $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname');
                        $downloadUrl = $reel->global_music_url;
                        $audioDownloaded = false;
                        
                        if ($cdnHostname || str_contains($downloadUrl, 'mediadelivery.net') || str_contains($downloadUrl, 'b-cdn.net')) {
                            // Extract bunny_id from URL
                            preg_match('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i', $downloadUrl, $matches);
                            if (!empty($matches[1])) {
                                $bunnyId = $matches[1];
                                $resolutions = ['play_480p.mp4', 'play_360p.mp4', 'play_720p.mp4', 'play_240p.mp4', 'play_1080p.mp4'];
                                $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
                                $hostToUse = $cdnHostname ?: 'iframe.mediadelivery.net';
                                
                                foreach ($resolutions as $res) {
                                    $unsignedPath = "/{$bunnyId}/{$res}";
                                    $testUrl = "https://{$hostToUse}{$unsignedPath}";
                                    
                                    if ($securityKey) {
                                        $expirationTime = time() + 7200;
                                        $hashable = $securityKey . $unsignedPath . $expirationTime;
                                        $token = hash('sha256', $hashable);
                                        $testUrl .= "?token={$token}&expires={$expirationTime}";
                                    }
                                    
                                    $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                        ->withHeaders([
                                            'User-Agent' => 'Mozilla/5.0', 
                                            'ngrok-skip-browser-warning' => '1',
                                            'Referer' => url('/')
                                        ])
                                        ->get($testUrl);
                                        
                                    if ($musicResponse->successful()) {
                                        file_put_contents($musicPath, $musicResponse->body());
                                        $audioDownloaded = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if (!$audioDownloaded) {
                            $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                ->withHeaders([
                                    'User-Agent' => 'Mozilla/5.0',
                                    'ngrok-skip-browser-warning' => '1',
                                    'Referer' => url('/')
                                ])
                                ->get($downloadUrl);
                                
                            if ($musicResponse->successful()) {
                                file_put_contents($musicPath, $musicResponse->body());
                                $audioDownloaded = true;
                            }
                        }
                        
                        if (!$audioDownloaded) {
                            Log::error("CompressReel: Failed to download music from {$reel->global_music_url}");
                            $musicPath = null;
                        }
                    }
                } elseif ($reel->music_source == 'upload' && $reel->audio_path) {
                    $musicPath = public_path(getFilePath('reelMusic') . '/' . $reel->audio_path);
                    if (!file_exists($musicPath)) {
                        Log::error("CompressReel: Uploaded music file not found at $musicPath");
                        $musicPath = null;
                    }
                } elseif ($reel->music_id) {
                    $track = \App\Models\ReelMusic::find($reel->music_id);
                    if ($track) {
                        $file = $track->file_path ?? $track->audio_path;
                        
                        if (str_starts_with($file, 'http')) {
                            // Direct HTTP URL (e.g. from BunnyCDN or external)
                            $musicName = 'music_' . $track->id . '_' . uniqid() . '.mp4';
                            $tempMusicPath = storage_path('app/public/temp/' . $musicName);
                            if (!file_exists(dirname($tempMusicPath))) mkdir(dirname($tempMusicPath), 0755, true);
                            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname');
                            $downloadUrl = $file;
                            $audioDownloaded = false;
                            
                            if ($cdnHostname || str_contains($file, 'mediadelivery.net') || str_contains($file, 'b-cdn.net')) {
                                preg_match('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/i', $downloadUrl, $matches);
                                if (!empty($matches[1])) {
                                    $bunnyId = $matches[1];
                                    $resolutions = ['play_480p.mp4', 'play_360p.mp4', 'play_720p.mp4', 'play_240p.mp4', 'play_1080p.mp4'];
                                    $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
                                    $hostToUse = $cdnHostname ?: 'iframe.mediadelivery.net';
                                    
                                    foreach ($resolutions as $res) {
                                        $unsignedPath = "/{$bunnyId}/{$res}";
                                        $testUrl = "https://{$hostToUse}{$unsignedPath}";
                                        
                                        if ($securityKey) {
                                            $expirationTime = time() + 7200;
                                            $hashable = $securityKey . $unsignedPath . $expirationTime;
                                            $token = hash('sha256', $hashable);
                                            $testUrl .= "?token={$token}&expires={$expirationTime}";
                                        }
                                        
                                        $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                            ->withHeaders([
                                                'User-Agent' => 'Mozilla/5.0', 
                                                'ngrok-skip-browser-warning' => '1',
                                                'Referer' => url('/')
                                            ])
                                            ->get($testUrl);
                                            
                                        if ($musicResponse->successful()) {
                                            file_put_contents($tempMusicPath, $musicResponse->body());
                                            $musicPath = $tempMusicPath;
                                            $audioDownloaded = true;
                                            break;
                                        }
                                    }
                                }
                            }

                            if (!$audioDownloaded) {
                                $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                    ->withHeaders([
                                        'User-Agent' => 'Mozilla/5.0', 
                                        'ngrok-skip-browser-warning' => '1',
                                        'Referer' => url('/')
                                    ])
                                    ->get($downloadUrl);
                                    
                                if ($musicResponse->successful()) {
                                    file_put_contents($tempMusicPath, $musicResponse->body());
                                    $musicPath = $tempMusicPath;
                                    $audioDownloaded = true;
                                }
                            }
                            
                            if (!$audioDownloaded) {
                                Log::error("CompressReel: Failed to download external track URL: $file");
                                $musicPath = null;
                            }
                        } else {
                            $musicPath = public_path(getFilePath('reelMusic') . '/' . $file);
                            if (!file_exists($musicPath)) {
                                $fallbackPath = public_path(getFilePath('reel') . '/' . $file);
                                if (file_exists($fallbackPath)) {
                                    $musicPath = $fallbackPath;
                                } else {
                                $sourceReel = \App\Models\Reel::where('video_path', $file)
                                    ->orWhere('compressed_video_path', $file)
                                    ->first();
                                if ($sourceReel) {
                                    $resolutions = ['play_480p.mp4', 'play_720p.mp4', 'play_360p.mp4', 'play_1080p.mp4'];
                                    $audioDownloaded = false;
                                    $baseAudioUrl = $sourceReel->getVideoUrl();
                                    
                                    $musicName = 'music_' . $track->id . '_' . uniqid() . '.mp4';
                                    $tempMusicPath = storage_path('app/public/temp/' . $musicName);
                                    if (!file_exists(dirname($tempMusicPath))) mkdir(dirname($tempMusicPath), 0755, true);
                                    
                                    if ($sourceReel->isBunnyReel()) {
                                        $bunnyService = app(\App\Services\BunnyStreamService::class)->useReelLibrary();
                                        $resolutions = ['play_480p.mp4', 'play_720p.mp4', 'play_360p.mp4', 'play_1080p.mp4'];
                                        $audioDownloaded = false;
                                        
                                        foreach($resolutions as $res) {
                                            $unsignedPath = "/{$sourceReel->bunny_id}/{$res}";
                                            $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');
                                            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname');
                                            
                                            $musicUrl = "https://{$cdnHostname}{$unsignedPath}";
                                            if ($securityKey) {
                                                $expirationTime = time() + 7200;
                                                $hashable = $securityKey . $unsignedPath . $expirationTime;
                                                $token = hash('sha256', $hashable);
                                                $musicUrl .= "?token={$token}&expires={$expirationTime}";
                                            }
                                            
                                            $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                                ->withHeaders([
                                                    'User-Agent' => 'Mozilla/5.0', 
                                                    'ngrok-skip-browser-warning' => '1',
                                                    'Referer' => url('/')
                                                ])
                                                ->get($musicUrl);
                                                
                                            if ($musicResponse->successful()) {
                                                file_put_contents($tempMusicPath, $musicResponse->body());
                                                $musicPath = $tempMusicPath;
                                                $audioDownloaded = true;
                                                break;
                                            }
                                        }
                                        
                                        if (!$audioDownloaded) {
                                            Log::error("CompressReel: Failed to download music from BunnyCDN");
                                            $musicPath = null;
                                        }
                                    } else {
                                        $musicResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                                ->withHeaders(['User-Agent' => 'Mozilla/5.0', 'ngrok-skip-browser-warning' => '1'])
                                                ->get($baseAudioUrl);
                                        if ($musicResponse->successful()) {
                                            file_put_contents($tempMusicPath, $musicResponse->body());
                                            $musicPath = $tempMusicPath;
                                        } else {
                                            Log::error("CompressReel: Failed to download music from BunnyCDN/external URL: $baseAudioUrl");
                                            $musicPath = null;
                                        }
                                    }
                                } else {
                                    $musicPath = null;
                                }
                            }
                        }
                    }
                }
                }

                if ($musicPath && (str_starts_with($musicPath, 'http') || file_exists($musicPath))) {
                    $musicFile = $musicPath;
                }
            }
            
            // Check if input video HAS audio
            $hasAudio = true;
            exec("\"$ffmpegPath\" -i " . escapeshellarg($inputPath) . " 2>&1", $probeOutput);
            if (!str_contains(implode("\n", $probeOutput), 'Audio:')) {
                $hasAudio = false;
                Log::info("CompressReel: Probed input video for reel #{$reel->id} — has NO audio track (silent base).");
            } else {
                Log::info("CompressReel: Probed input video for reel #{$reel->id} — contains active audio track.");
            }

            Log::info("CompressReel: Audio mix configuration details for reel #{$reel->id}", [
                'has_video_audio' => $hasAudio ? 'yes' : 'no',
                'music_file' => $musicFile,
                'music_start_time' => $reel->music_start_time,
                'mic_volume' => $reel->mic_volume,
                'music_volume' => $reel->music_volume
            ]);

            if ($duetVideoPath) {
                // Duet Mode: [0] = duet parent, [1] = camera, [2] = music (optional)
                $inputs = ["-i " . escapeshellarg($duetVideoPath), "-i " . escapeshellarg($inputPath)];
                if ($musicFile) {
                    $inputs[] = sprintf('-ss %s -i %s', number_format((float)($reel->music_start_time ?? 0.0), 2, '.', ''), escapeshellarg($musicFile));
                }
                
                $inputStr = implode(" ", $inputs);
                
                // Stack videos side by side (360x1280 each)
                $filterComplex = "[0:v]fps=30,format=yuv420p,scale=360:1280:force_original_aspect_ratio=increase,crop=360:1280,setpts=PTS-STARTPTS[v0];[1:v]fps=30,format=yuv420p,scale=360:1280:force_original_aspect_ratio=increase,crop=360:1280,setpts=PTS-STARTPTS[v1];[v0][v1]hstack=inputs=2:shortest=1[v];";
                $maps = "-map \"[v]\" ";
                
                $amixInputs = 0;
                $audioMix = "";
                $amixStr = "";
                
                // [0] Parent audio
                $audioMix .= "[0:a]volume=1.0[a0];";
                $amixStr .= "[a0]";
                $amixInputs++;
                
                // [1] Camera audio
                if ($hasAudio) {
                    $audioMix .= sprintf("[1:a]volume=%s[a1];", number_format((float)($reel->mic_volume ?? 1.0), 2, '.', ''));
                    $amixStr .= "[a1]";
                    $amixInputs++;
                }
                
                // [2] Music
                if ($musicFile) {
                    $audioMix .= sprintf("[2:a]volume=%s[a2];", number_format((float)($reel->music_volume ?? 1.0), 2, '.', ''));
                    $amixStr .= "[a2]";
                    $amixInputs++;
                }
                
                if ($amixInputs > 1) {
                    $filterComplex .= $audioMix . $amixStr . "amix=inputs={$amixInputs}:duration=shortest:dropout_transition=2[a]";
                    $maps .= "-map \"[a]\" ";
                } else {
                    $filterComplex .= $audioMix;
                    $maps .= "-map \"[a0]\" "; // fallback to just parent audio
                }
                
                $command = sprintf(
                    '%s %s -filter_complex %s %s -vcodec libx264 -preset ultrafast -crf 23 -c:a aac -b:a 192k -y %s 2>&1',
                    escapeshellarg($ffmpegPath),
                    trim($inputStr),
                    escapeshellarg($filterComplex),
                    $maps,
                    escapeshellarg($outputPath)
                );
            } else {
                // Standard mode
                if ($musicFile) {
                    if ($hasAudio) {
                        $audioFilter = sprintf(
                            '-ss %s -i %s -filter_complex "[0:a]volume=%s[v];[1:a]volume=%s[m];[v][m]amix=inputs=2:duration=first:dropout_transition=2[a]" -map 0:v -map "[a]"',
                            number_format((float)($reel->music_start_time ?? 0.0), 2, '.', ''),
                            escapeshellarg($musicFile),
                            number_format((float)($reel->mic_volume ?? 1.0), 2, '.', ''),
                            number_format((float)($reel->music_volume ?? 1.0), 2, '.', '')
                        );
                    } else {
                        $audioFilter = sprintf(
                            '-ss %s -i %s -filter_complex "[1:a]volume=%s[a]" -map 0:v -map "[a]"',
                            number_format((float)($reel->music_start_time ?? 0.0), 2, '.', ''),
                            escapeshellarg($musicFile),
                            number_format((float)($reel->music_volume ?? 1.0), 2, '.', '')
                        );
                    }
                }

                if (!$audioFilter) {
                    $audioFilter = "-acodec aac -b:a 128k";
                }

                $command = sprintf(
                    '%s -i %s %s -vcodec libx264 -preset ultrafast -crf 23 -c:a aac -b:a 192k -y %s 2>&1',
                    escapeshellarg($ffmpegPath),
                    escapeshellarg($inputPath),
                    $audioFilter,
                    escapeshellarg($outputPath)
                );
            }

            // 1a. Validate all local source files before starting FFmpeg/copying
            if (!file_exists($inputPath) || filesize($inputPath) === 0) {
                throw new \Exception("Local source video file missing or empty at path: {$inputPath}");
            }
            if ($reel->parent_id && (!$duetVideoPath || !file_exists($duetVideoPath) || filesize($duetVideoPath) === 0)) {
                throw new \Exception("Duet parent video file is missing or failed to download.");
            }
            if ($reel->music_source && $reel->music_source !== 'none' && (!$musicFile || !file_exists($musicFile) || filesize($musicFile) === 0)) {
                throw new \Exception("Configured music source file is missing or empty.");
            }

            $isMixingReel = ($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));

            if (!$isLegacy && !$isMixingReel) {
                Log::info("[REEL-FFMPEG] Skipping FFmpeg mixing for normal reel #{$reel->id}");
                copy($inputPath, $outputPath);
                $returnCode = 0;
            } else {
                Log::info("[REEL-FFMPEG] START", ['reel_id' => $reel->id]);
                Log::info("[REEL-PROCESS] FFmpeg started", ['reel_id' => $reel->id, 'command' => $command]);
                exec($command, $output, $returnCode);

                Log::info("[REEL-PROCESS] FFmpeg finished", [
                    'reel_id' => $reel->id,
                    'return_code' => $returnCode,
                    'output_file_exists' => file_exists($outputPath) ? 'yes' : 'no'
                ]);

                // Validate FFmpeg exit code and output file existence + size
                if ($returnCode !== 0 || !file_exists($outputPath) || filesize($outputPath) === 0) {
                    throw new \Exception("FFmpeg failed with exit code {$returnCode} or output file is empty.");
                }
                Log::info("[REEL-FFMPEG] SUCCESS", ['reel_id' => $reel->id]);
            }

            // [REEL-DUET] Logging file paths and sizes
            Log::info('[REEL-DUET] uploaded source path', ['path' => $inputPath]);
            Log::info('[REEL-DUET] final output path', ['path' => $outputPath]);
            Log::info('[REEL-DUET] source file size', ['size' => filesize($inputPath)]);
            Log::info('[REEL-DUET] final file size', ['size' => filesize($outputPath)]);

            // Verification checks
            if (realpath($outputPath) === realpath($inputPath)) {
                throw new \Exception("Validation Error: final.mp4 is the same file as source.mp4.");
            }

            // Stream validation via ffprobe
            $ffprobePath = $this->getFFprobePath($ffmpegPath);
            $hasVideo = false;
            $hasAudioStream = false;
            if ($ffprobePath) {
                $probeOutput = [];
                exec("\"$ffprobePath\" -v error -show_entries stream=codec_type -of default=noprint_wrappers=1 " . escapeshellarg($outputPath), $probeOutput);
                $probeText = implode("\n", $probeOutput);
                $hasVideo = str_contains($probeText, 'codec_type=video');
                $hasAudioStream = str_contains($probeText, 'codec_type=audio');

                Log::info('[REEL-DUET] final ffprobe streams', ['streams' => $probeText]);

                // Log dimensions and duration
                $dimOutput = [];
                exec("\"$ffprobePath\" -v error -select_streams v:0 -show_entries stream=width,height,duration -of default=noprint_wrappers=1 " . escapeshellarg($outputPath), $dimOutput);
                $dimText = implode("\n", $dimOutput);
                
                // Parse width/height/duration
                $width = ''; $height = ''; $duration = '';
                foreach ($dimOutput as $line) {
                    if (str_starts_with($line, 'width=')) $width = substr($line, 6);
                    if (str_starts_with($line, 'height=')) $height = substr($line, 7);
                    if (str_starts_with($line, 'duration=')) $duration = substr($line, 9);
                }
                Log::info('[REEL-DUET] final ffprobe dimensions', ['width' => $width, 'height' => $height]);
                Log::info('[REEL-DUET] final ffprobe duration', ['duration' => $duration]);

                // Validate side-by-side dimensions (should be 720 wide because 2x 360)
                if ((int)$width < 720) {
                    Log::warning("[REEL-DUET] final width {$width} is less than side-by-side expected 720.");
                }
            } else {
                Log::warning("CompressReel: ffprobe not found; stream validation skipped");
                $hasVideo = true;
                $hasAudioStream = true;
            }

            $requiresAudio = ($reel->music_source && $reel->music_source !== 'none') || $reel->parent_id || $hasAudio;

            if (!$hasVideo) {
                throw new \Exception("Validated output video stream missing.");
            }
            if ($requiresAudio && !$hasAudioStream) {
                throw new \Exception("Validated output audio stream missing for audio-required reel.");
            }

            // Transition state to compression_status = 4 (uploading_to_bunny)
            $reel->update(['compression_status' => 4]);

            if (!$isLegacy) {
                // Upload directly to Bunny Storage
                $storageZone = gs('bunny_reels_storage_zone');
                $accessKey = gs('bunny_reels_storage_access_key');
                $region = gs('bunny_reels_storage_region');
                $regionClean = strtolower(trim($region ?? ''));
                $host = (empty($regionClean) || in_array($regionClean, ['de', 'main', 'falkenstein'])) 
                    ? 'storage.bunnycdn.com' 
                    : "{$regionClean}.storage.bunnycdn.com";
                $storagePath = "reels/{$reel->id}/final.mp4";
                $uploadUrl = "https://{$host}/{$storageZone}/{$storagePath}";

                Log::info('[REEL-BUNNY-STORAGE] Upload started', [
                    'reel_id' => $reel->id,
                    'upload_url' => "https://{$host}/{$storageZone}/{$storagePath}"
                ]);

                $fp = @fopen($outputPath, 'rb');
                if ($fp === false) {
                    throw new \Exception("CompressReel: Final output not readable for storage upload: {$outputPath}");
                }

                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'AccessKey' => $accessKey,
                    ])
                    ->timeout(3600)
                    ->withBody($fp, 'application/octet-stream')
                    ->put($uploadUrl);
                } finally {
                    @fclose($fp);
                }

                Log::info('[REEL-BUNNY-STORAGE] Upload completed', [
                    'reel_id' => $reel->id,
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $reel->update([
                        'storage_path'          => $storagePath,
                        'bunny_status'          => 'ready',
                        'compression_status'    => 2, // ready
                    ]);

                    Log::info('[REEL-READY] Reel ready', ['reel_id' => $reel->id]);

                    // Cleanup temporary files
                    Log::info('[REEL-CLEANUP] Temporary files deleted', ['reel_id' => $reel->id]);
                    if ($musicFile && str_contains($musicFile, 'temp')) @unlink($musicFile);
                    @unlink($inputPath);
                    @unlink($outputPath);
                    if (isset($duetVideoPath) && file_exists($duetVideoPath)) @unlink($duetVideoPath);
                    
                    // Delete temporary directory if empty
                    @rmdir(dirname($outputPath));
                } else {
                    Log::error('[REEL-ERROR] Bunny Storage upload failed', [
                        'reel_id' => $reel->id,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    $reel->update(['compression_status' => 3]);
                }
                return;
            }

            // Legacy Bunny Stream upload path
            if (!$isLegacy) {
                throw new \Exception("CompressReel security block: non-legacy reel reached legacy Bunny Stream upload path!");
            }
            Log::info('[REEL-BUNNY] Final asset creation', ['reel_id' => $reel->id]);

            $originalBunnyId = $reel->bunny_id;
            $bunny = app(\App\Services\BunnyStreamService::class);
            $bunny->useReelLibrary();

            $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
            $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');

            if ($isLegacy) {
                $processedBunnyId = $reel->processing_bunny_id;
            } else {
                $processedBunnyId = $reel->bunny_id;
            }

            if (empty($processedBunnyId)) {
                try {
                    Log::info('[REEL-BUNNY] creating new Bunny asset for processed reel', [
                        'reel_id' => $reel->id,
                    ]);
                    $createResponse = \Illuminate\Support\Facades\Http::withHeaders([
                        'AccessKey' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])->post("https://video.bunnycdn.com/library/{$libraryId}/videos", [
                        'title' => $reel->title ?? 'Reel',
                    ]);
                } catch (\Throwable $e) {
                    Log::error("[REEL-BUNNY] failed to create new Bunny asset: " . $e->getMessage());
                    $createResponse = null;
                }

                if (!$createResponse || !$createResponse->successful()) {
                    Log::error('[REEL-BUNNY] creating new Bunny asset FAILED', [
                        'reel_id' => $reel->id,
                        'status' => $createResponse ? $createResponse->status() : 'n/a',
                    ]);
                    $this->cleanupLocalTemp($reel, $inputPath, $outputPath);
                    $reel->update(['compression_status' => 3]);
                    return;
                }

                $processedBunnyId = $createResponse->json('guid');
                if (!$processedBunnyId) {
                    Log::error('[REEL-BUNNY] new Bunny asset creation returned no GUID', [
                        'reel_id' => $reel->id,
                    ]);
                    $this->cleanupLocalTemp($reel, $inputPath, $outputPath);
                    $reel->update(['compression_status' => 3]);
                    return;
                }

                // Save GUID to appropriate field before starting upload
                if ($isLegacy) {
                    $reel->update(['processing_bunny_id' => $processedBunnyId]);
                } else {
                    $reel->update(['bunny_id' => $processedBunnyId]);
                }
            }

            Log::info("[REEL-BUNNY] Final upload started", [
                'reel_id' => $reel->id,
                'bunny_id' => $processedBunnyId,
                'bytes' => filesize($outputPath),
            ]);

            $fp = @fopen($outputPath, 'rb');
            if ($fp === false) {
                Log::error("CompressReel: Final output not readable for reel #{$reel->id}", ['path' => $outputPath]);
                try { $bunny->deleteVideo($processedBunnyId); } catch (\Throwable $ignored) {}
                $reel->update(['compression_status' => 3]);
                return;
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'AccessKey' => $apiKey,
                ])
                ->timeout(3600)
                ->withBody($fp, 'application/octet-stream')
                ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$processedBunnyId}");
            } finally {
                @fclose($fp);
            }

            Log::info('[REEL-BUNNY] Final upload completed', [
                'reel_id' => $reel->id,
                'bunny_id' => $processedBunnyId,
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                $reel->update([
                    'compressed_video_path' => $outputName,
                    'bunny_status'          => 'processing',
                    'compression_status'    => 5, // Bunny processing final asset
                ]);

                Log::info('[REEL-PROCESS] upload completed, waiting for webhook finalization', [
                    'reel_id' => $reel->id,
                    'bunny_id' => $processedBunnyId,
                ]);

                // Cleanup temporary local files ONLY if it is a legacy Reel (for new Reels, we wait for Bunny READY webhook)
                if ($isLegacy) {
                    if ($musicFile && str_contains($musicFile, 'temp')) @unlink($musicFile);
                    @unlink($inputPath);
                    @unlink($outputPath);
                    if (isset($duetVideoPath) && file_exists($duetVideoPath)) @unlink($duetVideoPath);
                }
            } else {
                Log::error("CompressReel: Bunny upload failed for reel #{$reel->id}", [
                    'bunny_id' => $processedBunnyId,
                    'status' => $response->status(),
                ]);
                $this->cleanupLocalTemp($reel, $inputPath, $outputPath);
                $reel->update(['compression_status' => 3]);
            }

        } catch (\Exception $e) {
            Log::error("CompressReel: Exception for reel #{$reel->id}: " . $e->getMessage());
            try {
                $inputPath  = public_path(getFilePath('reel') . '/' . $this->reel->video_path);
                $this->cleanupLocalTemp($this->reel, $inputPath);
            } catch (\Throwable $ignored) {}
            $reel->update(['compression_status' => 3]);
            throw $e;
        }
    }

    /**
     * Delete temporary local copies once the content is safely on Bunny CDN.
     * Only applies to Bunny-hosted reels - legacy/local-only reels must keep
     * their files. This fulfils the requirement that cPanel storage holds no
     * permanent video data.
     */
    /**
     * Clean transient processing outputs on a FAILED run.
     *
     * We deliberately do NOT delete the original local source ($inputPath) here:
     * the source must be retained so a later retry can re-run FFmpeg without
     * re-downloading the original from Bunny (which can return HTTP 404).
     * The source is only removed in the SUCCESS branch after the final processed
     * video has been uploaded to Bunny.
     */
    private function cleanupLocalTemp(Reel $reel, string $inputPath, ?string $outputPath = null): void
    {
        try {
            if ($outputPath && file_exists($outputPath)) {
                @unlink($outputPath);
            }
        } catch (\Throwable $e) {
            Log::warning("CompressReel: Cleanup skipped for reel #{$reel->id}: " . $e->getMessage());
        }
    }

    protected function getFFmpegPath(): ?string
    {
        // 1. Check General Settings
        $gs = \App\Models\GeneralSetting::first();
        if ($gs && !empty($gs->ffmpeg_path) && file_exists($gs->ffmpeg_path)) {
            return $gs->ffmpeg_path;
        }

        // 2. Try 'where' (Windows) or 'which' (Linux)
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $testCommand = $isWin ? 'where ffmpeg.exe' : 'which ffmpeg';
        $path = trim(shell_exec($testCommand) ?? '');

        if (!empty($path)) {
            $lines = explode("\n", $path);
            foreach ($lines as $line) {
                $line = trim($line);
                if (file_exists($line)) return $line;
            }
        }

        // 3. Common Windows Fallbacks
        if ($isWin) {
            $fallbacks = [
                'C:\laragon\bin\ffmpeg\bin\ffmpeg.exe',
                'C:\ffmpeg\bin\ffmpeg.exe',
                'C:\Users\HP\Downloads\ClipGrab\ffmpeg.exe',
                base_path('ffmpeg.exe'),
            ];
            foreach ($fallbacks as $fb) {
                if (file_exists($fb)) return $fb;
            }
        }

        Log::error("CompressReel: FFmpeg not found. Tried settings, command line, and common fallbacks.");
        return null;
    }

    protected function getFFprobePath(string $ffmpegPath): ?string
    {
        $dir = dirname($ffmpegPath);
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $ffprobeName = $isWin ? 'ffprobe.exe' : 'ffprobe';
        $path = $dir . DIRECTORY_SEPARATOR . $ffprobeName;
        if (file_exists($path)) {
            return $path;
        }
        
        $testCommand = $isWin ? 'where ffprobe.exe' : 'which ffprobe';
        $cmdPath = trim(shell_exec($testCommand) ?? '');
        if (!empty($cmdPath)) {
            $lines = explode("\n", $cmdPath);
            foreach ($lines as $line) {
                $line = trim($line);
                if (file_exists($line)) return $line;
            }
        }
        
        return null;
    }
}
