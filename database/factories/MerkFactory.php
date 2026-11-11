<?php

namespace Database\Factories;

use App\Models\Merk;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Kolom 'nama_merk' sesuai dengan SQL (varchar(255) NOT NULL)
            'nama_merk' => $this->faker->unique()->company(),
        ];
    }
}