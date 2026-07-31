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

    protected function makeAuthority(): User
    {
        return User::factory()->create(['id' => 1]);
    }

    protected function enableTwoFactor(User $authority): string
    {
        $secret = app(TwoFactorAuthenticationService::class)->generateSecretKey();

        $authority->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => ['recovery-code-one', 'recovery-code-two'],
            'two_factor_confirmed_at' => now(),
        ])->save();

        return $secret;
    }

    public function test_login_is_not_challenged_while_authority_has_no_two_factor_configured(): void
    {
        $this->makeAuthority();
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_any_users_login_is_challenged_once_authority_has_two_factor_enabled(): void
    {
        $authority = $this->makeAuthority();
        $this->enableTwoFactor($authority);
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
        $this->assertEquals($user->id, session('login.id'));
    }

    public function test_authoritys_own_login_is_also_challenged(): void
    {
        $authority = $this->makeAuthority();
        $this->enableTwoFactor($authority);

        $response = $this->post('/login', [
            'email' => $authority->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
    }

    public function test_challenge_completes_login_for_the_original_user_with_authoritys_code(): void
    {
        $authority = $this->makeAuthority();
        $secret = $this->enableTwoFactor($authority);
        $user = User::factory()->create();

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
        $authority = $this->makeAuthority();
        $this->enableTwoFactor($authority);
        $user = User::factory()->create();

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

    public function test_challenge_completes_login_with_authoritys_recovery_code(): void
    {
        $authority = $this->makeAuthority();
        $this->enableTwoFactor($authority);
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post('/two-factor-challenge', [
            'recovery_code' => 'recovery-code-one',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertEquals(['recovery-code-two'], $authority->fresh()->two_factor_recovery_codes);
    }

    public function test_authority_without_two_factor_is_redirected_away_from_admin_area(): void
    {
        $authority = $this->makeAuthority();
        $authority->forceFill(['is_admin' => true])->save();

        $response = $this->actingAs($authority)->get('/admin');

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_non_authority_admin_is_not_forced_into_two_factor_setup(): void
    {
        $this->makeAuthority();
        $otherAdmin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($otherAdmin)->get('/admin');

        $response->assertOk();
    }

    public function test_non_authority_cannot_manage_two_factor(): void
    {
        $this->makeAuthority();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('two-factor.enable'))->assertForbidden();
        $this->actingAs($user)->get(route('two-factor.qr-code'))->assertForbidden();
        $this->actingAs($user)->post(route('two-factor.confirm'))->assertForbidden();
        $this->actingAs($user)->delete(route('two-factor.disable'))->assertForbidden();
    }

    public function test_authority_can_enable_and_confirm_two_factor_from_profile(): void
    {
        $authority = $this->makeAuthority();

        $enableResponse = $this->actingAs($authority)->post(route('two-factor.enable'));
        $enableResponse->assertRedirect(route('profile.edit'));

        $authority->refresh();
        $this->assertNotNull($authority->two_factor_secret);
        $this->assertFalse($authority->hasEnabledTwoFactorAuthentication());

        $code = app(Google2FA::class)->getCurrentOtp($authority->two_factor_secret);

        $confirmResponse = $this->post(route('two-factor.confirm'), ['code' => $code]);
        $confirmResponse->assertRedirect(route('profile.edit'));

        $this->assertTrue($authority->fresh()->hasEnabledTwoFactorAuthentication());
    }

    public function test_disabling_confirmed_two_factor_requires_correct_password(): void
    {
        $authority = $this->makeAuthority();
        $this->enableTwoFactor($authority);

        $wrongPassword = $this->actingAs($authority)->delete(route('two-factor.disable'), [
            'password' => 'not-the-password',
        ]);
        $wrongPassword->assertSessionHasErrorsIn('twoFactorDisable', 'password');
        $this->assertTrue($authority->fresh()->hasEnabledTwoFactorAuthentication());

        $correctPassword = $this->delete(route('two-factor.disable'), [
            'password' => 'password',
        ]);
        $correctPassword->assertRedirect(route('profile.edit'));
        $this->assertFalse($authority->fresh()->hasEnabledTwoFactorAuthentication());
    }

    public function test_cancelling_pending_two_factor_setup_does_not_require_password(): void
    {
        $authority = $this->makeAuthority();
        $this->actingAs($authority)->post(route('two-factor.enable'));

        $response = $this->delete(route('two-factor.disable'));

        $response->assertRedirect(route('profile.edit'));
        $this->assertNull($authority->fresh()->two_factor_secret);
    }
}
