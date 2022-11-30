<?php

namespace App\Listeners;

use App\Events\TicketSupportAdminEvent;
use App\Models\User;
use App\Notifications\newTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class TicketSupportAdminListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(TicketSupportAdminEvent $event)
    {
        $users = User::join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'roles.id', 'role_user.role_id')
            ->where('roles.name', 'ADMINISTRATEUR')
            ->distinct('users.name')
            ->get();

        Notification::send($users, new newTicket($event->ticket));
    }
}
