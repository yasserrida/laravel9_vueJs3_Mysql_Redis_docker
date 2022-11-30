<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ticketResolu extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Ticket Support')
                    ->line('Votre demande ' . $this->ticket->title . 'a été traité.')
                    ->action('voir', '/ticket');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'text' => 'Votre demande ' . $this->ticket->title . 'a été traité.',
            'route' => '/ticket',
            'created_at' => $this->ticket['updated_at'],
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'text' => 'Votre demande ' . $this->ticket->title . 'a été traité.',
            'route' => '/ticket',
            'created_at' => $this->ticket['updated_at'],
        ];
    }
}
