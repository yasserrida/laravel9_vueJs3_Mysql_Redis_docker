<?php

namespace App\Service;

use App\Helpers\StringHelper;
use App\Models\Produit;
use Illuminate\Support\Facades\Cache;

class ProduitService
{
    public function clearCache(): void
    {
        Cache::forget('Produits');
    }

    public function getAll()
    {
        return Cache::remember('Produits', 604800, fn () => Produit::select('id', 'name', 'slug', 'gamme_id', 'fournisseur_id')->with([
            'fournisseur' => fn ($query) => $query->select('id', 'name', 'slug'),
            'gamme' => fn ($query) => $query->select('id', 'name', 'slug')
        ])
            ->orderBy('id', 'ASC')->get());
    }

    public function get(string $id): Produit
    {
        return Produit::with(['fournisseur', 'gamme'])->findOrFail($id);
    }

    public function store(array $data): Produit
    {
        $this->clearCache();

        $produit = Produit::create($data);

        return $produit;
    }

    public function update(array $data, string $id): Produit
    {
        $this->clearCache();

        $produit = Produit::findOrFail($id);

        $data['slug'] = StringHelper::toSlug($data['name']);

        $produit->update($data);

        return $produit;
    }

    public function delete(string $id): void
    {
        $this->clearCache();

        $produit = Produit::findOrFail($id);
        $produit->delete();
    }
}
