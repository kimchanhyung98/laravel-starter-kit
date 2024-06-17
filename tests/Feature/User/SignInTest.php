<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SignInTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'login_id' => 'tester',
        'password' => 'TestPassword!',
    ];

    private function sendSignIn(): TestResponse
    {
        return $this->postJson('api/users/signin', $this->data);
    }

    public function test_signin_success(): void
    {
        User::factory()->create(['login_id' => 'tester']);

        $this->sendSignIn()
            ->assertCreated()
            ->assertJsonStructure(['data' => ['access_token']]);
    }

    public function test_signin_deleted_user(): void
    {
        User::factory()->deleted()->create(['login_id' => 'tester']);

        $this->sendSignIn()
            ->assertUnauthorized();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_signin_fail($field, $value, $error): void
    {
        $this->data[$field] = $value;

        User::factory()->create(['login_id' => 'tester']);

        $this->sendSignIn()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'login_id.null' => ['login_id', null, 'The login id field is required.'],
            'login_id.empty' => ['login_id', '', 'The login id field is required.'],
            'login_id.min' => ['login_id', 'test', 'The login id field must be at least 5 characters.'],
            'login_id.max' => ['login_id', str_repeat('a', 101), 'The login id field must not be greater than 100 characters.'],

            'password.null' => ['password', null, 'The password field is required.'],
            'password.empty' => ['password', '', 'The password field is required.'],
            'password.min' => ['password', 'Short!', 'The password field must be at least 12 characters.'],
            'password.max' => ['password', str_repeat('a', 20).'A!', 'The password field must not be greater than 20 characters.'],
            'password.mixed' => ['password', 'testpassword!', 'The password field must contain at least one uppercase and one lowercase letter.'],
            'password.symbol' => ['password', 'TestPassword', 'The password field must contain at least one symbol.'],
        ];
    }
}
