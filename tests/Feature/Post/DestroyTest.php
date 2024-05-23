<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/posts/' . $post->id);
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['message']]);

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }
}
