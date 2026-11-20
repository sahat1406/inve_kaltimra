<?php

namespace Database\Factories;

use App\Models\Pelaporan;
use App\Models\Aset;
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
            'aset_id'  => Aset::factory(),
        ];
    }
}
