<?php

namespace App\Http\Controllers\Admin;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Rules\FileTypeValidate;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\Request;

class AdminController extends Controller {

    protected $service;

    public function __construct(AdminDashboardService $service)
    {
        $this->service = $service;
    }

    public function dashboard() {
        $pageTitle   = 'Dashboard';
        $widget      = $this->service->getDashboardWidgets();
        $chart       = $this->service->getDashboardCharts();
        $deposit     = $this->service->getDepositStats();
        $withdrawals = $this->service->getWithdrawalStats();

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart', 'deposit', 'withdrawals'));
    }

    public function depositAndWithdrawReport(Request $request) {
        $report = $this->service->getDepositWithdrawReport($request);
        return response()->json($report);
    }

    public function transactionReport(Request $request) {
        $report = $this->service->getTransactionReport($request);
        return response()->json($report);
    }

    public function profile() {
        $pageTitle = 'Profile';
        $admin     = $this->service->getAdminProfile();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request) {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $user = $this->service->getAdminProfile();

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $this->service->updateAdminProfile($request);
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password() {
        $pageTitle = 'Password Setting';
        $admin     = $this->service->getAdminProfile();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $updated = $this->service->updateAdminPassword($request);

        if (!$updated) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications() {
        $pageTitle    = 'Notifications';
        $data         = $this->service->getNotifications();
        return view('admin.notifications', array_merge(compact('pageTitle'), $data));
    }

    public function notificationRead($id) {
        $url = $this->service->markNotificationRead($id);
        return redirect($url ?? url()->previous());
    }

    public function requestReport() {
        $pageTitle = 'Your Listed Report & Request';
        $reports = [];
        return view('admin.reports', compact('reports', 'pageTitle'));
    }

    public function reportSubmit(Request $request) {
        $request->validate([
            'type'    => 'required|in:bug,feature',
            'message' => 'required',
        ]);
        $notify[] = ['success', 'Report submitted successfully'];
        return back()->withNotify($notify);
    }

    public function readAllNotification() {
        $this->service->markAllNotificationsRead();
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification() {
        $this->service->deleteAllNotifications();
        $notify[] = ['success', 'Notifications deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id) {
        $this->service->deleteNotification($id);
        $notify[] = ['success', 'Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash) {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function checkSpace(){
        return response()->json($this->service->checkStorageSpace());
    }

}
