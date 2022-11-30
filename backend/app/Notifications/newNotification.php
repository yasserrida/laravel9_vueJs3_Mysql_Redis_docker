<?php

namespace App\Notifications;

use App\Models\NotificationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class newNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $notification;

    public function __construct(NotificationUser $notification)
    {
        $this->notification = $notification;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->notification['name'])
                    ->line($this->notification['name']);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'text' => $this->notification['name'],
            'created_at' => $this->notification['created_at'],
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'text' => $this->notification['name'],
            'created_at' => $this->notification['created_at'],
        ];
    }
}
