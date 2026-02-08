<x-guest-layout>
    <p class="text-muted mb-3">{{ __('Thanks for signing up! Please verify your email address by clicking the link we emailed you.') }}</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">{{ __('A new verification link has been sent to your email address.') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none">{{ __('Log Out') }}</button>
        </form>
    </div>
</x-guest-layout>
