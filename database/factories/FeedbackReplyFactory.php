<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\FeedbackReply;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackReplyFactory extends Factory
{
    protected $model = FeedbackReply::class;

    public function definition(): array
    {
        return [
            'feedback_replies' => $this->faker->sentence, // ✅ isi konten reply
            'feedback_id' => Feedback::factory(),         // atau instance feedback yang sudah ada
        ];
    }
}
