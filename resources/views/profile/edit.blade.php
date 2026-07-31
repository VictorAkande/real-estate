<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @if (session('status') === 'two-factor-required')
            <div class="alert alert-warning">
                {{ __('Two-factor authentication is required for admin accounts. Please set it up below.') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                    @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                    @include('profile.partials.two-factor-authentication-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                    @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
