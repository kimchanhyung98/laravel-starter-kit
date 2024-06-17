<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserShowTest extends TestCase
{
    use RefreshDatabase;

    private function sendUserShow(): TestResponse
    {
        return $this->getJson('api/users');
    }

    public function test_user_show_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendUserShow()
            ->assertOk()
            ->assertJsonStructure(['data' => ['login_id', 'name', 'email', 'created_at']]);
    }

    public function test_user_show_fail_with_unauthorized(): void
    {
        $this->sendUserShow()
            ->assertUnauthorized();
    }
}
