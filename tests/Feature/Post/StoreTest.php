<?php

namespace Tests\Feature\Post;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Title',
            'contents' => 'Test Contents',
            'type' => 'free',
            'is_open' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'message']]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Test Title',
            'contents' => 'Test Contents',
        ]);
    }
}
