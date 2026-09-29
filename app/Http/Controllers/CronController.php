<?php

namespace App\Http\Controllers;

use App\Models\CronJob;
use App\Models\CronJobLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{
    public function index()
    {
        $cronJobs = CronJob::with('schedule')->active()->get();
        foreach ($cronJobs as $job) {
            $job->last_run = Carbon::now();
            $job->next_run = Carbon::now()->addSeconds($job->schedule->interval);
            $job->save();

            $log = new CronJobLog();
            $log->cron_job_id = $job->id;
            $log->start_at = Carbon::now();

            try {
                // Execute the URL
                $response = Http::get($job->url);
                if ($response->successful()) {
                    $log->error = null;
                } else {
                    $log->error = 'HTTP Error: ' . $response->status();
                }
            } catch (\Exception $e) {
                $log->error = $e->getMessage();
            }

            $log->end_at = Carbon::now();
            $log->duration = $log->start_at->diffInSeconds($log->end_at);
            $log->save();
        }

        return 'Cron executed successfully';
    }
}
