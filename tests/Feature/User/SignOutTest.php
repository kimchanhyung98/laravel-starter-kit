<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SignOutTest extends TestCase
{
    use RefreshDatabase;

    private function sendSignOut(): TestResponse
    {
        return $this->postJson('api/users/signout');
    }

    public function test_signout_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendSignOut()
            ->assertNoContent();
    }

    public function test_signout_fail_with_unauthorized(): void
    {
        $this->sendSignOut()
            ->assertUnauthorized();
    }
}
