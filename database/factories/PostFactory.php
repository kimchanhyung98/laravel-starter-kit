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
            'type' => $this->faker->randomElement(['free', 'notice', 'faq']),
            'title' => $this->faker->sentence(4),
            'contents' => $this->faker->paragraphs(3, true),
            'hit' => $this->faker->numberBetween(0, 100),
            'likes_count' => $this->faker->numberBetween(0, 100),
            'is_published' => true, //$this->faker->boolean(),

        ];
    }

    public function randomCreated(): PostFactory
    {
        return $this->state([
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
        ]);
    }

    public function deleted(): PostFactory
    {
        return $this->state([
            'deleted_at' => now(),
        ]);
    }
}
