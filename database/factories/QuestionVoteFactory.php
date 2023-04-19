<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionVote>
 */
class QuestionVoteFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vote_values = [1, -1];
        return [
            'user_id' => fake()->numberBetween(2, 3),
            'question_id' => fake()->numberBetween(1, 2),
            'value' => $vote_values[rand(0, 1)],
        ];
    }
}
