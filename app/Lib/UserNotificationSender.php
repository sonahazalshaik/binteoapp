<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\NotificationTemplate;
use App\Models\User;

/**
 * Class UserNotificationSender
 * 
 * This class handles the sending of notifications to users based on specified criteria.
 */
class UserNotificationSender
{
    private $isSingleNotification = false;

    public function notificationToAll($request)
    {
        if (!$this->isTemplateEnabled($request->via)) {
            return $this->redirectWithNotify('warning', 'Default notification template is not enabled');
        }

        $handleSelectedUser = $this->handleSelectedUsers($request);
        if (!$handleSelectedUser) {
            return $this->redirectWithNotify('error', "Ensure that the user field is populated when sending an email to the designated user group");
        }

        $userQuery      = $this->getUserQuery($request);
        $totalUserCount = $this->getTotalUserCount($userQuery, $request);

        if ($totalUserCount <= 0) {
            return $this->redirectWithNotify('error', "Notification recipients were not found among the selected user base.");
        }

        $imageUrl = $this->handlePushNotificationImage($request);
        $users    = $this->getUsers($userQuery, $request->start, $request->batch);

        $this->sendNotifications($users, $request, $imageUrl);

        return $this->manageSessionForNotification($totalUserCount, $request);
    }

    public function notificationToSingle($request, $userId)
    {
        if (!$this->isTemplateEnabled($request->via)) {
            return $this->redirectWithNotify('warning', 'Default notification template is not enabled');
        }
        $this->isSingleNotification = true;
        $imageUrl = $this->handlePushNotificationImage($request);
        $user     = User::findOrFail($userId);
        $this->sendNotifications($user, $request, $imageUrl, true);

        return $this->redirectWithNotify("success", "Notification sent successfully");
    }

    private function isTemplateEnabled($via)
    {
        return NotificationTemplate::where('act', 'DEFAULT')->where($via . '_status', Status::ENABLE)->exists();
    }

    private function redirectWithNotify($type, $message)
    {
        $notify[] = [$type, $message];
        return back()->withNotify($notify);
    }

    private function handleSelectedUsers($request)
    {
        if ($request->being_sent_to == 'selectedUsers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['user' => session()->get('SEND_NOTIFICATION')['user']]);
            } elseif (!$request->user || !is_array($request->user) || empty($request->user)) {
                return false;
            }
        }
        return true;
    }

    private function getUserQuery($request)
    {
        $scope = $request->being_sent_to;
        return User::oldest()->active()->$scope();
    }

    private function getTotalUserCount($userQuery, $request)
    {
        if (session()->has("SEND_NOTIFICATION")) {
            $totalUserCount = session('SEND_NOTIFICATION')['total_user'];
        } else {
            $totalUserCount = (clone $userQuery)->count() - ($request->start - 1);
        }
        return $totalUserCount;
    }

    private function handlePushNotificationImage($request)
    {
        if ($request->via == 'push') {
            if ($request->hasFile('image')) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
                session()->put('PUSH_IMAGE_URL', $imageUrl);
                return $imageUrl;
            }
            return $this->isSingleNotification ? null : session()->get('PUSH_IMAGE_URL');
        }
        return null;
    }

    private function getUsers($userQuery, $start, $batch)
    {
        return (clone $userQuery)->skip($start - 1)->limit($batch)->get();
    }

    private function sendNotifications($users, $request, $imageUrl, $isSingleNotification = false)
    {
        if (!$isSingleNotification) {
            foreach ($users as $user) {
                notify($user, 'DEFAULT', [
                    'subject' => $request->subject,
                    'message' => $request->message,
                ], [$request->via], pushImage: $imageUrl);
            }
        } else {
            notify($users, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }
    }

    private function manageSessionForNotification($totalUserCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData                = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token', 'image');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_user'] = $totalUserCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalUserCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " notifications were sent successfully";
            $url     = route("admin.users.index");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . "  notifications were sent successfully";
            $url     = route("admin.users.index") . "?email_sent=yes";
        }

        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }
}
