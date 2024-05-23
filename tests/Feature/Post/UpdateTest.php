<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/posts/' . $post->id, [
            'title' => 'Updated Title',
            'contents' => 'Updated Contents',
            'type' => 'free',
            'is_open' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'message']]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'contents' => 'Updated Contents',
        ]);
    }
}
