<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_update(): void
    {
        $user = User::create([
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);
        $token = $user->createToken('testing')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/accounts', [
                'name' => 'update-testing',
                'nickname' => 'update-tester',
                'email' => 'update-testing@example.com',
                // 'password' => 'password',
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['message']]);

        $this->assertDatabaseHas('users', [
            'name' => 'update-testing',
            'nickname' => 'update-tester',
            'email' => 'update-testing@example.com',
        ]);
    }
}
