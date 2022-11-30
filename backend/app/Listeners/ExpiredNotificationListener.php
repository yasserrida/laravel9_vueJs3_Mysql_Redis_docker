<?php

namespace App\Listeners;

use App\Events\ExpiredNotificationEvent;
use App\Mail\ExpiredNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ExpiredNotificationListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(ExpiredNotificationEvent $event)
    {
        Mail::to($event->user['email'])->send(new ExpiredNotificationMail($event->notification));
    }
}
