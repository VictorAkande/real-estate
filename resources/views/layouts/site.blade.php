<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Worlden Settler Properties') }}</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-light npc-navbar sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">Worlden Settler Properties</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('sale') }}">Buy</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('rent') }}">Rent</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('shortlet') }}">Short Let</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('agents') }}">Agents</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('market') }}">Market Trends</a></li>
                    </ul>
                    <div class="d-flex gap-2">
                        @auth
                            <a class="btn btn-outline-primary" href="{{ route('dashboard') }}">Dashboard</a>
                        @else
                            <a class="btn btn-outline-primary" href="{{ route('login') }}">Log in</a>
                            <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        @yield('content')

        <footer class="npc-footer mt-5">
            <div class="container py-5">
                <div class="row gy-4">
                    <div class="col-lg-4">
                        <h5 class="fw-bold">Worlden Settler Properties</h5>
                        <p class="text-muted">Discover verified listings, connect with trusted professionals, and monitor market trends across Nigeria.</p>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6 class="text-uppercase text-muted">Company</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('blog') }}">Property Blog</a></li>
                            <li><a href="{{ route('areas') }}">Area Guides</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6 class="text-uppercase text-muted">Explore</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('sale') }}">For Sale</a></li>
                            <li><a href="{{ route('rent') }}">For Rent</a></li>
                            <li><a href="{{ route('shortlet') }}">Short Let</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="text-uppercase text-muted">Agents & Developers</h6>
                        <p class="text-muted">List your property and reach serious buyers and renters nationwide.</p>
                        <button class="btn btn-outline-light">List a Property</button>
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                    <small class="text-muted">2026 Worlden Settler Properties. All rights reserved.</small>
                    <div class="d-flex gap-3">
                        <a href="{{ route('agents') }}">Estate Agents</a>
                        <a href="{{ route('developers') }}">Developers</a>
                        <a href="{{ route('market') }}">Market Trends</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
