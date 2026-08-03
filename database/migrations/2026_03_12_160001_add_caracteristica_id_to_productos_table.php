<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('caracteristica_id')->nullable()->after('slug');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('set null');
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['caracteristica_id']);
            $table->dropColumn('caracteristica_id');
        });
        Schema::enableForeignKeyConstraints();
    }
};
