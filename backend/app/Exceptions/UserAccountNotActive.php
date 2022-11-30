<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UserAccountNotActive extends Exception
{
    public function report()
    {
        Log::channel('users')->error('GetUser : Utilisateur non activé');

        return true;
    }

    public function render()
    {
        report($this);
        $this->report();
        return response()->json(['message' => 'Utilisateur non activé'], 500);
    }
}
