<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('file_name')->nullable()->unique();
            $table->string('collection_name')->nullable();
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();

            $table->foreignId('contrat_id')->nullable()->constrained('contrats');
            $table->foreignId('ticket_id')->nullable()->constrained('tickets');
            $table->foreignId('user_id')->constrained('users');

            $table->softDeletes();
            $table->Timestamps();
        });
    }
}
