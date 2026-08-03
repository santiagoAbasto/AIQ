<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_ai_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logincliente_id')->constrained('loginclientes')->cascadeOnDelete();
            $table->string('assistant_type');
            $table->string('title')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['logincliente_id', 'assistant_type', 'last_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_ai_chats');
    }
};
