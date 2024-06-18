<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        'nickname' => 'edit_tester',
        'password' => 'Password1!',
    ];

    private function sendUserUpdate(): TestResponse
    {
        return $this->putJson('api/users', $this->data);
    }

    public function test_user_update_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendUserUpdate()
            ->assertOk()
            ->assertJsonStructure(['data' => ['nickname', 'created_at']]);
    }

    public function test_user_update_fail_with_unauthorized(): void
    {
        $this->sendUserUpdate()
            ->assertUnauthorized();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_user_update_fail($field, $value, $error): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendUserUpdate()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            /*
            'name.null' => ['name', null, 'The name field is required.'],
            'name.empty' => ['name', '', 'The name field is required.'],
            'name.min' => ['name', 'A', 'The name field must be at least 2 characters.'],
            'name.max' => ['name', str_repeat('a', 51), 'The name field must not be greater than 50 characters.'],
            */

            'nickname.null' => ['nickname', null, 'The nickname field is required.'],
            'nickname.empty' => ['nickname', '', 'The nickname field is required.'],
            'nickname.min' => ['nickname', 'A', 'The nickname field must be at least 2 characters.'],
            'nickname.max' => ['nickname', str_repeat('a', 51), 'The nickname field must not be greater than 50 characters.'],

            /*
            'email.null' => ['email', null, 'The email field is required.'],
            'email.empty' => ['email', '', 'The email field is required.'],
            'email.email' => ['email', 'test', 'The email must be a valid email address.'],
            'email.max' => ['email', str_repeat('a', 51).'@test.com', 'The email field must not be greater than 50 characters.'],
            'email.unique' => ['email', User::factory()->create()->email, 'The email has already been taken.'],
            */

            'password.min' => ['password', 'Short!', 'The password field must be at least 8 characters.'],
            'password.max' => ['password', str_repeat('a', 101).'A!', 'The password field must not be greater than 100 characters.'],
            'password.mixed' => ['password', 'testpassword!', 'The password field must contain at least one uppercase and one lowercase letter.'],
            'password.symbol' => ['password', 'TestPassword', 'The password field must contain at least one symbol.'],
        ];
    }
}
