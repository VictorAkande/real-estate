<x-guest-layout>
    <p class="text-muted mb-3">{{ __('Forgot your password? We will email a reset link to the address on your account.') }}</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
    </form>
</x-guest-layout>
