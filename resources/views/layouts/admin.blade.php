<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin - Worlden Settler Properties' }}</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <aside class="col-lg-2 npc-admin-sidebar p-4">
                    <div class="mb-4">
                        <a class="fw-bold text-white text-decoration-none" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                        <div class="text-muted small">{{ Auth::user()->name }}</div>
                    </div>
                    <nav class="nav flex-column gap-2">
                        <a class="nav-link p-0 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a>
                        <a class="nav-link p-0 {{ request()->routeIs('admin.listings.*') ? 'active' : '' }}" href="{{ route('admin.listings.index') }}">Listings</a>
                        <a class="nav-link p-0 {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}" href="{{ route('admin.agents.index') }}">Agents & Developers</a>
                        <a class="nav-link p-0 {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}" href="{{ route('admin.locations.index') }}">Locations</a>
                        <a class="nav-link p-0 {{ request()->routeIs('admin.content.*') ? 'active' : '' }}" href="{{ route('admin.content.index') }}">Site Content</a>
                    </nav>
                    <div class="mt-5">
                        <a class="btn btn-outline-light btn-sm w-100" href="{{ route('home') }}">Back to site</a>
                    </div>
                </aside>
                <main class="col-lg-10 p-4 bg-light min-vh-100">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div>
                            <h1 class="h4 mb-1">{{ $heading ?? 'Dashboard' }}</h1>
                            <p class="text-muted mb-0">Manage listings, teams, and site content.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary" href="{{ route('profile.edit') }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Log Out</button>
                            </form>
                        </div>
                    </div>
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
