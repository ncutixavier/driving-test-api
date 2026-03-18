<?php

namespace Tests\Feature\Api\V1;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_answers_for_question(): void
    {
        $question = Question::factory()->create();
        Answer::factory()->count(3)->create(['question_id' => $question->id]);

        $response = $this->getJson("/api/v1/questions/{$question->id}/answers");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'question_id', 'answer', 'is_correct'],
                ],
            ]);
    }

    public function test_can_create_an_answer(): void
    {
        $question = Question::factory()->create();

        $payload = [
            'question_id' => $question->id,
            'answer'      => 'Stop at the red light.',
            'is_correct'  => true,
        ];

        $response = $this->postJson('/api/v1/answers', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['answer' => 'Stop at the red light.', 'is_correct' => true]);

        $this->assertDatabaseHas('answers', ['answer' => 'Stop at the red light.']);
    }

    public function test_create_answer_requires_question_id(): void
    {
        $response = $this->postJson('/api/v1/answers', [
            'answer'     => 'Some answer',
            'is_correct' => false,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['question_id']);
    }

    public function test_can_update_an_answer(): void
    {
        $answer = Answer::factory()->create();

        $response = $this->putJson("/api/v1/answers/{$answer->id}", [
            'answer'     => 'Updated answer text.',
            'is_correct' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['answer' => 'Updated answer text.', 'is_correct' => true]);
    }

    public function test_can_delete_an_answer(): void
    {
        $answer = Answer::factory()->create();

        $response = $this->deleteJson("/api/v1/answers/{$answer->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('answers', ['id' => $answer->id]);
    }
}
