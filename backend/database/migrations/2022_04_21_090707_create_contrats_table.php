<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratsTable extends Migration
{
    public function up()
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();

            $table->string('numero_contrat')->nullable()->index();
            $table->date('date_signature')->nullable();
            $table->date('date_reception')->nullable();
            $table->date('date_effet')->nullable();
            $table->string('source')->nullable();
            $table->string('rib')->nullable();
            $table->string('iban', 50)->nullable();
            $table->string('iban_titulaire')->nullable();

            $table->string('statut')->nullable();
            $table->string('sous_statut')->nullable();

            $table->foreignId('produit_id')->nullable()->constrained('produits');
            $table->foreignId('user_id')->constrained('users');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contrats');
    }
}
