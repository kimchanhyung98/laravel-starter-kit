<?php

namespace Database\Factories\User;

use App\Models\User;
use App\Models\User\UserKakao;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserKakaoFactory extends Factory
{
    protected $model = UserKakao::class;

    public function definition(): array
    {
        $user = User::factory()->kakao()->create();

        return [
            'user_id'   => $user->id,
            'email'     => $user->email,
            'name'      => $user->name,
            'nickname'  => $user->nickname,
            'avatar'    => fake()->imageUrl(),
            'gender'    => fake()->randomElement(['male', 'female']),
            'birthday'  => fake()->date('md'),
            'calendar'  => fake()->randomElement(['SOLAR', 'LUNAR', 'LEAP']),
            'age_range' => fake()->randomElement(['15~19', '20~29', '30~39', '40~49', '50~59', '60~']),
            'sub'       => $user->provider_id,
        ];
    }
}
