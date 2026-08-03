<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bobinas', function (Blueprint $table) {
            $table->id();
            $table->string('orden')->nullable();
            $table->string('titulo');
            $table->longtext('descripcion')->nullable();
            $table->longtext('descripciondos')->nullable();
            // imagen
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobinas');
    }
};
