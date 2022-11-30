<?php

namespace Database\Seeders;

use App\Enumerations\Gamme;
use App\Helpers\stringHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GammeSeeder extends Seeder
{
    public function run()
    {
        DB::table('gammes')->delete();

        $gammes = [];

        foreach (Gamme::GAMMES as $item) {
            $gammes[] = [
                'name' => StringHelper::toCamelCase($item),
                'slug' => StringHelper::toSlug($item),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        DB::table('gammes')->insert($gammes);
    }
}
