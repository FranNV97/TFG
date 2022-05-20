<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public static function getNotifications($id) {
        $notifications = Notification::where('user_id', $id)->get();

        return $notifications;
    }

    public static function newNotification($message, $typeElement, $idElement, $userId) {
        $notification = new Notification();

        if($message != null) {
            $notification->message = $message;
        }

        if($typeElement != null) {
            $notification->typeElement = $typeElement;
        }

        if($idElement != null) {
            $notification->idElement = $idElement;
        }

        if($userId != null) {
            $notification->user_id = $userId;
        }

        $notification->save();

        return $notification->id;
    }

    public static function deleteNotification($id) {
        $notification = Notification::findOrFail($id);

        $notification->delete();
    }

    public static function deleteNotificationsSameElement($typeElement, $idElement) {
        Notification::where('typeElement', $typeElement)->where('idElement', $idElement)->delete();
    }
}
