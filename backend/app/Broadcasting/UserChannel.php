<?php

namespace App\Broadcasting;

use App\Models\User;

class UserChannel
{
    public function __construct()
    {
    }

    public function join(User $user, $id): bool
    {
        return (int) $user->id === (int) $id;
    }
}
