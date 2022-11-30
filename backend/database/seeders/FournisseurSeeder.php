<?php

namespace Database\Seeders;

use App\Enumerations\Fournisseur;
use App\Helpers\StringHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FournisseurSeeder extends Seeder
{
    public function run()
    {
        DB::table('fournisseurs')->delete();

        $fournisseurs = [];

        foreach (Fournisseur::FOURNISSEURS as $item) {
            $fournisseurs[] = [
                'name' => StringHelper::toCamelCase($item),
                'slug' => StringHelper::toSlug($item),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        DB::table('fournisseurs')->insert($fournisseurs);
    }
}
