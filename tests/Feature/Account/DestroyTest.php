<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_destroy(): void
    {
        $user = User::create([
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
            'password' => 'password',
        ]);
        $token = $user->createToken('testing')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/accounts/delete', [
                'deleted_reason' => 'delete account test',
            ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['message']]);

        $this->assertSoftDeleted('users', [
            'name' => 'testing',
            'nickname' => 'tester',
            'email' => 'testing@example.com',
        ]);
    }
}
