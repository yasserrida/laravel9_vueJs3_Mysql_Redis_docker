<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclamationsTable extends Migration
{
    public function up()
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();

            $table->string('canal')->nullable();
            $table->string('qualification')->nullable();
            $table->string('reclamant')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->date('date_mail')->nullable();
            $table->date('date_courier')->nullable();
            $table->boolean('solution')->nullable()->default(false);
            $table->date('date_cloture')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('contrat_id')->nullable()->constrained('contrats');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamations');
    }
}
