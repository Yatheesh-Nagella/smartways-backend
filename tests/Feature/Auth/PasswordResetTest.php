<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        // Update to use API endpoint with JSON
        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'We have emailed your password reset link.']);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        // Send reset request via API
        $this->postJson('/api/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
            // Test API reset endpoint with JSON
            $response = $this->postJson('/api/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

            $response->assertStatus(200)
                     ->assertJson(['message' => 'Password has been reset successfully!']);

            return true;
        });
    }

    public function test_password_reset_requires_valid_email(): void
    {
        $response = $this->postJson('/api/forgot-password', ['email' => 'invalid-email']);

        $response->assertStatus(422);
    }

    public function test_password_reset_requires_existing_user(): void
    {
        $response = $this->postJson('/api/forgot-password', ['email' => 'nonexistent@example.com']);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message']);
    }

    public function test_password_reset_requires_valid_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(422);
    }

    public function test_password_reset_requires_matching_passwords(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->postJson('/api/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
            $response = $this->postJson('/api/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'newpassword123',
                'password_confirmation' => 'differentpassword',
            ]);

            $response->assertStatus(422);
            return true;
        });
    }
}