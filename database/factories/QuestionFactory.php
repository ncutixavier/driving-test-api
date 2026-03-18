<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'question'    => $this->faker->sentence() . '?',
            'image'       => null,
            'difficulty'  => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'explanation' => $this->faker->sentence(),
            'is_active'   => true,
        ];
    }
}
