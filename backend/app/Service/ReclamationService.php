<?php

namespace App\Service;

use App\Models\Media;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationService
{
    public function getAll(array $params): LengthAwarePaginator
    {
        $reclamations = Reclamation::select(
            'reclamations.id',
            'reclamations.canal',
            'reclamations.qualification',
            'reclamations.reclamant',
            'reclamations.status',
            'reclamations.date_cloture',
            'reclamations.niveau',
            'reclamations.jointes',
            'contrats.numero_contrat'
        )
            ->leftjoin('contrats', 'contrats.id', '=', 'reclamations.contrat_id')
            ->when(isset($params['solution']), fn ($query) => $query->where('solution', $params['solution']))
            ->when(isset($params['numero_contrat']), fn ($query) => $query->where('contrats.numero_contrat', $params['numero_contrat']))
            ->when(isset($params['canal']) && is_array($params['canal']), fn ($query) => $query->whereIn('canal', $params['canal']));

        $user = User::find(Auth::guard('api')->id());

        if ($user->hasRole('GESTIONNAIRE')) {
            $reclamations = $reclamations->join('users', 'users.id', '=', 'reclamations.user_id')
                ->where(fn ($query) => $query->orWhere('users.owner', $user->id)->orWhere('reclamations.user_id', $user->id));
        } elseif ($user->hasRole('RESPONSABLE')) {
            $reclamations = $reclamations->where('reclamations.user_id', $user->id);
        }

        if (isset($params['sort']) && isset($params['sordOrder'])) {
            $reclamations = $reclamations->orderBy('reclamations.' . $params['sort'], $params['sordOrder']);
        } else {
            $reclamations = $reclamations->orderBy('reclamations.created_at', 'DESC');
        }

        return $reclamations->paginate(8);
    }

    public function get(string $id): Reclamation
    {
        return Reclamation::with('contrat')->findOrFail($id);
    }

    public function store(Request $request): Reclamation
    {
        $data = $request->all();

        $filesPath = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $file_name = $file->store('reclamations');
                array_push($filesPath, $file_name);
                Media::create([
                    'file_name' => explode('/', $file_name)[1],
                    'contrat_id' => $data['contrat_id'],
                    'collection_name' => 'reclamations',
                    'name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        $data['jointes'] = implode(',', $filesPath);

        $reclamation = Reclamation::create($data);

        return $reclamation;
    }

    public function update(array $data, string $id): Reclamation
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update($data);

        return $reclamation;
    }
}
