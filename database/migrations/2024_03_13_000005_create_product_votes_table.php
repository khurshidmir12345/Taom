<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();

            $table->unique(['bot_user_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_votes');
    }
}; 