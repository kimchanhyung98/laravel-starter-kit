<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'type' => fake()->randomElement([null, 'notice', 'faq']),
            'title' => fake()->sentence,
            'contents' => fake()->paragraph,
            'hit' => fake()->numberBetween(0, 1000),
            'is_open' => fake()->boolean,
        ];
    }
}