<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnswerVote>
 */
class AnswerVoteFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vote_values = [1, -1];
        return [
            'user_id' => 1,
            'answer_id' => 1,
            'value' => $vote_values[rand(0, 1)],
        ];
    }
}
