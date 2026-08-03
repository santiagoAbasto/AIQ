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
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('titulo')->nullable();
            $table->longtext('descripcion')->nullable();
            $table->text('imagen')->nullable();
            $table->text('icono_mision')->nullable();
            $table->text('icono_vision')->nullable();
            $table->text('icono_valores')->nullable();
            $table->text('texto_mision')->nullable();
            $table->text('texto_vision')->nullable();
            $table->text('texto_valores')->nullable();           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
