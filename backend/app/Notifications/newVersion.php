<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class newVersion extends Notification implements ShouldQueue
{
    use Queueable;

    public $version;

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'text' => 'Une nouvelle version a été deployer ' . $this->version . '. Veuiller reconnecter.',
            'created_at' => Carbon::today()->format('Y-m-d'),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'text' => 'Une nouvelle version a été deployer ' . $this->version . '. Veuiller reconnecter.',
            'created_at' => Carbon::today()->format('Y-m-d'),
        ];
    }
}
