<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function enableTwoFactor(User $user): string
    {
        $secret = app(TwoFactorAuthenticationService::class)->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => ['recovery-code-one', 'recovery-code-two'],
            'two_factor_confirmed_at' => now(),
        ])->save();

        return $secret;
    }

    public function test_login_redirects_to_challenge_when_two_factor_is_enabled(): void
    {
        $user = User::factory()->create();
        $this->enableTwoFactor($user);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
        $this->assertEquals($user->id, session('login.id'));
    }

    public function test_challenge_completes_login_with_valid_code(): void
    {
        $user = User::factory()->create();
        $secret = $this->enableTwoFactor($user);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $code = app(Google2FA::class)->getCurrentOtp($secret);

        $response = $this->post('/two-factor-challenge', [
            'code' => $code,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_challenge_rejects_invalid_code(): void
    {
        $user = User::factory()->create();
        $this->enableTwoFactor($user);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post('/two-factor-challenge', [
            'code' => '000000',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('code');
    }

    public function test_challenge_completes_login_with_valid_recovery_code(): void
    {
        $user = User::factory()->create();
        $this->enableTwoFactor($user);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post('/two-factor-challenge', [
            'recovery_code' => 'recovery-code-one',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertEquals(['recovery-code-two'], $user->fresh()->two_factor_recovery_codes);
    }

    public function test_admin_without_two_factor_is_redirected_away_from_admin_area(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_admin_with_two_factor_can_access_admin_area(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->enableTwoFactor($admin);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
    }
}
