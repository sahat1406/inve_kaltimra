
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kd_barang')->unique();
            $table->string('gambar');
            $table->string('nm_barang');
            $table->text('deskripsi');
            $table->date('tgl_penambahan');
            $table->foreignId('kategori_id')->constrained('kategoris');
            // $table->foreignId('merk_id')->constrained('merks');
            $table->foreignId('lokasi_id')->constrained('lokasis');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barangs');
    }
};
