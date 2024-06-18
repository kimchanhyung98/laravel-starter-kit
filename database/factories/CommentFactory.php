<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'post_id' => Post::inRandomOrder()->first()->id,
            'parent_id' => null,
            'contents' => $this->faker->paragraphs(3, true),
            'likes_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function reply(): CommentFactory
    {
        return $this->state(function () {
            $parent = Comment::inRandomOrder()->first();

            return [
                'post_id' => $parent->post_id,
                'parent_id' => $parent->id,
            ];
        });
    }
}
