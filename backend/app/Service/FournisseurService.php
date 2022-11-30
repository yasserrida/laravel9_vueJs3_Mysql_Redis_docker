<?php

namespace App\Service;

use App\Helpers\StringHelper;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\Cache;

class FournisseurService
{
    public function clearCache(): void
    {
        Cache::forget('Fournisseurs');
    }

    public function getAll()
    {
        return Cache::remember('Fournisseurs', 604800, fn () => Fournisseur::select('id', 'name', 'slug')->orderBy('id', 'ASC')->get());
    }

    public function get(string $id): Fournisseur
    {
        return Fournisseur::findOrFail($id);
    }

    public function store(array $data): Fournisseur
    {
        $this->clearCache();

        $fournisseur = Fournisseur::create($data);

        return $fournisseur;
    }

    public function update(array $data, string $id): Fournisseur
    {
        $this->clearCache();

        $fournisseur = Fournisseur::findOrFail($id);

        $data['slug'] = StringHelper::toSlug($data['name']);

        $fournisseur->update($data);

        return $fournisseur;
    }

    public function delete(string $id): void
    {
        $this->clearCache();

        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
    }
}
