<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function getAll()
    {
        $user = User::find(Auth::guard('api')->id());

        return $user->notifications;
    }

    public function getUnreaded()
    {
        $user = User::find(Auth::guard('api')->id());

        return $user->unreadNotifications;
    }

    public function markAsRead()
    {
        $user = User::find(Auth::guard('api')->id());

        $user->unreadNotifications->markAsRead();
    }
}
