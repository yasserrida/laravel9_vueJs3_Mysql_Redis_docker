<?php

namespace App\Events;

use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiredNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $notification;

    public function __construct(User $user, NotificationUser $notification)
    {
        $this->user = $user;
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ExpiredNotificationEvent');
    }
}
