<?php

namespace App\Listeners;

use App\Events\NotificationUserCreatedEvent;
use App\Models\User;
use App\Notifications\newNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotificationUserCreatedListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(NotificationUserCreatedEvent $event)
    {
        $users = User::join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->whereIn('roles.name', ['GESTIONNAIRE', 'GESTIONNAIRE_JUNIOR'])
            ->distinct('users.id')
            ->get();

            Notification::send($users, new newNotification($event->notif));
    }
}
