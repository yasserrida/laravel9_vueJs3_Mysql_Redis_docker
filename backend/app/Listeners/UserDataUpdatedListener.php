<?php

namespace App\Listeners;

use App\Events\UserDataUpdatedEvent;
use App\Models\User;
use App\Notifications\updateUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class UserDataUpdatedListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(UserDataUpdatedEvent $event)
    {
        $user = User::findOrFail($event->user_id);

        Notification::send($user, new updateUser());
    }
}
