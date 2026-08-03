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
        Schema::table('subcategorias', function (Blueprint $table) {
            // Eliminar el índice único global sobre slug
            $table->dropUnique('subcategorias_slug_unique');
            // Agregar índice único compuesto: el mismo slug puede existir en distintas categorías
            $table->unique(['slug', 'categoria_id'], 'subcategorias_slug_categoria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategorias', function (Blueprint $table) {
            $table->dropUnique('subcategorias_slug_categoria_unique');
            $table->unique('slug', 'subcategorias_slug_unique');
        });
    }
};
