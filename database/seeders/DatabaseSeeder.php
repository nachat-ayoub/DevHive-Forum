<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Answer;
use App\Models\Question;
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

        // Question::factory()->create([
        //     'title' => 'Test Question!',
        //     'body' => 'This is my test question please help me!',
        //     'user_id' => 1,
        // ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
