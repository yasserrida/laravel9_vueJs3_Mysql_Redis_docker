<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('priority')->default('LOW')->nullable(); // LOW - MEDIUM - HIGH
            $table->string('statut')->default('OPEN')->nullable(); // OPEN - PENDING - CLOSED
            $table->string('label')->nullable(); // BUG - QUESTION - ENHANCEMENT
            $table->string('categorie')->nullable(); // UNCATEGORIZED - TECHNIQUE
            $table->boolean('is_resolved')->default(false);

            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
