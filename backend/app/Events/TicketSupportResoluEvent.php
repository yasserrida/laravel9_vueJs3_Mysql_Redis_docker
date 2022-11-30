<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketSupportResoluEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $user_id;

    public function __construct(Ticket $ticket, string $user_id)
    {
        $this->ticket = $ticket;
        $this->user_id = $user_id;
    }
}
