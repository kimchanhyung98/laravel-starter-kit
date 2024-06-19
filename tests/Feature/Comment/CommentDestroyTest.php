<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentDestroyTest extends TestCase
{
    use RefreshDatabase;

    private function sendCommentDestroy(int $commentId): TestResponse
    {
        return $this->deleteJson('api/comments/'.$commentId);
        // return $this->postJson('api/comments/'.$commentId.'/delete');
    }

    public function test_comment_destroy_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentDestroy($comment->id)
            ->assertOk()
            ->assertJsonStructure(['data' => ['message']]);
    }

    public function test_comment_destroy_fail_with_unauthorized(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentDestroy($comment->id)
            ->assertUnauthorized();
    }

    public function test_comment_destroy_fail_with_forbidden(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentDestroy($comment->id)
            ->assertForbidden();
    }
}
