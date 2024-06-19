<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSearchSeeder extends Seeder
{
    public function __construct(public string $name, public array $values)
    {
        //
    }

    public function run(): void
    {
        $user = User::factory()->create();
        foreach ($this->values as $value) {
            Post::factory()->create(['user_id' => $user->id, $this->name => $value]);
        }
    }
}
