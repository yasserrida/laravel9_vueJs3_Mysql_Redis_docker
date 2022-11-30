<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ExpiredNotificationEvent;
use App\Events\NotificationUserCreatedEvent;
use App\Events\TicketSupportAdminEvent;
use App\Events\TicketSupportResoluEvent;
use App\Events\UserDataUpdatedEvent;
use App\Listeners\ExpiredNotificationListener;
use App\Listeners\NotificationUserCreatedListener;
use App\Listeners\TicketSupportAdminListener;
use App\Listeners\TicketSupportResoluListener;
use App\Listeners\UserDataUpdatedListener;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ExpiredNotificationEvent::class => [ ExpiredNotificationListener::class ],
        NotificationUserCreatedEvent::class => [ NotificationUserCreatedListener::class ],
        TicketSupportAdminEvent::class => [ TicketSupportAdminListener::class ],
        TicketSupportResoluEvent::class => [ TicketSupportResoluListener::class ],
        UserDataUpdatedEvent::class => [ UserDataUpdatedListener::class ],
    ];

    protected $subscribe = [

    ];

    public function shouldDiscoverEvents()
    {
        return false;
    }

    public function boot()
    {
    }
}
