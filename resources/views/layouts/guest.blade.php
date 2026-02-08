<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Real Estate') }}</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex align-items-center justify-content-center px-3">
            <div class="card shadow-sm" style="max-width: 460px; width: 100%;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none fw-bold fs-4 text-dark">Real Estate Centre</a>
                        <p class="text-muted mb-0">Access your account</p>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
