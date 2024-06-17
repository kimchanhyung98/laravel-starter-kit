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
        // 'password' => 'TestPassword!',
        'deleted_reason' => 'Reason for withdrawal',
    ];

    private function sendUserDestroy(): TestResponse
    {
        return $this->postJson('api/users/delete', $this->data);
    }

    public function test_user_destroy_success(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->sendUserDestroy()
            ->assertNoContent();
    }

    public function test_user_destroy_no_reason_success(): void
    {
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
        Sanctum::actingAs(User::factory()->create());

        $this->data[$field] = $value;

        $this->sendUserDestroy()
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $error]);
    }

    public static function invalidFieldsProvider(): array
    {
        return [
            // password
            'deleted_reason.max' => ['deleted_reason', str_repeat('a', 201), 'The deleted reason field must not be greater than 200 characters.'],
        ];
    }
}
