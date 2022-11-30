<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGammesTable extends Migration
{
    public function up()
    {
        Schema::create('gammes', function (Blueprint $table) {
            $table->id();

            $table->string('name', 40);
            $table->string('slug', 40)->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gammes');
    }
}
