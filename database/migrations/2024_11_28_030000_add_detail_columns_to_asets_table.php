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
        Schema::table('asets', function (Blueprint $table) {
            if (!Schema::hasColumn('asets', 'no_sub_aset')) {
                $table->string('no_sub_aset')->nullable()->after('nm_aset');
            }
            if (!Schema::hasColumn('asets', 'satuan')) {
                $table->string('satuan', 50)->nullable()->default('unit')->after('no_sub_aset');
            }
            if (!Schema::hasColumn('asets', 'tgl_aku')) {
                $table->date('tgl_aku')->nullable()->after('tgl_penambahan');
            }
            if (!Schema::hasColumn('asets', 'kota')) {
                $table->string('kota')->nullable()->after('lokasi_id');
            }
            if (!Schema::hasColumn('asets', 'ket_lok')) {
                $table->text('ket_lok')->nullable()->after('kota');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->dropColumn(['no_sub_aset', 'satuan', 'tgl_aku', 'kota', 'ket_lok']);
        });
    }
};
