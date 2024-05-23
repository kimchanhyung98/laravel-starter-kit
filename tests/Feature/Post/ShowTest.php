<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show(): void
    {
        $post = Post::factory()->free()->create();

        $response = $this->getJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
                'id',
                'user' => [
                    'name',
                ],
                'type',
                'title',
                'contents',
                'hit',
                'is_edit',
                'created_at',
            ]]);
    }
}
