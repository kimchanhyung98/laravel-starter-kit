<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\Comment;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $likeableType = $this->faker->randomElement([
            Post::class,
            Comment::class
        ]);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'likeable_id' => $likeableType::inRandomOrder()->first()->id,
            'likeable_type' => $likeableType,
        ];
    }
}
