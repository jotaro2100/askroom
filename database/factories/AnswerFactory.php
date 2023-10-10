<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Query;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'query_id' => Query::factory(),
            'content' => fake()->realText(random_int(100, 300)),
        ];
    }
}
