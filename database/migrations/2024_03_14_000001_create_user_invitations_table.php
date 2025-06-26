<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inviter_id');
            $table->bigInteger('invited_id');
            $table->bigInteger('chat_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_invitations');
    }
}; 