<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_ai_chat_id')->constrained('cliente_ai_chats')->cascadeOnDelete();
            $table->foreignId('cliente_ai_request_id')->nullable()->constrained('cliente_ai_requests')->nullOnDelete();
            $table->string('role');
            $table->longText('content');
            $table->string('status')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['cliente_ai_chat_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_ai_chat_messages');
    }
};
