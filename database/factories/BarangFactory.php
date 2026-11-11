<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BarangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Barang::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Contoh nilai untuk kolom varchar yang mungkin memiliki nilai terbatas
        $possibleKondisi = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Baru'];
        $possibleSumberDana = ['APBN', 'APBD', 'Swadaya', 'Hibah', 'Lainnya'];

        return [
            'kd_barang' => 'BRG' . $this->faker->unique()->bothify('???###'), // varchar(255)
            'gambar' => 'qrcodes/' . Str::random(15) . '.png', // varchar(255), path dummy untuk QR
            'nm_barang' => $this->faker->words(rand(2, 5), true), // varchar(255)
            'deskripsi' => $this->faker->paragraph(rand(1, 3)), // text
            'tgl_penambahan' => $this->faker->date(), // date
            'kategori_id' => Kategori::factory(), // bigint unsigned
            'lokasi_id' => Lokasi::factory(), // bigint unsigned
        ];
    }
}