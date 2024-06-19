<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'name' => '홍길동',
        'nickname' => 'tester',
        'email' => 'testing@example.com',
        'password' => 'Password1!',
    ];

    private function sendSignUp(): TestResponse
    {
        return $this->postJson('api/users/signup', $this->data);
    }

    public function test_signup_success(): void
    {
        $this->sendSignUp()
            ->assertCreated()
            ->assertJsonStructure(['data' => ['access_token']]);

        /* // todo : check token
        $token = $response->json('data.access_token');
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/users')
            ->assertStatus(200);
        */
    }

    public function test_signup_email_duplicate(): void
    {
        User::factory()->create([
            'nickname' => 'new_tester',
            'email' => 'testing@example.com',
        ]);

        $this->sendSignUp()
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
    }

    public function test_signup_nickname_duplicate(): void
    {
        User::factory()->create([
            'nickname' => 'tester',
            'email' => 'new_tester@example.com',
        ]);

        $this->sendSignUp()
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nickname' => 'The nickname has already been taken.']);
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_signup_fail($field, $value, $error): void
    {
        $this->data[$field] = $value;

        $this->sendSignUp()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'name.null' => ['name', null, 'The name field is required.'],
            'name.empty' => ['name', '', 'The name field is required.'],
            'name.min' => ['name', '김', 'The name field must be at least 2 characters.'],
            'name.max' => ['name', str_repeat('a', 51), 'The name field must not be greater than 50 characters.'],

            'nickname.null' => ['nickname', null, 'The nickname field is required.'],
            'nickname.empty' => ['nickname', '', 'The nickname field is required.'],
            'nickname.max' => ['nickname', str_repeat('a', 51), 'The nickname field must not be greater than 50 characters.'],
            // 'nickname.duplicate' => ['nickname', 'tester', 'The nickname has already been taken.'],

            'email.null' => ['email', null, 'The email field is required.'],
            'email.empty' => ['email', '', 'The email field is required.'],
            'email.max' => ['email', str_repeat('a', 91).'@example.com', 'The email field must not be greater than 100 characters.'],
            'email.invalid' => ['email', 'invalid-email', 'The email field must be a valid email address.'],
            // 'email.duplicate' => ['email', 'testing@example.com', 'The email has already been taken.'],

            'password.null' => ['password', null, 'The password field is required.'],
            'password.empty' => ['password', '', 'The password field is required.'],
            'password.min' => ['password', 'Short!', 'The password field must be at least 8 characters.'],
            'password.max' => ['password', str_repeat('a', 101).'A!', 'The password field must not be greater than 100 characters.'],
            'password.mixed' => ['password', 'testpassword!', 'The password field must contain at least one uppercase and one lowercase letter.'],
            'password.symbol' => ['password', 'TestPassword', 'The password field must contain at least one symbol.'],
        ];
    }
}
