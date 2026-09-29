<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\RequiredConfig;
use App\Models\NotificationTemplate;
use App\Rules\FileTypeValidate;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function globalEmail() {
        $pageTitle = 'Global Email Template';
        return view('admin.notification.global_email_template', compact('pageTitle'));
    }

    public function globalEmailUpdate(Request $request) {
        $request->validate([
            'email_from'      => 'required|email|string|max:40',
            'email_from_name' => 'required',
            'email_template'  => 'required',
        ]);

        $general                  = gs();
        $general->email_from      = $request->email_from;
        $general->email_from_name = $request->email_from_name;
        $general->email_template  = $request->email_template;
        $general->save();

        RequiredConfig::configured('notification_template');

        $notify[] = ['success', 'Global email template updated successfully'];
        return back()->withNotify($notify);
    }

    public function globalSms() {
        $pageTitle = 'Global Sms Template';
        return view('admin.notification.global_sms_template', compact('pageTitle'));
    }

    public function globalSmsUpdate(Request $request) {
        $request->validate([
            'sms_from'     => 'required|string|max:40',
            'sms_template' => 'required',
        ]);

        $general               = gs();
        $general->sms_from     = $request->sms_from;
        $general->sms_template = $request->sms_template;
        $general->save();

        $notify[] = ['success', 'Global sms template updated successfully'];
        return back()->withNotify($notify);
    }

    public function globalPush() {
        $pageTitle = 'Global Push Notification Template';
        return view('admin.notification.global_push_template', compact('pageTitle'));
    }

    public function globalPushUpdate(Request $request) {
        $request->validate([
            'push_template' => 'required',
            'push_title'    => 'required',
        ]);

        $general                = gs();
        $general->push_template = $request->push_template;
        $general->push_title    = $request->push_title;
        $general->save();

        $notify[] = ['success', 'Global push notification template updated successfully'];
        return back()->withNotify($notify);
    }

    public function templates() {
        $pageTitle = 'Notification Templates';
        $templates = NotificationTemplate::orderBy('name')->get();
        return view('admin.notification.template.index', compact('pageTitle', 'templates'));
    }

    public function templateEdit($type, $id) {
        $template  = NotificationTemplate::findOrFail($id);
        $pageTitle = $template->name;
        return view('admin.notification.template.' . $type, compact('pageTitle', 'template'));
    }

    public function templateUpdate(Request $request, $type, $id) {
        $this->service->updateNotificationTemplate($request, $type, $id);

        $notify[] = ['success', 'Notification template updated successfully'];
        return back()->withNotify($notify);
    }

    public function emailSetting() {
        $pageTitle = 'Email Notification Settings';
        return view('admin.notification.email_setting', compact('pageTitle'));
    }

    public function emailSettingUpdate(Request $request) {
        $this->service->updateEmailSetting($request);
        $notify[] = ['success', 'Email settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function emailTest(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $error = $this->service->sendTestEmail($request->email);
        } catch (\Exception $e) {
            $notify[] = ['info', 'Please enable from general settings'];
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        if ($error) {
            $notify[] = ['error', $error];
        } else {
            $notify[] = ['success', 'Email sent to ' . $request->email . ' successfully'];
        }

        return back()->withNotify($notify);
    }

    public function smsSetting() {
        $pageTitle = 'SMS Notification Settings';
        return view('admin.notification.sms_setting', compact('pageTitle'));
    }

    public function smsSettingUpdate(Request $request) {
        $this->service->updateSmsSetting($request);
        $notify[] = ['success', 'Sms settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function smsTest(Request $request) {
        $request->validate(['mobile' => 'required']);

        try {
            $error = $this->service->sendTestSms($request->mobile);
        } catch (\Exception $e) {
            $notify[] = ['info', 'Please enable from general settings'];
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        if ($error) {
            $notify[] = ['error', $error];
        } else {
            $notify[] = ['success', 'SMS sent to ' . $request->mobile . 'successfully'];
        }

        return back()->withNotify($notify);
    }

    public function pushSetting() {
        $pageTitle  = 'Push Notification Settings';
        $fileExists = file_exists(getFilePath('pushConfig') . '/push_config.json');
        return view('admin.notification.push_setting', compact('pageTitle', 'fileExists'));
    }
    public function pushSettingUpdate(Request $request) {
        try {
            $this->service->updatePushSetting($request);
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', 'Firebase settings updated successfully'];
        return back()->withNotify($notify);
    }
    public function pushSettingUpload(Request $request) {
        $request->validate([
            'file' => ['required', new FileTypeValidate(['json'])],
        ]);
        try {
            $this->service->uploadPushConfig($request->file('file'));
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload your file'];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', 'Configuration file uploaded successfully'];
        return back()->withNotify($notify);
    }
    public function pushSettingDownload() {
        $filePath = getFilePath('pushConfig') . '/push_config.json';
        if (!file_exists($filePath)) {
            $notify[] = ['success', "File not found"];
            return back()->withNotify($notify);
        }
        return response()->download($filePath);
    }

}
