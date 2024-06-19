<?php

namespace Tests\Feature\Like;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LikeStoreTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'like_id' => 1,
        'like_type' => 'post',
    ];

    private function sendLikeStore(): TestResponse
    {
        return $this->postJson('api/likes', $this->data);
    }

    public function test_like_store_success_with_post(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data['like_id'] = Post::factory()->create()->id;

        $this->sendLikeStore()
            ->assertCreated();
    }

    public function test_like_store_success_with_comment(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $this->data['like_id'] = $comment->id;
        $this->data['like_type'] = 'comment';

        $this->sendLikeStore()
            ->assertCreated();
    }

    public function test_like_store_fail_with_unauthorized(): void
    {
        $this->sendLikeStore()
            ->assertUnauthorized();
    }

    public function test_like_store_fail_with_not_found(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data['like_id'] = 999;

        $this->sendLikeStore()
            ->assertNotFound();
    }

    public function test_like_store_fail_with_conflict(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data['like_id'] = Post::factory()->create()->id;

        $this->sendLikeStore()
            ->assertCreated();

        $this->sendLikeStore()
            ->assertConflict();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_like_store_fail($field, $value, $error): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendLikeStore()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'like_id.required' => ['like_id', null, 'The like id field is required.'],
            'like_id.integer' => ['like_id', 'string', 'The like id field must be an integer.'],

            'like_type.required' => ['like_type', null, 'The like type field is required.'],
            'like_type.in' => ['like_type', 'invalid', 'The selected like type is invalid.'],
        ];
    }
}
