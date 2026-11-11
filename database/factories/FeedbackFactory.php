<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pelaporan;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        $pelaporan = Pelaporan::factory()->create([
            'status' => 'selesai',
            'user_id' => $user->id, // ← kunci penting
        ]);

        return [
            'pelaporan_id'      => $pelaporan->id,
            'user_id'           => $user->id,
            'rating'            => $this->faker->numberBetween(1, 5),
            'komentar'          => $this->faker->sentence,
            'tanggal_feedback'  => now(),
        ];
    }
}
