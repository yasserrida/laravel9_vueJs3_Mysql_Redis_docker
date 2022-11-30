<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class updateUser extends Notification implements ShouldQueue
{
    use Queueable;

    public $version;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'text' => 'Votre profile a été mise à jour par les responsables. Veuiller reconnecter pour prend en compte les changements.',
            'created_at' => Carbon::today()->format('Y-m-d'),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'text' => 'Votre profile a été mise à jour par les responsables. Veuiller reconnecter pour prend en compte les changements.',
            'created_at' => Carbon::today()->format('Y-m-d'),
        ];
    }
}
