<?php

namespace App\Policies;

use App\Models\AttributionDossier;
use App\Models\Contrat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ContratPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAbleTo('CONTRAT-CREATE')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function update(User $user)
    {
        return $user->isAbleTo('CONTRAT-UPDATE') && $user->isAbleTo('OPERATION-CHECK')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function upload_documents(User $user)
    {
        return $user->isAbleTo('CONTRAT-UPLOAD')
            ? Response::allow()
            : Response::deny('Cette action n\'est pas autorisée');
    }

    public function canManipulate(User $user, Contrat $contrat)
    {
        if (! $user->isAbleTo('CONTRAT-CREATE') || ! $user->isAbleTo('CONTRAT-UPDATE')) {
            return false;
        }
        if ($user->hasRole('ADMINISTRATEUR') || $user->hasRole('RESPONSABLE')) {
            return true;
        }

        return false;
    }
}
