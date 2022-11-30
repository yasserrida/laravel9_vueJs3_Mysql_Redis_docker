<?php

namespace App\Listeners;

use App\Events\TicketSupportResoluEvent;
use App\Models\User;
use App\Notifications\ticketResolu;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class TicketSupportResoluListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(TicketSupportResoluEvent $event)
    {
        $user = User::findOrFail($event->user_id);

        Notification::send($user, new ticketResolu($event->ticket));
    }
}
