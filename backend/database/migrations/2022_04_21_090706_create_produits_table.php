<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();

            $table->string('name', 40);
            $table->string('slug', 40)->unique();

            $table->foreignId('gamme_id')->nullable()->constrained('gammes');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
}
