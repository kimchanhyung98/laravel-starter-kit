<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/posts/'.$post->id.'/edit');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'type', 'title', 'contents', 'is_open']]);
    }
}
