<?php

namespace Tests\Feature\Post;

use Database\Seeders\PostSearchSeeder;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PostIndexSearchTest extends TestCase
{
    use RefreshDatabase;

    private function sendPostIndex(array $data): TestResponse
    {
        return $this->json('GET', 'api/posts', $data);
    }

    private function runSeeder(string $name, array $values): void
    {
        $seeder = new PostSearchSeeder($name, $values);
        $seeder->run();
    }

    public function test_post_index_search_with_q(): void
    {
        $titles = ['searchtest1', 'testsearch2', 'test3'];
        $this->runSeeder('title', $titles);

        $this->sendPostIndex(['q' => 'search'])
            ->assertOk()
            ->assertSee($titles[0])
            ->assertSee($titles[1])
            ->assertDontSee($titles[2]);
    }

    public function test_post_index_with_search_per_page(): void
    {
        $this->seed(PostSeeder::class);

        $this->sendPostIndex(['per_page' => 7])
            ->assertOk()
            ->assertJsonCount(7, 'data')
            ->assertJsonFragment(['per_page' => 7]);
    }
}
