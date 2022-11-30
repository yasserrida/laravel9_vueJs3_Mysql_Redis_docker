<?php

namespace Database\Seeders;

use App\Enumerations\Produit;
use App\Helpers\stringHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        DB::table('produits')->delete();

        $produits = [];

        foreach (Produit::PRODUITS as $item) {
            $produits[] = [
                'name' => StringHelper::toCamelCase($item['produit']),
                'slug' => StringHelper::toSlug($item['produit']),
                'gamme_id' => $item['gamme_id'],
                'fournisseur_id' => $item['fournisseur_id'],

                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        DB::table('produits')->insert($produits);
    }
}
