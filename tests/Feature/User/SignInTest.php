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
        // 'name' => '홍길동',
        // 'nickname' => 'tester',
        'email' => 'testing@example.com',
        'password' => 'Password1!',
    ];

    private function sendSignIn(): TestResponse
    {
        return $this->postJson('api/users/signin', $this->data);
    }

    public function test_signin_success(): void
    {
        User::factory()->create(['email' => 'testing@example.com']);

        $this->sendSignIn()
            ->assertOk()
            ->assertJsonStructure(['data' => ['access_token']]);
    }

    public function test_signin_deleted_user(): void
    {
        User::factory()->deleted()->create(['email' => 'testing@example.com']);

        $this->sendSignIn()
            ->assertUnauthorized();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_signin_fail($field, $value, $error): void
    {
        $this->data[$field] = $value;

        User::factory()->create(['email' => 'testing@example.com']);

        $this->sendSignIn()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'email.null' => ['email', null, 'The email field is required.'],
            'email.empty' => ['email', '', 'The email field is required.'],
            'email.invalid' => ['email', 'invalid-email', 'The email field must be a valid email address.'],

            'password.null' => ['password', null, 'The password field is required.'],
            'password.empty' => ['password', '', 'The password field is required.'],
            'password.min' => ['password', 'Short!', 'The password field must be at least 8 characters.'],
            'password.max' => ['password', str_repeat('a', 99).'A1!', 'The password field must not be greater than 100 characters.'],
        ];
    }
}
