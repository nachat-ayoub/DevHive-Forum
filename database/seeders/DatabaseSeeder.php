<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Answer;
use App\Models\AnswerVote;
use App\Models\Question;
use App\Models\QuestionVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        User::factory(3)->create();
        Question::factory(5)->create();
        Answer::factory(4)->create();

        // QuestionVote::factory(10)->create();
        // AnswerVote::factory(5)->create();

        QuestionVote::factory()->create([
            'user_id' => 2,
            'question_id' => 1,
            'value' => -1,
        ]);
        QuestionVote::factory()->create([
            'user_id' => 3,
            'question_id' => 1,
            'value' => -1,
        ]);

        AnswerVote::factory()->create([
            'user_id' => 1,
            'answer_id' => 1,
            'value' => 1,
        ]);
        AnswerVote::factory()->create([
            'user_id' => 2,
            'answer_id' => 1,
            'value' => 1,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
