<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description', 'is_active', 'created_at', 'updated_at'],
                ],
            ]);
    }

    public function test_can_create_a_category(): void
    {
        $payload = [
            'name'        => 'Road Signs',
            'description' => 'Questions about road signs.',
        ];

        $response = $this->postJson('/api/v1/categories', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Road Signs', 'slug' => 'road-signs']);

        $this->assertDatabaseHas('categories', ['name' => 'Road Signs']);
    }

    public function test_create_category_requires_name(): void
    {
        $response = $this->postJson('/api/v1/categories', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $category->id]);
    }

    public function test_can_update_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/v1/categories/{$category->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
