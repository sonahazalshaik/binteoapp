<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\CronSchedule;
use App\Services\Admin\SettingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronConfigurationController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }
    public function cronJobs()
    {
        $pageTitle = 'Cron Jobs';
        $data      = $this->service->getCronJobs();
        return view('admin.cron.index', array_merge(compact('pageTitle'), $data));
    }

    public function cronJobStore(Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'next_run'         => 'required',
            'cron_schedule_id' => 'required|integer',
            'url'              => 'required|url',
        ]);

        $this->service->createCronJob($request->only(['name', 'next_run', 'cron_schedule_id', 'url']));

        $notify[] = ['success', 'Cron job updated successfully'];
        return to_route('admin.cron.index')->withNotify($notify);
    }

    public function cronJobUpdate(Request $request)
    {
        $request->validate([
            'id'               => 'required|integer',
            'name'             => 'required',
            'next_run'         => 'required',
            'cron_schedule_id' => 'required|integer',
        ]);

        $cronJob = CronJob::findOrFail($request->id);

        if (!$cronJob->is_default) {
            $request->validate(['url' => 'required|url']);
        }

        $this->service->updateCronJob($request->only(['id', 'name', 'next_run', 'cron_schedule_id', 'url']));

        if (!$cronJob->is_default) {
            $cronJob->alias = titleToKey($request->name);
            $cronJob->save();
        }

        $notify[] = ['success', 'Cron job update successfully'];
        return back()->withNotify($notify);
    }

    public function cronJobDelete($id)
    {
        $cronJob = CronJob::where('is_default', 0)->where('id', $id)->firstOrFail();
        $cronJob->delete();

        CronJobLog::where('cron_job_id', $id)->delete();

        $notify[] = ['success', 'Cron job deleted successfully'];
        return back()->withNotify($notify);
    }

    public function schedule()
    {
        $pageTitle = 'Cron Schedules';
        $schedules = CronSchedule::paginate(getPaginate());
        return view('admin.cron.schedule', compact('pageTitle', 'schedules'));
    }

    public function scheduleStore(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'interval' => 'required|integer|gt:0',
        ]);

        $id = $request->id ?? 0;

        if ($id) {
            $schedule = CronSchedule::findOrFail($id);
            $message  = "Cron schedule updated successfully";
        } else {
            $schedule = new CronSchedule();
            $message  = "Cron schedule added successfully";
        }
        $schedule->name     = $request->name;
        $schedule->interval = $request->interval;
        $schedule->status   = Status::ENABLE;
        $schedule->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function scheduleStatus($id)
    {
        return CronSchedule::changeStatus($id);
    }

    public function schedulePause($id)
    {

        return CronJob::changeStatus($id, 'is_running');
    }

    public function scheduleLogs($id)
    {
        $cronJob   = CronJob::findOrFail($id);
        $pageTitle = $cronJob->name . " Cron Schedule Logs";
        $logs      = $this->service->getCronJobLogs($cronJob->id);
        return view('admin.cron.logs', compact('pageTitle', 'logs', 'cronJob'));
    }

    public function scheduleLogResolved($id)
    {
        $log        = CronJobLog::findOrFail($id);
        $log->error = null;
        $log->save();

        $notify[] = ['success', 'Cron log resolved successfully'];
        return back()->withNotify($notify);
    }

    public function logFlush($id)
    {
        $cronJob = CronJob::findOrFail($id);
        CronJobLog::where('cron_job_id', $cronJob->id)->delete();

        $notify[] = ['success', 'All logs flushed successfully'];
        return back()->withNotify($notify);
    }
}
