<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logincliente_id')->constrained('loginclientes')->cascadeOnDelete();
            $table->string('assistant_type');
            $table->text('input');
            $table->longText('output')->nullable();
            $table->string('status')->default('completed');
            $table->string('webhook_url')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_ai_requests');
    }
};
