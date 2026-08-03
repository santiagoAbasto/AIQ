<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_importados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logincliente_id')->constrained('loginclientes')->cascadeOnDelete();
            $table->string('nombre')->nullable();
            $table->string('email')->nullable();
            $table->string('empresa')->nullable();
            $table->string('telefono')->nullable();
            $table->string('producto')->nullable();
            $table->text('consulta')->nullable();
            $table->json('raw_data')->nullable();
            $table->string('source_file')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_importados');
    }
};
