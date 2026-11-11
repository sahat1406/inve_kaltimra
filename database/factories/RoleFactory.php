<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Kolom 'nama_role' sesuai dengan SQL (varchar(255) NOT NULL)
            'role' => $this->faker->unique()->jobTitle(),
        ];
    }

    /**
     * State untuk membuat role 'Admin'.
     * (Ini adalah helper tambahan, bukan dari struktur SQL langsung, tapi berguna untuk testing)
     */
    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Admin',
        ]);
    }

    /**
     * State untuk membuat role 'Manager'.
     * (Helper tambahan)
     */
    public function user(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'User',
        ]);
    }

}