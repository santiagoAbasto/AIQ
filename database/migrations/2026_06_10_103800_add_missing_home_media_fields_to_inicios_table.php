<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inicios', function (Blueprint $table) {
            foreach (['banner_dos', 'titulouno', 'titulodos', 'titulotres', 'imagenuna', 'imagendos', 'imagentres'] as $column) {
                if (! Schema::hasColumn('inicios', $column)) {
                    $table->text($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('inicios', function (Blueprint $table) {
            foreach (['banner_dos', 'titulouno', 'titulodos', 'titulotres', 'imagenuna', 'imagendos', 'imagentres'] as $column) {
                if (Schema::hasColumn('inicios', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
