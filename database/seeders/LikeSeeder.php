<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Like::factory(50)->create();

        if (Post::count() === 0 || Comment::count() === 0){
            $this->call([
                PostSeeder::class,
                CommentSeeder::class,
            ]);
        }

        Like::factory(50)->post()->create();
        Like::factory(50)->comment()->create();
    }
}
