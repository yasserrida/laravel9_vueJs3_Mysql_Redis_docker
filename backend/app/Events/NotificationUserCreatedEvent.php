<?php

namespace App\Events;

use App\Models\NotificationUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationUserCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notif;

    public function __construct(NotificationUser $notif)
    {
        $this->notif = $notif;
    }
}
