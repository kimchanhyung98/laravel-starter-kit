<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'title' => 'Post title, update testing',
        'contents' => 'post contents, update testing',
    ];

    private function sendPostUpdate(int $postId): TestResponse
    {
        return $this->putJson('api/posts/'.$postId, $this->data);
        // return $this->postJson('api/posts/'.$postId.'/update', $this->data);
    }

    public function test_post_update_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->sendPostUpdate($post->id)
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'message']]);
    }

    public function test_post_update_fail_with_unauthorized(): void
    {
        $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->sendPostUpdate($post->id)
            ->assertUnauthorized();
    }

    public function test_post_update_fail_with_forbidden(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->sendPostUpdate($post->id)
            ->assertForbidden();
    }

    public function test_post_update_fail_with_not_found(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendPostUpdate(999)
            ->assertNotFound();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_post_update_fail($field, $value, $error): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->data[$field] = $value;
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->sendPostUpdate($post->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'title.null' => ['title', null, 'The title field is required.'],
            'title.empty' => ['title', '', 'The title field is required.'],
            'title.min' => ['title', 'a', 'The title field must be at least 2 characters.'],
            'title.max' => ['title', str_repeat('a', 101), 'The title field must not be greater than 100 characters.'],

            'contents.null' => ['contents', null, 'The contents field is required.'],
            'contents.empty' => ['contents', '', 'The contents field is required.'],
            'contents.min' => ['contents', 'a', 'The contents field must be at least 2 characters.'],
        ];
    }
}
