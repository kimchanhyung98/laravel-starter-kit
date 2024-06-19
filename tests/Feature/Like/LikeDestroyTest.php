<?php

namespace Tests\Feature\Like;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LikeDestroyTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'like_id' => 1,
        'like_type' => 'post',
    ];

    private function sendLikeDestroy(): TestResponse
    {
        return $this->deleteJson('api/likes', $this->data);
        // return $this->postJson('api/likes/delete', $this->data);
    }

    public function test_like_destroy_success_with_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => Post::factory()->create()->id,
            'likeable_type' => Post::class,
        ]);

        $this->data['like_id'] = $like->likeable_id;

        $this->sendLikeDestroy()
            ->assertNoContent();
    }

    public function test_like_destroy_success_with_comment(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => Post::factory()->create()->id,
        ]);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class,
        ]);

        $this->data['like_id'] = $like->likeable_id;
        $this->data['like_type'] = 'comment';

        $this->sendLikeDestroy()
            ->assertNoContent();
    }

    public function test_like_destroy_fail_with_not_authenticated(): void
    {
        $this->sendLikeDestroy()
            ->assertUnauthorized();
    }

    public function test_like_destroy_fail_with_not_found(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendLikeDestroy()
            ->assertNotFound();
    }

    public function test_like_destroy_fail_with_conflict(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data['like_id'] = Post::factory()->create()->id;

        $this->sendLikeDestroy()
            ->assertConflict();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_like_destroy_fail($field, $value, $error): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendLikeDestroy()
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
