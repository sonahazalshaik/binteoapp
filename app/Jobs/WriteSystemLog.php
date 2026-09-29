<?php

namespace App\Jobs;

use App\Models\SystemLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WriteSystemLog
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 5;
    public int $tries = 1;

    public function __construct(public array $logData) {}

    public function handle(): void
    {
        SystemLog::create($this->logData);
    }
}
