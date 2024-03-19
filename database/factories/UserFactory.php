<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'nickname' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'phone' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
        ];
    }

    public function apple(): UserFactory
    {
        return $this->state([
            'provider' => 'apple',
            'provider_id' => '000123.'.fake()->uuid(),
            'provider_token' => fake()->md5,
        ]);
    }

    public function kakao(): UserFactory
    {
        return $this->state([
            'provider' => 'kakao',
            'provider_id' => fake()->numberBetween(1000000000, 5000000000),
            'provider_token' => fake()->md5,
        ]);
    }
}
