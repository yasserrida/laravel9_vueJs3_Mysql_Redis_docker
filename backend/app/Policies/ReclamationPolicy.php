<?php

namespace App\Policies;

use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ReclamationPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAbleTo('RECLAMATION-CREATE')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function update(User $user)
    {
        return $user->isAbleTo('RECLAMATION-UPDATE')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }
}
