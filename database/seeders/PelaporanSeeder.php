<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelaporan;
use App\Models\Barang;
use Illuminate\Support\Str;

class PelaporanSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada minimal 1 barang
        $barang = Barang::first();
        if (!$barang) {
            $barang = Barang::factory()->create(); // Pastikan kamu punya BarangFactory
        }

        Pelaporan::create([
            'judul' => 'Laptop Rusak',
            'deskripsi' => 'Layar tidak menyala dan baterai cepat habis.',
            'status' => 'menunggu',
            'barang_id' => $barang->id,
        ]);

        Pelaporan::create([
            'judul' => 'Printer Bermasalah',
            'deskripsi' => 'Hasil cetakan buram dan tinta bocor.',
            'status' => 'sedang diperbaiki',
            'barang_id' => $barang->id,
        ]);

        Pelaporan::create([
            'judul' => 'Proyektor Mati Total',
            'deskripsi' => 'Tidak bisa dihidupkan meskipun kabel daya sudah diganti.',
            'status' => 'selesai',
            'barang_id' => $barang->id,
        ]);
    }
}
