<?php

namespace App\Console\Commands;

use App\Models\Reel;
use Illuminate\Console\Command;

/**
 * Removes local/source copies of Bunny-hosted reels that are no longer required.
 *
 * Reel uploads are stored locally only as a TEMPORARY working copy while CompressReel
 * processes them. Once processing succeeds (or a failed job has passed the retention
 * window), the local copy is no longer needed because the real asset lives on Bunny.
 *
 * Safety rules (must never break existing functionality):
 *  - Local-only reels (no bunny_id) are NEVER touched — their local file is the source
 *    of truth and is served directly.
 *  - A source still being processed or still within its retry window is NEVER deleted.
 *  - Only Bunny-hosted reel local copies older than the retention window are removed,
 *    and only when the reel is fully processed (is_compressed + ready) OR has failed
 *    (compression_status = 3) beyond the retention window.
 */
class CleanupOrphanReelTemps extends Command
{
    protected $signature = 'reel:cleanup-orphan-temps {--hours=24 : Retention window in hours}';

    protected $description = 'Delete orphan/expired temporary local reel source files for Bunny-hosted reels';

    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $cutoff = time() - ($hours * 3600);
        $dir = realpath(public_path(getFilePath('reel')));

        $cleaned = 0;

        Reel::withoutGlobalScopes()
            ->whereNotNull('bunny_id')
            ->where('bunny_id', '!=', '')
            ->where(function ($q) {
                $q->whereNotNull('video_path')->where('video_path', '!=', '')
                  ->orWhereNotNull('compressed_video_path')->where('compressed_video_path', '!=', '');
            })
            ->chunkById(100, function ($reels) use (&$cleaned, $cutoff, $dir) {
                foreach ($reels as $reel) {
                    if (!$this->isExpiredAndDoneOrFailed($reel, $cutoff)) {
                        continue;
                    }

                    foreach ([$reel->video_path, $reel->compressed_video_path] as $file) {
                        if (empty($file)) {
                            continue;
                        }

                        $path = $this->resolveWithin($dir, $file);
                        if ($path !== null && is_file($path) && filemtime($path) <= $cutoff) {
                            @unlink($path);
                            $cleaned++;
                            $this->info("Deleted orphan reel temp: {$path}");
                        }
                    }
                }
            });

        $this->info("Cleanup complete. {$cleaned} file(s) removed.");
        return self::SUCCESS;
    }

    /**
     * Resolve a stored filename to an absolute path that is guaranteed to live
     * inside the reel upload directory, or null if it escapes it.
     */
    private function resolveWithin(string $dir, string $file): ?string
    {
        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $candidate = realpath($dir . basename($file));

        if ($candidate === false || !str_starts_with($candidate, $dir)) {
            return null;
        }

        return $candidate;
    }

    private function isExpiredAndDoneOrFailed(Reel $reel, int $cutoff): bool
    {
        if ((bool) $reel->is_compressed && $reel->bunny_status === 'ready') {
            // Fully processed — local copy is no longer needed.
            return true;
        }

        // Failed (not compressed) — only safe to remove after the retention window.
        if ((int) $reel->compression_status === 3 && strtotime((string) $reel->updated_at) <= $cutoff) {
            return true;
        }

        return false;
    }
}
