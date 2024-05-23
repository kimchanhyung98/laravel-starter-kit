<?php

namespace Tests\Feature\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_up(): void
    {
        $userData = [
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
            'password' => 'password',
        ];
        $response = $this->postJson('/api/accounts/signup', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['access_token']]);
        $this->check_token($response->json('data.access_token'));
    }

    protected function check_token($token): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/accounts');
        $response->assertStatus(200);
    }
}
