<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelaporan;
use App\Models\Aset;
use Illuminate\Support\Str;

class PelaporanSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada minimal 1 aset
        $aset = Aset::first();
        if (!$aset) {
            $aset = Aset::factory()->create(); // Pastikan kamu punya AsetFactory
        }

        Pelaporan::create([
            'judul' => 'Laptop Rusak',
            'deskripsi' => 'Layar tidak menyala dan baterai cepat habis.',
            'status' => 'menunggu',
            'aset_id' => $aset->id,
        ]);

        Pelaporan::create([
            'judul' => 'Printer Bermasalah',
            'deskripsi' => 'Hasil cetakan buram dan tinta bocor.',
            'status' => 'sedang diperbaiki',
            'aset_id' => $aset->id,
        ]);

        Pelaporan::create([
            'judul' => 'Proyektor Mati Total',
            'deskripsi' => 'Tidak bisa dihidupkan meskipun kabel daya sudah diganti.',
            'status' => 'selesai',
            'aset_id' => $aset->id,
        ]);
    }
}
