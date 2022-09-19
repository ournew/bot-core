<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->text('message');
            $table->timestamps();
            
            $table->foreign('chat_id')->references('chat_id')->on('chats');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};