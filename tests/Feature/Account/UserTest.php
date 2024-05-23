<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_info(): void
    {
        $user = User::create([
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);
        $token = $user->createToken('testing')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/accounts');
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['name', 'nickname', 'email', 'phone', 'provider', 'created_at']]);
    }
}
