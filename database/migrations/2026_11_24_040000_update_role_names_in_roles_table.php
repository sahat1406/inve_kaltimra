<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tetapkan ID 1 sebagai manager, dan ID 2 sebagai admin
        DB::table('roles')->where('id', 1)->update(['role' => 'manager']);
        DB::table('roles')->where('id', 2)->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->where('id', 1)->update(['role' => 'admin']);
        DB::table('roles')->where('id', 2)->update(['role' => 'user']);
    }
};
