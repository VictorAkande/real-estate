<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Welcome back</h5>
                <p class="text-muted mb-0">{{ __("You're logged in!") }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
