<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CommentUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'contents' => 'comment contents, update testing',
    ];

    private function sendCommentUpdate(int $commentId): TestResponse
    {
        return $this->putJson('api/comments/'.$commentId, $this->data);
        // return $this->postJson('api/comments/'.$commentId.'/update', $this->data);
    }

    public function test_comment_update_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentUpdate($comment->id)
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'message']]);
    }

    public function test_comment_update_fail_with_unauthorized(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentUpdate($comment->id)
            ->assertUnauthorized();
    }

    public function test_comment_update_fail_with_forbidden(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentUpdate($comment->id)
            ->assertForbidden();
    }

    public function test_comment_update_fail_with_not_found(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentUpdate(999)
            ->assertNotFound();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_comment_update_fail($field, $value, $error): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->data[$field] = $value;

        $this->sendCommentUpdate($comment->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'contents.null' => ['contents', null, 'The contents field is required.'],
            'contents.empty' => ['contents', '', 'The contents field is required.'],
            'contents.min' => ['contents', 'a', 'The contents field must be at least 2 characters.'],
            'contents.max' => ['contents', str_repeat('a', 5001), 'The contents field must not be greater than 5000 characters.'],
        ];
    }
}
