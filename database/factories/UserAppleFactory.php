<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\User\UserApple;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAppleFactory extends Factory
{
    protected $model = UserApple::class;

    public function definition(): array
    {
        $user = User::factory()->apple()->create();

        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'sub' => $user->provider_id,
            'at_hash' => fake()->md5,
            'token' => $user->provider_token,
        ];
    }
}
