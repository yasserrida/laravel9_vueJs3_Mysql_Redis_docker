<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class PasswrodError extends Exception
{
    public function report()
    {
        Log::channel('users')->error('updateSelf : Anncien mot de passe érroner');

        return true;
    }

    public function render()
    {
        report($this);
        $this->report();
        return response()->json(['message' => 'Anncien mot de passe érroner'], 500);
    }
}
