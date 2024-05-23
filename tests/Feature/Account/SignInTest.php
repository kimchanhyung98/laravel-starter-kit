<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignInTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_in(): void
    {
        User::create([
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/accounts/signin', [
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);

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
