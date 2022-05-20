<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotificationsView()
    {

        $user = User::getUser(Auth::user()->id);
        $notifications = Notification::getNotifications($user->id);

        return view('notifications', ['user' => $user, 'notifications' => $notifications, 'badge' => count($notifications)]);
    }

    public function getNotifications($id)
    {

        $notifications = Notification::getNotifications($id);

        return $notifications;
    }

    public function newNotification(Request $request)
    {

        return Notification::newNotification($request->message, $request->typeElement, $request->idElement, $request->user_id);
    }

    public function deleteNotification($id)
    {

        Notification::deleteNotification($id);
    }

    public function deleteNotificationsSameElement(Request $request)
    {

        Notification::deleteNotificationsSameElement($request->typeElement, $request->idElement);
    }
}
