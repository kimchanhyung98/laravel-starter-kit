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
        'login_id' => 'tester',
        'name' => 'testing',
        'email' => 'testing@example.com',
        'password' => 'TestPassword!',
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
    }

    public function test_signup_email_duplicate(): void
    {
        User::factory()->create([
            'login_id' => 'new_tester',
            'email' => 'testing@example.com',
        ]);

        $this->sendSignUp()
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_signup_fail($field, $value, $error): void
    {
        $this->data[$field] = $value;

        User::factory()->create(['login_id' => 'tester']);

        $this->sendSignUp()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            'login_id.null' => ['login_id', null, 'The login id field is required.'],
            'login_id.empty' => ['login_id', '', 'The login id field is required.'],
            'login_id.min' => ['login_id', 'aa', 'The login id field must be at least 3 characters.'],
            'login_id.max' => ['login_id', str_repeat('a', 21), 'The login id field must not be greater than 20 characters.'],
            'login_id.regex' => ['login_id', 'tester!', 'The login id field format is invalid.'],
            'login_id.unique' => ['login_id', 'tester', 'The login id has already been taken.'],

            'name.null' => ['name', null, 'The name field is required.'],
            'name.empty' => ['name', '', 'The name field is required.'],
            'name.min' => ['name', 't', 'The name field must be at least 2 characters.'],
            'name.max' => ['name', str_repeat('a', 101), 'The name field must not be greater than 100 characters.'],

            'email.null' => ['email', null, 'The email field is required.'],
            'email.empty' => ['email', '', 'The email field is required.'],
            'email.max' => ['email', str_repeat('a', 101).'@example.com', 'The email field must not be greater than 100 characters.'],
            'email.invalid' => ['email', 'invalid-email', 'The email field must be a valid email address.'],
            // 'email.duplicate' => ['email', 'testing@example.com', 'The email has already been taken.'],

            'password.null' => ['password', null, 'The password field is required.'],
            'password.empty' => ['password', '', 'The password field is required.'],
            'password.min' => ['password', 'Short!', 'The password field must be at least 12 characters.'],
            'password.max' => ['password', str_repeat('a', 101).'A!', 'The password field must not be greater than 100 characters.'],
            'password.mixed' => ['password', 'testpassword!', 'The password field must contain at least one uppercase and one lowercase letter.'],
            'password.symbol' => ['password', 'TestPassword', 'The password field must contain at least one symbol.'],
        ];
    }
}
