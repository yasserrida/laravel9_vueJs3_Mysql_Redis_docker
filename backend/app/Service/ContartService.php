<?php

namespace App\Service;

use App\Exceptions\ContratAlreadyExiste;
use App\Models\Contrat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ContartService
{
    protected DocumentsService $documentService;

    public function __construct(DocumentsService $DocumentService)
    {
        $this->documentService = $DocumentService;
    }

    public function getAll(array $params): LengthAwarePaginator
    {
        return Contrat::orderBy('contrats.id', 'ASC')
            ->when(isset($params['contrat_id']), fn ($query) => $query->where('id', $params['nom_contrat']))
            ->when(isset($params['produit_id']), fn ($query) => $query->where('produit_id', $params['produit_id']))
            ->whereNull('contrats.deleted_at')
            ->paginate(10);
    }

    public function getByNum(string $id): Contrat
    {
        return Contrat::where('numero_contrat', $id)
            ->whereNull('contrats.deleted_at')
            ->firstOrFail();
    }

    public function getById(string $id): Contrat
    {
        return Contrat::where('contrats.id', $id)
            ->whereNull('contrats.deleted_at')
            ->firstOrFail();
    }

    public function store(Request $request): Contrat
    {
        $data = $request->all();

        if (!isset($data['id'])) {
            if (isset($data['numero_contrat'])) {
                $existeContrat = Contrat::where('numero_contrat', $data['numero_contrat'])->first();
                if ($existeContrat) {
                    throw new ContratAlreadyExiste();
                }
            }

            $contrat = Contrat::create($data);

            if (!isset($data['numero_contrat'])) {
                $contrat->numero_contrat = strval($contrat->id);
            }

            if ($request->hasFile('file')) {
                $contrat->rib = $this->documentService->store($request, strval($contrat->id), 'contrats', 'rib');
            }

            $contrat->save();
        } else {
            $contrat = Contrat::findOrFail($data['id']);

            $temp = $this->documentService->update($request, strval($data['id']), 'contrats', 'rib');
            if ($temp != '') {
                $data['rib'] = $temp;
            }

            $contrat->update($data);
        }

        return Contrat::where('contrats.id', $contrat->id)->first();
    }

    public function update(Request $request, string $id): Contrat
    {
        $data = $request->all();
        $contrat = Contrat::findOrFail($id);

        if ($request->hasFile('file')) {
            $data['rib'] = $this->documentService->updateRibDocuments($request, strval($id), 'contrats', 'rib');
        }

        $contrat->update($data);

        return $contrat;
    }

    public function getDocuments(string $id): Collection
    {
        return $this->documentService->getDocuments('contrat_id', strval($id), 'contrats', null);
    }
}
