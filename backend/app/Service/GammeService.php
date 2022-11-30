<?php

namespace App\Service;

use App\Helpers\StringHelper;
use App\Models\Gamme;
use Illuminate\Support\Facades\Cache;

class GammeService
{
    public function clearCache(): void
    {
        Cache::forget('Gammes');
    }

    public function getAll()
    {
        return Cache::remember('Gammes', 604800, fn () => Gamme::select('id', 'name', 'slug')->orderBy('id', 'ASC')->get());
    }

    public function get(string $id): Gamme
    {
        return Gamme::findOrFail($id);
    }

    public function store(array $data): Gamme
    {
        $this->clearCache();

        $gamme = Gamme::create($data);

        return $gamme;
    }

    public function update(array $data, string $id): Gamme
    {
        $this->clearCache();

        $gamme = Gamme::findOrFail($id);

        $data['slug'] = StringHelper::toSlug($data['name']);

        $gamme->update($data);

        return $gamme;
    }

    public function delete(string $id): void
    {
        $this->clearCache();

        $gamme = Gamme::findOrFail($id);
        $gamme->delete();
    }
}
