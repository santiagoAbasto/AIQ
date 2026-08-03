<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cliente_ai_chats', function (Blueprint $table) {
            $table->timestamp('hidden_from_client_at')->nullable()->after('last_message_at');
            $table->index(['logincliente_id', 'hidden_from_client_at', 'last_message_at'], 'cliente_ai_chats_client_visibility_index');
        });
    }

    public function down(): void
    {
        Schema::table('cliente_ai_chats', function (Blueprint $table) {
            $table->dropIndex('cliente_ai_chats_client_visibility_index');
            $table->dropColumn('hidden_from_client_at');
        });
    }
};
