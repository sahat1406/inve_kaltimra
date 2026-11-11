<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role; // Pastikan ini di-import
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Sesuai kolom 'name' varchar(255)
            'email' => $this->faker->unique()->safeEmail(), // Sesuai kolom 'email' varchar(255) unique
            'email_verified_at' => now(), // timestamp nullable, diisi agar user terverifikasi by default
            'password' => static::$password ??= Hash::make('password'), // varchar(255)
            'role_id' => Role::factory(), // bigint unsigned, foreign key ke roles.id
            'remember_token' => Str::random(10), // varchar(100) nullable
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State untuk membuat User dengan role tertentu.
     * (Helper tambahan)
     * @param string $roleName
     * @return static
     */
    public function withRole(string $roleName): static
    {
        return $this->state(function (array $attributes) use ($roleName) {
            // Mencari role berdasarkan nama, atau membuatnya jika tidak ada.
            // Ini mengasumsikan Anda ingin role tersebut ada.
            $role = Role::firstOrCreate(['role' => $roleName]);
            return [
                'role_id' => $role->id,
            ];
        });
    }
}