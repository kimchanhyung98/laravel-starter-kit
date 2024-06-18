<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Post::count() === 0) {
            $this->call(PostSeeder::class);
        }

        // Comment::factory(10)->create();
        Comment::factory(100)->create();
        Comment::factory(300)->reply()->create();
    }
}
