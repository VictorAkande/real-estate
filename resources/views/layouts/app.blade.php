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
        <nav class="navbar navbar-expand-lg navbar-light npc-navbar">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">Real Estate Centre</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#authNavbar" aria-controls="authNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="authNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        @if (Auth::user()?->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                        @endif
                    </ul>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="bg-white border-bottom">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
