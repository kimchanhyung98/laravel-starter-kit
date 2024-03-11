<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\UserApple;
use App\Models\User\UserKakao;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        UserApple::factory(5)->create();
        UserKakao::factory(5)->create();
    }
}
