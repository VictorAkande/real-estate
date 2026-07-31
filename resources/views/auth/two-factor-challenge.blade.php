<x-guest-layout>
    <div id="two-factor-code-section" @class(['d-none' => $errors->has('recovery_code')])>
        <p class="text-muted">
            {{ __('Please contact the site administrator for the current authentication code, then enter it below to continue.') }}
        </p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="d-grid gap-3">
            @csrf

            <div>
                <x-input-label for="code" :value="__('Code')" />
                <x-text-input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" autofocus />
                <x-input-error :messages="$errors->get('code')" />
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-link p-0" onclick="document.getElementById('two-factor-code-section').classList.add('d-none'); document.getElementById('two-factor-recovery-section').classList.remove('d-none');">
                    {{ __('Use a recovery code instead') }}
                </button>
                <x-primary-button>{{ __('Log in') }}</x-primary-button>
            </div>
        </form>
    </div>

    <div id="two-factor-recovery-section" @class(['d-none' => ! $errors->has('recovery_code')])>
        <p class="text-muted">
            {{ __('Please contact the site administrator for one of the emergency recovery codes, then enter it below to continue.') }}
        </p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="d-grid gap-3">
            @csrf

            <div>
                <x-input-label for="recovery_code" :value="__('Recovery Code')" />
                <x-text-input id="recovery_code" name="recovery_code" type="text" autocomplete="one-time-code" />
                <x-input-error :messages="$errors->get('recovery_code')" />
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-link p-0" onclick="document.getElementById('two-factor-recovery-section').classList.add('d-none'); document.getElementById('two-factor-code-section').classList.remove('d-none');">
                    {{ __('Use an authentication code instead') }}
                </button>
                <x-primary-button>{{ __('Log in') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
