<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotUsersTable extends Migration
{
    public function up()
    {
        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('user_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('last_message_id')->nullable();
            $table->foreignId('cafe_id')->constrained()->onDelete('cascade');
            $table->string('step')->default('start');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_users');
    }
}
