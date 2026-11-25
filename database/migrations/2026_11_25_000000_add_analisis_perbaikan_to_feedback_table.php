<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('feedback', 'analisis_perbaikan')) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->text('analisis_perbaikan')->nullable()->after('pelaporan_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('feedback', 'analisis_perbaikan')) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->dropColumn('analisis_perbaikan');
            });
        }
    }
};
