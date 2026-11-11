<?php

namespace Database\Factories;

use App\Models\Pelaporan;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelaporanFactory extends Factory
{
    protected $model = Pelaporan::class;

    public function definition(): array
    {
        return [
            'judul'      => $this->faker->sentence,
            'deskripsi'  => $this->faker->paragraph,
            'status'     => 'selesai', // hanya kolom yang tersedia
            'barang_id'  => Barang::factory(),
        ];
    }
}
