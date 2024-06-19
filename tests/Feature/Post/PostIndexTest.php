<?php

namespace Tests\Feature\Post;

use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'q' => 'ea',
        'type' => 'free',
        'per_page' => 10,
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PostSeeder::class);
    }

    private function sendPostIndex(): TestResponse
    {
        return $this->json('GET', 'api/posts', $this->data);
    }

    public function test_post_index_success(): void
    {
        $this->data = [];

        $this->sendPostIndex()
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'user_name', 'title', 'like_count', 'created_at']],
                'links' => ['first', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'path', 'per_page', 'to'],
            ]);
    }

    public function test_post_index_success_with_data(): void
    {
        $this->sendPostIndex()
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'user_name', 'title', 'like_count', 'created_at']],
                'links' => ['first', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'path', 'per_page', 'to'],
            ]);
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_post_index_fail($field, $value, $error): void
    {
        $this->data[$field] = $value;

        $this->sendPostIndex()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'q.min' => ['q', 'a', 'The q field must be at least 2 characters.'],
            'q.max' => ['q', str_repeat('a', 101), 'The q field must not be greater than 100 characters.'],

            'type.in' => ['type', 'invalid_type', 'The selected type is invalid.'],

            'per_page.integer' => ['per_page', 'abc', ['The per page field must be an integer.', 'The per page field must be between 5 and 20.']],
            'per_page.between_low' => ['per_page', 4, 'The per page field must be between 5 and 20.'],
            'per_page.between_high' => ['per_page', 21, 'The per page field must be between 5 and 20.'],
        ];
    }
}
