<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsUserTable extends Migration
{
    public function up()
    {
        Schema::create('notifications_user', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->boolean('status')->nullable()->default(true);
            $table->integer('temps_traitement')->nullable();
            $table->string('for')->nullable()->default('all');
            $table->boolean('archiver')->nullable()->default(false);

            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications_user');
    }
}
