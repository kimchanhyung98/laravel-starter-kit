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

class CommentStoreTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'post_id' => 1,
        'contents' => 'comment contents, store testing',
    ];

    private function sendCommentStore(): TestResponse
    {
        return $this->postJson('api/comments', $this->data);
    }

    public function test_comment_store_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data['post_id'] = Post::factory()->create()->id;

        $this->sendCommentStore()
            ->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'message']]);
    }

    public function test_comment_store_fail_with_unauthorized(): void
    {
        $this->sendCommentStore()
            ->assertUnauthorized();
    }

    public function test_comment_store_fail_with_forbidden(): void
    {
        $this->markTestSkipped('comment check');

        Sanctum::actingAs(User::factory()->create());

        $parent = Comment::factory()->create([
            'post_id' => Post::factory()->create()->id,
        ]);
        $child = Comment::factory()->create([
            'post_id' => $parent->post_id,
            'parent_id' => $parent->id,
        ]);

        $this->data['parent_id'] = $child->id;

        $this->sendCommentStore()
            ->assertForbidden();
    }

    public function test_comment_store_fail_with_too_many_requests(): void
    {
        $this->markTestSkipped('comment check');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Comment::factory(6)->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->sendCommentStore()
            ->assertTooManyRequests()
            ->assertJson(['message' => '작성_실패']);
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_comment_store_fail($field, $value, $error): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create();
        $this->data['post_id'] = $field === 'post_id' ? $value : $post->id;
        $this->data[$field] = $value;

        $this->sendCommentStore()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'post_id.null' => ['post_id', null, 'The post id field is required.'],
            'post_id.empty' => ['post_id', '', 'The post id field is required.'],
            'post_id.integer' => ['post_id', 'a', 'The post id field must be an integer.'],
            'post_id.exists' => ['post_id', 999, 'The selected post id is invalid.'],

            'parent_id' => ['parent_id', 999, 'The selected parent id is invalid.'],
            'parent_id.integer' => ['parent_id', 'a', 'The parent id field must be an integer.'],
            'parent_id.exists' => ['parent_id', 999, 'The selected parent id is invalid.'],

            'contents.null' => ['contents', null, 'The contents field is required.'],
            'contents.empty' => ['contents', '', 'The contents field is required.'],
            'contents.min' => ['contents', 'a', 'The contents field must be at least 2 characters.'],
            'contents.max' => ['contents', str_repeat('a', 5001), 'The contents field must not be greater than 5000 characters.'],
        ];
    }
}
