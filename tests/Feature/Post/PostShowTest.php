<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    private function sendPostShow(int $postId): TestResponse
    {
        return $this->getJson('api/posts/' . $postId);
    }

    public function test_post_show_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create();

        $this->sendPostShow($post->id)
            ->assertOk()
            ->assertJsonStructure(['data' => [
                'id', 'user_name', 'type', 'title', 'contents', 'hit', 'like_count', 'comment_count', 'created_at', 'is_liked', 'is_editable',
            ]]);
    }

    public function test_post_show_success_for_owner_with_unpublished_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_published' => false
        ]);

        $this->sendPostShow($post->id)
            ->assertOk()
            ->assertJsonStructure(['data' => [
                'id', 'user_name', 'type', 'title', 'contents', 'hit', 'like_count', 'comment_count', 'created_at', 'is_liked', 'is_editable',
            ]]);
    }

    public function test_user_show_fail_with_forbidden(): void
    {
        // Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create([
            'user_id' => User::factory()->create()->id,
            'is_published' => false
        ]);

        $this->sendPostShow($post->id)
            ->assertForbidden();
    }
}
