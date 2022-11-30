<?php

namespace App\Service;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DocumentsService
{
    public function store(Request $request, string $contratID, string $collection_name, string $collection_subname): string
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->store($collection_name);

            $temp = explode('/', $file_name);

            Media::create([
                'file_name' => $temp[count($temp) - 1],
                'contrat_id' => $contratID,
                'collection_name' => $collection_name,
                'collection_subname' => $collection_subname,
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
            ]);

            return $file_name;
        }

        return '';
    }

    public function storeMultiple(Request $request, string $field, string $contratID, string $collection_name, string $collection_subname): array
    {
        $filesPath = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $file_name = $file->store($collection_name);
                array_push($filesPath, $file_name);

                $temp = explode('/', $file_name);

                Media::create([
                    'file_name' => $temp[count($temp) - 1],
                    $field => $contratID,
                    'collection_name' => $collection_name,
                    'collection_subname' => $collection_subname,
                    'name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return $filesPath;
    }

    public function update(Request $request, string $contratID, string $collection_name, string $collection_subname): string
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->store($collection_name);
            $temp = explode('/', $file_name);

            Media::where('contrat_id', $contratID)
                ->where('collection_name', $collection_name)
                ->where('collection_subname', $collection_subname)
                ->delete();

            Media::create([
                'file_name' => $temp[count($temp) - 1],
                'contrat_id' => $contratID,
                'collection_name' => $collection_name,
                'collection_subname' => $collection_subname,
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
            ]);

            return $file_name;
        }

        return '';
    }

    public function updateMultiple(Request $request, string $field, string $contratID, string $collection_name, string $collection_subname): array
    {
        $filesPath = [];

        if ($request->hasFile('files')) {
            Media::where($field, $contratID)
                ->where('collection_name', $collection_name)
                ->where('collection_subname', $collection_subname)
                ->delete();

            foreach ($request->file('files') as $file) {
                $file_name = $file->store($collection_name);
                array_push($filesPath, $file_name);

                $temp = explode('/', $file_name);

                Media::create([
                    'file_name' => $temp[count($temp) - 1],
                    $field => $contratID,
                    'collection_name' => $collection_name,
                    'collection_subname' => $collection_subname,
                    'name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return $filesPath;
    }

    public function storeMultipleKey(Request $request, string $contratID, string $personneID, string $collection_name): array
    {
        $filesPath = [];

        foreach ($request->all() as $key => $item) {
            $uploadedFile = $request->file($key);
            $file_name = $uploadedFile->store($collection_name . '/' . $key);
            array_push($filesPath, $file_name);

            Media::where('personne_id', $personneID)
                ->where('collection_name', $collection_name)
                ->where('collection_subname', $key)
                ->delete();

            $temp = explode('/', $file_name);

            Media::create([
                'file_name' => $temp[count($temp) - 1],
                'contrat_id' => $contratID != '000' ? $contratID : null,
                'personne_id' => $personneID,
                'collection_name' => $collection_name,
                'collection_subname' => $key,
                'name' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getClientMimeType(),
            ]);
        }

        return $filesPath;
    }

    public function getDocuments(string $field, string $id, string $collection_name, ?string $collection_subname = null): Collection
    {
        return Media::where($field, $id)
            ->where('collection_name', $collection_name)
            ->when(isset($collection_subname), fn ($query) => $query->where('collection_subname', $collection_subname))
            ->whereNull('deleted_at')
            ->get();
    }

    public function storeRibDocuments(Request $request): string|null
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->store('contrats/rib');

            return explode('/', strval($file_name))[2];
        }

        return null;
    }

    public function updateRibDocuments(Request $request, string $contratID, string $collection_name, string $collection_subname): string
    {
        $file = $request->file('file');
        $file_name = $file->store('contrats/rib');

        Media::where('contrat_id', $contratID)
            ->where('collection_name', $collection_name)
            ->where('collection_subname', $collection_subname)
            ->delete();

        Media::create([
            'file_name' => explode('/', strval($file_name))[2],
            'contrat_id' => $contratID,
            'collection_name' => $collection_name,
            'collection_subname' => $collection_subname,
            'name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return explode('/', strval($file_name))[2];
    }

    public function deleteDocuments(string $id): void
    {
        Media::where('id', $id)->delete();
    }
}
