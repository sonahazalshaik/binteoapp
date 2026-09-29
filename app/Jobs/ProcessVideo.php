<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public int $timeout = 600;
    public int $tries = 2;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle(): void
    {
        if (!$this->video->video_path || $this->video->video_path === '') {
            Log::info("ProcessVideo: Skipping Bunny-uploaded video #{$this->video->id} (no local video_path)");
            return;
        }

        $videoPath = public_path(getFilePath('video') . '/' . $this->video->video_path);
        if (!file_exists($videoPath)) {
            Log::error("Video file not found for processing: $videoPath");
            return;
        }

        $ffmpegPath = $this->getFFmpegPath() ?? 'ffmpeg';

        $outputBaseDir = 'assets/videos/processed/' . $this->video->id;
        $fullOutputBaseDir = public_path($outputBaseDir);
        
        if (!file_exists($fullOutputBaseDir)) {
            mkdir($fullOutputBaseDir, 0755, true);
        }

        // HLS Resolutions and Bitrates
        $variants = [
            ['res' => '640x360',  'bitrate' => '800k',  'label' => '360p'],
            ['res' => '1280x720', 'bitrate' => '2500k', 'label' => '720p'],
            ['res' => '1920x1080','bitrate' => '5000k', 'label' => '1080p']
        ];

        try {
            // 0. Extract Duration
            $durationOutput = shell_exec(escapeshellarg($ffmpegPath) . " -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"$videoPath\"");
            if ($durationOutput) {
                $seconds = round((float)$durationOutput);
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds / 60) % 60);
                $seconds = $seconds % 60;
                $formattedDuration = ($hours > 0 ? sprintf("%02d:", $hours) : "") . sprintf("%02d:%02d", $minutes, $seconds);
                $this->video->update(['duration' => $formattedDuration]);
            }

            // 1. Generate Thumbnail if missing
            if (!$this->video->thumbnail_path) {
                $thumbName = uniqid() . '.jpg';
                $thumbRelativePath = getFilePath('thumbnail') . '/' . $thumbName;
                $thumbAbsPath = public_path($thumbRelativePath);
                
                if (!file_exists(dirname($thumbAbsPath))) {
                    mkdir(dirname($thumbAbsPath), 0755, true);
                }

                $thumbCommand = sprintf(
                    '%s -i %s -ss 00:00:05.000 -vframes 1 %s 2>&1',
                    escapeshellarg($ffmpegPath),
                    escapeshellarg($videoPath),
                    escapeshellarg($thumbAbsPath)
                );
                shell_exec($thumbCommand);

                if (file_exists($thumbAbsPath)) {
                    $this->video->update(['thumbnail_path' => $thumbName]);
                }
            }

            $masterPlaylistContent = "#EXTM3U\n#EXT-X-VERSION:3\n";

            // 2. Transcode to HLS variants (Optional: can be slow for Sync)
            foreach ($variants as $variant) {
                $label = $variant['label'];
                $res = $variant['res'];
                $bitrate = $variant['bitrate'];
                
                $variantOutputDir = "$fullOutputBaseDir/$label";
                if (!file_exists($variantOutputDir)) {
                    mkdir($variantOutputDir, 0755, true);
                }
                
                $segmentPath = "$variantOutputDir/playlist.m3u8";
                $tsPattern = "$variantOutputDir/segment_%03d.ts";

                $command = sprintf(
                    '%s -i %s -s %s -c:v libx264 -b:v %s -g 48 -keyint_min 48 -sc_threshold 0 -c:a aac -b:a 128k -f hls -hls_time 4 -hls_playlist_type vcd -hls_segment_filename %s %s 2>&1',
                    escapeshellarg($ffmpegPath),
                    escapeshellarg($videoPath),
                    escapeshellarg($res),
                    escapeshellarg($bitrate),
                    escapeshellarg($tsPattern),
                    escapeshellarg($segmentPath)
                );
                shell_exec($command);

                if (file_exists($segmentPath)) {
                    $bandwidth = $label === '360p' ? 800000 : ($label === '720p' ? 2500000 : 5000000);
                    $masterPlaylistContent .= "#EXT-X-STREAM-INF:BANDWIDTH=$bandwidth,RESOLUTION=$res\n$label/playlist.m3u8\n";
                }
            }

            // 3. Write Master Playlist
            $masterPath = "$outputBaseDir/master.m3u8";
            file_put_contents(public_path($masterPath), $masterPlaylistContent);

            // 4. Update Video Status
            $this->video->update([
                'hls_path' => $masterPath,
                'status' => 'ready'
            ]);

            Log::info("HLS Processing completed for video: " . $this->video->id);

        } catch (\Exception $e) {
            Log::error("HLS Processing failed: " . $e->getMessage());
            $this->video->update(['status' => 'ready']);
        }
    }

    protected function getFFmpegPath(): ?string
    {
        $gs = \App\Models\GeneralSetting::first();
        if ($gs && !empty($gs->ffmpeg_path)) {
            return $gs->ffmpeg_path;
        }

        $testCommand = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where ffmpeg' : 'which ffmpeg';
        $path = trim(shell_exec($testCommand) ?? '');

        return !empty($path) ? explode("\n", $path)[0] : null;
    }
}
