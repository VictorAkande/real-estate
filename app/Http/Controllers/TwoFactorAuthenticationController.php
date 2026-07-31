<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Generate a new (unconfirmed) two-factor secret and recovery codes for the user.
     */
    public function store(Request $request, TwoFactorAuthenticationService $service): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_secret' => $service->generateSecretKey(),
            'two_factor_recovery_codes' => $service->generateRecoveryCodes(),
            'two_factor_confirmed_at' => null,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'two-factor-authentication-enabling');
    }

    /**
     * Render the QR code for the user's pending two-factor secret.
     */
    public function qrCode(Request $request, TwoFactorAuthenticationService $service): Response
    {
        $user = $request->user();

        abort_if(! $user->two_factor_secret, 404);

        return response(
            $service->qrCodeSvg($user, $user->two_factor_secret)
        )->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Confirm two-factor authentication with a code from the authenticator app.
     */
    public function confirm(Request $request, TwoFactorAuthenticationService $service): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! $user->two_factor_secret || ! $service->verify($user->two_factor_secret, $request->input('code'))) {
            throw ValidationException::withMessages([
                'code' => __('The provided code is invalid.'),
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'two-factor-authentication-confirmed');
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->hasEnabledTwoFactorAuthentication()) {
            $request->validateWithBag('twoFactorDisable', [
                'password' => ['required', 'current_password'],
            ]);
        }

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'two-factor-authentication-disabled');
    }
}
