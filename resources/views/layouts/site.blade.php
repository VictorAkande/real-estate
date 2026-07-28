<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Worlden Settler Properties') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-light npc-navbar sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo-navbar.png') }}" alt="Worlden Settler Properties" height="44">
                    <span>Worlden Settler Properties</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('sale') }}">Buy</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('rent') }}">Rent</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('shortlet') }}">Short Let</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('land') }}">Land</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('agents') }}">Agents</a></li>
                        {{-- Market Trends nav link hidden for MVP launch; route still live --}}
                    </ul>
                    @auth
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('dashboard') }}">Dashboard</a>
                    </div>
                    @endauth
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
                        <p class="text-muted mb-2"><a href="mailto:worldensettlerproperties@gmail.com" class="text-muted">worldensettlerproperties@gmail.com</a></p>
                        <p class="text-muted mb-2"><a href="https://wa.me/message/JTJEFNARZO4KN1" class="text-muted" target="_blank" rel="noopener">Chat with us on WhatsApp</a></p>
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="https://www.instagram.com/worldensettlerhq1" class="text-muted" target="_blank" rel="noopener">Instagram</a></li>
                            <li class="list-inline-item"><a href="https://www.tiktok.com/@worldensettlerproperties" class="text-muted" target="_blank" rel="noopener">TikTok</a></li>
                            <li class="list-inline-item"><a href="https://www.linkedin.com/in/worldensettlerproperties-933781239" class="text-muted" target="_blank" rel="noopener">LinkedIn</a></li>
                            <li class="list-inline-item"><a href="https://www.facebook.com/share/1LW3LRfVA9/" class="text-muted" target="_blank" rel="noopener">Facebook</a></li>
                            <li class="list-inline-item"><a href="https://x.com/worldenp01" class="text-muted" target="_blank" rel="noopener">Twitter/X</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6 class="text-uppercase text-muted">Company</h6>
                        <ul class="list-unstyled">
                            {{-- Property Blog / Area Guides links hidden for MVP launch; routes still live --}}
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6 class="text-uppercase text-muted">Explore</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('sale') }}">For Sale</a></li>
                            <li><a href="{{ route('rent') }}">For Rent</a></li>
                            <li><a href="{{ route('shortlet') }}">Short Let</a></li>
                            <li><a href="{{ route('land') }}">Land</a></li>
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
                        {{-- Market Trends link hidden for MVP launch; route still live --}}
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
