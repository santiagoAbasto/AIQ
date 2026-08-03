<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loginclientes', function (Blueprint $table) {
            $table->timestamp('inactive_since_at')->nullable()->after('approved_at');
            $table->index(['is_enabled', 'inactive_since_at'], 'loginclientes_inactivity_index');
        });

        // Existing inactive accounts receive a fresh 30-day grace period.
        DB::table('loginclientes')
            ->where('is_enabled', false)
            ->whereNull('inactive_since_at')
            ->update(['inactive_since_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('loginclientes', function (Blueprint $table) {
            $table->dropIndex('loginclientes_inactivity_index');
            $table->dropColumn('inactive_since_at');
        });
    }
};
