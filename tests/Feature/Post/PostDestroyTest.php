<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostDestroyTest extends TestCase
{
    use RefreshDatabase;

    private function sendPostDestroy(int $postId): TestResponse
    {
        return $this->deleteJson('api/posts/'.$postId);
        // return $this->postJson('api/posts/'.$postId.'/delete');
    }

    public function test_post_destroy_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->sendPostDestroy($post->id)
            ->assertOk()
            ->assertJsonStructure(['data' => ['message']]);
    }

    public function test_post_destroy_fail_with_unauthorized(): void
    {
        $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->sendPostDestroy($post->id)
            ->assertUnauthorized();
    }

    public function test_post_destroy_fail_with_forbidden(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->sendPostDestroy($post->id)
            ->assertForbidden();
    }

    public function test_post_destroy_fail_with_not_found(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendPostDestroy(999)
            ->assertNotFound();
    }
}
