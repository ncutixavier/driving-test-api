<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::factory(5)->create();

        $categories->each(function (Category $category) {
            $questions = Question::factory(10)->create(['category_id' => $category->id]);

            $questions->each(function (Question $question) {
                Answer::factory()->create(['question_id' => $question->id, 'is_correct' => true]);
                Answer::factory(3)->create(['question_id' => $question->id, 'is_correct' => false]);
            });
        });
    }
}
