<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorAuthenticatedSessionController extends Controller
{
    /**
     * Display the two-factor authentication challenge view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the two-factor authentication challenge and complete the login.
     */
    public function store(Request $request, TwoFactorAuthenticationService $service): RedirectResponse
    {
        $userId = $request->session()->get('login.id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $user = User::findOrFail($userId);
        $authority = User::twoFactorAuthority();

        if ($code = $request->input('code')) {
            if (! $authority || ! $service->verify($authority->two_factor_secret, $code)) {
                throw ValidationException::withMessages([
                    'code' => __('The provided code is invalid.'),
                ]);
            }
        } elseif ($recoveryCode = $request->input('recovery_code')) {
            $codes = $authority?->recoveryCodes() ?? [];
            $matched = collect($codes)->first(fn ($stored) => hash_equals($stored, $recoveryCode));

            if (! $matched) {
                throw ValidationException::withMessages([
                    'recovery_code' => __('The provided recovery code is invalid.'),
                ]);
            }

            $authority->forceFill([
                'two_factor_recovery_codes' => array_values(array_diff($codes, [$matched])),
            ])->save();
        } else {
            throw ValidationException::withMessages([
                'code' => __('Please provide an authentication code or recovery code.'),
            ]);
        }

        $remember = $request->session()->pull('login.remember', false);
        $request->session()->forget('login.id');

        Auth::login($user, $remember);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
