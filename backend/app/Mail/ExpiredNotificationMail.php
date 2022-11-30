<?php

namespace App\Mail;

use App\Models\NotificationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiredNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public NotificationUser $notification;

    public function __construct(NotificationUser $notification)
    {
        $this->notification = $notification;
    }

    public function build()
    {
        return $this->from('support@yasser.com', 'SUPPORT')
            ->view('emails.ExpiredNotifications')
            ->with([
                'notification' => $this->notification,
            ]);
    }
}
