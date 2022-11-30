<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->hasRole('ADMINISTRATEUR')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function update(User $user)
    {
        return $user->hasRole('ADMINISTRATEUR')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }
}
