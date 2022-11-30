<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ContratAlreadyExiste extends Exception
{
    public function report()
    {
        Log::channel('contrats')->error('Store : Numéro contrat déjà existe');

        return true;
    }

    public function render($request)
    {
        report($this);
        $this->report();
        return response()->json(['message' => 'Numéro contrat déjà existe'], 500);
    }
}
