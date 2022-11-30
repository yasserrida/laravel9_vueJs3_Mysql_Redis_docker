<?php

namespace App\Service\Api\V1;

use App\Models\Contrat;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainService
{
    public function storeContrat(Request $request): Contrat
    {
        $data = $request->all();

        if (isset($data['numero_contrat'])) {
            $existeContrat = Contrat::where('numero_contrat', $data['numero_contrat'])->first();
            if ($existeContrat) {
                throw new ErrorException('Numéro contrat déjà existe');
            }
        }

        $data['from'] = 'contrat';
        $contrat = Contrat::create($data);

        if (!isset($data['numero_contrat'])) {
            $contrat->numero_contrat = strval($contrat->id);
            $contrat->save();
        }

        Log::channel('api')->info('storeContrat : Success => ' . $contrat->numero_contrat);

        return Contrat::where('id', $contrat->id)->first();
    }
}
