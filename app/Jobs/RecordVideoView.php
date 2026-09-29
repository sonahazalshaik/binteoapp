<?php

namespace App\Jobs;

use App\Models\ViewLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordVideoView implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 10;
    public int $tries = 2;

    public function __construct(
        public int $userId,
        public int $videoId,
        public string $device
    ) {}

    public function handle(): void
    {
        $info = getIpInfo();
        $raw = $info['country'] ?? [];
        $country = is_array($raw) ? null : (string) $raw;

        ViewLog::updateOrCreate(
            ['user_id' => $this->userId, 'video_id' => $this->videoId],
            ['updated_at' => now(), 'device' => $this->device, 'country' => $country]
        );
    }
}
