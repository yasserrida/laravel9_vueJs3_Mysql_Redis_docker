<?php

namespace App\Exceptions;

use Exception;

class UserDataAlreadyUsed extends Exception
{
    public function render($request)
    {
        return response()->json(['message' => 'Numéro de poste ou email déja affecter a un autre utilisateur'], 500);
    }
}
