<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDataUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }
}
