<?php

namespace App\Policies;

use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NotificationUserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAbleTo('NOTIFICATION-CREATE')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function update(User $user, NotificationUser $notification)
    {
        return $user->isAbleTo('NOTIFICATION-UPDATE') && $notification->user_id == $user->id
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function delete(User $user, NotificationUser $notification)
    {
        return $user->isAbleTo('NOTIFICATION-DELETE') && $notification->user_id == $user->id
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }
}
