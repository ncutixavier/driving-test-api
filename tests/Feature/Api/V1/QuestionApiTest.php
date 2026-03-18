<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_questions(): void
    {
        Question::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/questions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'category_id', 'question', 'difficulty', 'is_active'],
                ],
            ]);
    }

    public function test_can_create_a_question(): void
    {
        $category = Category::factory()->create();

        $payload = [
            'category_id' => $category->id,
            'question'    => 'What does a red light mean?',
            'difficulty'  => 'easy',
            'answers'     => [
                ['answer' => 'Stop', 'is_correct' => true],
                ['answer' => 'Go',   'is_correct' => false],
            ],
        ];

        $response = $this->postJson('/api/v1/questions', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['question' => 'What does a red light mean?']);

        $this->assertDatabaseHas('questions', ['question' => 'What does a red light mean?']);
        $this->assertDatabaseHas('answers', ['answer' => 'Stop', 'is_correct' => 1]);
    }

    public function test_create_question_requires_category_id(): void
    {
        $response = $this->postJson('/api/v1/questions', ['question' => 'Test?']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_can_show_a_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->getJson("/api/v1/questions/{$question->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $question->id]);
    }

    public function test_can_update_a_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->patchJson("/api/v1/questions/{$question->id}", [
            'question' => 'Updated question text?',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['question' => 'Updated question text?']);
    }

    public function test_can_delete_a_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->deleteJson("/api/v1/questions/{$question->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('questions', ['id' => $question->id]);
    }

    public function test_can_get_random_questions(): void
    {
        Question::factory()->count(10)->create();

        $response = $this->getJson('/api/v1/questions/random?count=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_filter_questions_by_category(): void
    {
        $category = Category::factory()->create();
        Question::factory()->count(2)->create(['category_id' => $category->id]);
        Question::factory()->count(3)->create();

        $response = $this->getJson("/api/v1/questions?category_id={$category->id}");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }
}
