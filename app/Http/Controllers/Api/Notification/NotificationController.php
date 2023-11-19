<?php

namespace App\Http\Controllers\Api\Notification;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    //user notifications
    public function userNotifications($user_id)
    {
        $notifications = User::find($user_id)->notifications;

        return response()->json([
            'status' => true,
            'message' => 'User notifications',
            'data' => $notifications,
        ]);
    }
}
