<?php

namespace Database\Factories;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class LokasiFactory extends Factory
{
    protected $model = Lokasi::class;

    public function definition(): array
    {
        return [
            'lokasi' => $this->faker->unique()->streetName() . ' No. ' . $this->faker->buildingNumber(),
        ];
    }
}
