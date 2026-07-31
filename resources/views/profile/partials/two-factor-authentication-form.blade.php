@php
    $pending = $user->two_factor_secret && ! $user->hasEnabledTwoFactorAuthentication();
@endphp

<section>
    <header>
        <h2 class="h5 mb-1">
            {{ __('Two-Factor Authentication') }}
        </h2>

        <p class="text-muted mb-0">
            {{ __('Add additional security to your account by requiring a code from an authenticator app (such as Google Authenticator) at login.') }}
        </p>
    </header>

    <div class="mt-3">
        @if (! $user->two_factor_secret)
            <p class="text-muted">{{ __('You have not enabled two-factor authentication.') }}</p>

            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <x-primary-button>{{ __('Enable Two-Factor Authentication') }}</x-primary-button>
            </form>
        @elseif ($pending)
            <p class="text-muted">
                {{ __('Scan the QR code below with your authenticator app, then enter the generated code to finish enabling two-factor authentication.') }}
            </p>

            <img src="{{ route('two-factor.qr-code') }}" alt="{{ __('Two-factor authentication QR code') }}" width="200" height="200">

            <p class="text-muted small mt-2">
                {{ __('If you cannot scan the code, enter this setup key manually:') }}
                <code>{{ $user->two_factor_secret }}</code>
            </p>

            <form method="POST" action="{{ route('two-factor.confirm') }}" class="d-grid gap-3" style="max-width: 20rem;">
                @csrf

                <div>
                    <x-input-label for="two_factor_code" :value="__('Code')" />
                    <x-text-input id="two_factor_code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" />
                    <x-input-error :messages="$errors->get('code')" />
                </div>

                <div class="d-flex gap-2">
                    <x-primary-button>{{ __('Confirm') }}</x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-2">
                @csrf
                @method('delete')
                <x-secondary-button>{{ __('Cancel Setup') }}</x-secondary-button>
            </form>
        @else
            <p class="text-success">{{ __('Two-factor authentication is enabled.') }}</p>

            <div class="mb-3">
                <p class="mb-1">
                    {{ __('Recovery codes — store these somewhere safe. Each one can be used once to log in if you lose access to your authenticator app.') }}
                </p>
                <div class="bg-light p-3 rounded font-monospace small">
                    @foreach ($user->recoveryCodes() as $recoveryCode)
                        <div>{{ $recoveryCode }}</div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#confirmTwoFactorDisable">
                {{ __('Disable Two-Factor Authentication') }}
            </button>

            <div class="modal fade" id="confirmTwoFactorDisable" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('delete')
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('Disable two-factor authentication') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">{{ __('Please enter your password to confirm you would like to disable two-factor authentication.') }}</p>
                                <x-input-label for="two_factor_disable_password" value="{{ __('Password') }}" class="sr-only" />
                                <x-text-input id="two_factor_disable_password" name="password" type="password" placeholder="{{ __('Password') }}" />
                                <x-input-error :messages="$errors->twoFactorDisable->get('password')" />
                            </div>
                            <div class="modal-footer">
                                <x-secondary-button type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
                                <x-danger-button class="ms-2">{{ __('Disable Two-Factor Authentication') }}</x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
