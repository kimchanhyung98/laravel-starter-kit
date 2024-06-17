<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use RefreshDatabase;

    protected array $data = [
        // 'password' => 'Password1!',
        'deleted_reason' => 'Reason for withdrawal',
    ];

    private function sendUserDestroy(): TestResponse
    {
        // return $this->postJson('api/users/delete', $this->data);
        return $this->deleteJson('api/users', $this->data);
    }

    public function test_user_destroy_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendUserDestroy()
            ->assertNoContent();
    }

    public function test_user_destroy_no_reason_success(): void
    {
        $this->markTestSkipped('Change the route to POST method.');

        Sanctum::actingAs(User::factory()->create());

        $this->data['deleted_reason'] = null;

        $this->sendUserDestroy()
            ->assertNoContent();
    }

    public function test_user_destroy_fail_with_unauthorized(): void
    {
        $this->sendUserDestroy()
            ->assertUnauthorized();
    }

    #[DataProvider('invalidFieldsProvider')]
    public function test_signin_fail($field, $value, $error): void
    {
        $this->markTestSkipped('Change the route to POST method.');

        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendUserDestroy()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            /*
            'password.null' => ['password', null, 'The password field is required.'],
            'password.empty' => ['password', '', 'The password field is required.'],
            'password.min' => ['password', 'Short!', 'The password field must be at least 8 characters.'],
            'password.max' => ['password', str_repeat('a', 100).'A!', 'The password field must not be greater than 100 characters.'],
            */
            'deleted_reason.max' => ['deleted_reason', str_repeat('a', 201), 'The deleted reason field must not be greater than 200 characters.'],
        ];
    }
}
