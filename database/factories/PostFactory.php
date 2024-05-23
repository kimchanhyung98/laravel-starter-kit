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
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['free', 'notice', 'faq']),
            'title' => fake()->sentence,
            'contents' => fake()->text,
            'hit' => fake()->numberBetween(0, 1000),
            'is_open' => fake()->boolean,
        ];
    }

    public function free(): PostFactory
    {
        return $this->state([
            'type' => 'free',
            'is_open' => true,
        ]);
    }
}
