<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostStoreTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'title' => 'Post title, store testing',
        'contents' => 'post contents, store testing',
    ];

    private function sendPostStore(): TestResponse
    {
        return $this->postJson('api/posts', $this->data);
    }

    public function test_post_store_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendPostStore()
            ->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'message']]);
    }

    public function test_post_store_fail_with_unauthorized(): void
    {
        $this->sendPostStore()
            ->assertUnauthorized();
    }

    public function test_post_store_fail_with_too_many_requests(): void
    {
        $this->markTestSkipped('post check');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Post::factory(6)->create(['user_id' => $user->id]);

        $this->sendPostStore()
            ->assertTooManyRequests()
            ->assertJson(['message' => '작성_실패']);
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_post_store_fail($field, $value, $error): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendPostStore()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'title.null' => ['title', null, 'The title field is required.'],
            'title.empty' => ['title', '', 'The title field is required.'],
            'title.max' => ['title', str_repeat('a', 101), 'The title field must not be greater than 100 characters.'],

            'contents.null' => ['contents', null, 'The contents field is required.'],
            'contents.empty' => ['contents', '', 'The contents field is required.'],
            'contents.min' => ['contents', 'test', 'The contents field must be at least 5 characters.'],
        ];
    }
}
