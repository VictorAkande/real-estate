@extends('layouts.site')

@section('content')
@php
    $hero = $contentBlocks['home_hero'] ?? null;
    $featuredBlock = $contentBlocks['home_featured'] ?? null;
    $latestBlock = $contentBlocks['home_latest'] ?? null;
    $marketBlock = $contentBlocks['home_market'] ?? null;
    $listBlock = $contentBlocks['home_list_property'] ?? null;
    $marketStats = $marketBlock?->template === 'stats' && $marketBlock->body
        ? array_filter(array_map('trim', explode("\n", $marketBlock->body)))
        : [];
    $heroHasImage = $hero?->template === 'hero' && $hero?->image_path;
    $heroImageUrl = $heroHasImage
        ? (str_starts_with($hero->image_path, 'http') ? $hero->image_path : Storage::url($hero->image_path))
        : null;
@endphp

<section class="npc-hero py-5 {{ $heroHasImage ? 'npc-hero-photo' : '' }}" @if ($heroImageUrl) style="background-image: url('{{ $heroImageUrl }}');" @endif>
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <span class="npc-chip">{{ $hero->subtitle ?? "Nigeria's property marketplace" }}</span>
                <h1 class="display-5 fw-bold mt-3">{{ $hero->title ?? 'Find your next property in Nigeria' }}</h1>
                <p class="lead text-muted">{{ $hero->body ?? 'Search verified homes, land, and commercial spaces. Compare listings, connect with trusted agents, and move with confidence.' }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary btn-lg" href="{{ $hero->cta_link ?? route('sale') }}">{{ $hero->cta_text ?? 'Browse for Sale' }}</a>
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('rent') }}">Browse for Rent</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="npc-hero-card p-4">
                    <h5 class="fw-bold">Search properties</h5>
                    <form class="row g-3 mt-1" method="GET" action="{{ route('sale') }}" id="heroSearchForm">
                        <div class="col-md-6">
                            <label class="form-label">Transaction</label>
                            <select class="form-select" name="transaction" id="heroTransaction">
                                <option value="sale">Buy</option>
                                <option value="rent">Rent</option>
                                <option value="shortlet">Short Let</option>
                                <option value="land">Land</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Property Type</label>
                            <input class="form-control" name="property_type" placeholder="Apartment, Land, Commercial" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <select class="form-select" name="location_id">
                                <option value="">All locations</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Min Price</label>
                            <input class="form-control" name="min_price" placeholder="0" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Max Price</label>
                            <input class="form-control" name="max_price" placeholder="Any" />
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Search properties</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const heroForm = document.getElementById('heroSearchForm');
    const heroTransaction = document.getElementById('heroTransaction');

    if (heroForm && heroTransaction) {
        const routeMap = {
            sale: "{{ route('sale') }}",
            rent: "{{ route('rent') }}",
            shortlet: "{{ route('shortlet') }}",
            land: "{{ route('land') }}",
        };

        const syncAction = () => {
            heroForm.action = routeMap[heroTransaction.value] || routeMap.sale;
        };

        syncAction();
        heroTransaction.addEventListener('change', syncAction);
        heroForm.addEventListener('submit', syncAction);
    }
</script>

<section class="py-5 bg-white">
    <div class="container">
        <div class="mb-4">
            <h2 class="npc-section-title">Watch &amp; explore</h2>
            <p class="text-muted">See what's available across our categories in under a minute.</p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach ([
                ['label' => 'Shortlet', 'video' => 'bqdEiKfdk_8', 'route' => 'shortlet'],
                ['label' => 'Rental', 'video' => 'lCZTSPGAgUw', 'route' => 'rent'],
                ['label' => 'Front Page', 'video' => 'tr9bpPCO9UI', 'route' => 'home'],
                ['label' => 'Investment', 'video' => 'bYKSOd442mo', 'route' => 'market'],
                ['label' => 'Realtors', 'video' => 'ifpPtWL5awY', 'route' => 'agents'],
                ['label' => 'Not yet upload', 'video' => 'dZrhegqp9Jw', 'href' => 'https://youtube.com/shorts/dZrhegqp9Jw?si=bSN2hR6qtcZ4Ltxc'],
            ] as $video)
                <div class="col">
                    <div class="npc-card h-100">
                        <div class="ratio ratio-9x16">
                            <iframe src="https://www.youtube.com/embed/{{ $video['video'] }}" title="{{ $video['label'] }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="p-3 text-center">
                            <a class="btn btn-outline-primary btn-sm w-100" href="{{ $video['href'] ?? route($video['route']) }}" @if(isset($video['href'])) target="_blank" rel="noopener" @endif>{{ $video['label'] }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="npc-section-title">{{ $featuredBlock->title ?? 'Featured real estate companies' }}</h2>
                <p class="text-muted">{{ $featuredBlock->body ?? 'Connect with agencies that are active in your target locations.' }}</p>
            </div>
            <a class="btn btn-outline-primary" href="{{ route('agents') }}">View all</a>
        </div>
        <div class="row g-4">
            @forelse ($featuredAgents as $company)
                <div class="col-md-4">
                    <div class="npc-card p-4 h-100">
                        @if ($company->logo_thumb || $company->logo_url)
                            <img class="npc-logo" alt="{{ $company->name }}" src="{{ str_starts_with($company->logo_url, 'http') ? $company->logo_url : Storage::url($company->logo_thumb ?? $company->logo_url) }}">
                        @endif
                        <h5 class="fw-bold mb-1">{{ $company->name }}</h5>
                        <p class="text-muted mb-2">{{ $company->company ?? 'Independent agent' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ $company->address ?? 'Nigeria' }}</span>
                            <span class="badge text-bg-light">Verified</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No agents yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="npc-section-title">{{ $latestBlock->title ?? 'Latest listed properties' }}</h2>
                <p class="text-muted">{{ $latestBlock->body ?? 'Fresh listings updated daily from trusted partners.' }}</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary" href="{{ route('sale') }}">For Sale</a>
                <a class="btn btn-outline-primary" href="{{ route('rent') }}">For Rent</a>
                <a class="btn btn-outline-primary" href="{{ route('land') }}">Land</a>
            </div>
        </div>
        <div class="row g-4">
            @forelse ($latestListings as $listing)
                <div class="col-md-6 col-lg-3">
                    <a class="text-decoration-none text-reset" href="{{ route('listing.detail', $listing) }}">
                        <div class="npc-card h-100">
                            <div class="npc-card-img" @if ($listing->cover_image) style="background-image: url('{{ str_starts_with($listing->cover_image, 'http') ? $listing->cover_image : Storage::url($listing->cover_thumb ?? $listing->cover_image) }}');" @endif></div>
                            <div class="p-3">
                                <h6 class="fw-bold mb-1">{{ $listing->title }}</h6>
                                <p class="text-muted small mb-2">{{ $listing->location->name ?? 'Nigeria' }}</p>
                                <div class="npc-price">₦{{ number_format($listing->price, 2) }}</div>
                                <div class="text-muted small mt-1">
                                    {{ $listing->bedrooms ?? 0 }} Beds · {{ $listing->bathrooms ?? 0 }} Baths · {{ $listing->parking_spaces ?? 0 }} Parking
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No listings yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="npc-card p-4 h-100">
                    <h4 class="fw-bold">{{ $marketBlock->title ?? 'Market demand trends' }}</h4>
                    <p class="text-muted">{{ $marketBlock->body ?? 'Track high-demand locations, price changes, and rental yields in real time.' }}</p>
                    @if ($marketBlock?->template === 'feature' && $marketBlock?->image_path)
                        <img class="img-fluid rounded-3 mt-3" src="{{ str_starts_with($marketBlock->image_path, 'http') ? $marketBlock->image_path : Storage::url($marketBlock->image_path) }}" alt="Market insights">
                    @endif
                    @if ($marketBlock?->template === 'stats')
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach ($marketStats as $stat)
                                <span class="badge text-bg-light">{{ $stat }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="npc-card p-4 h-100">
                    <h4 class="fw-bold">{{ $listBlock->title ?? 'List property in minutes' }}</h4>
                    <p class="text-muted">{{ $listBlock->body ?? 'Agents and developers can reach serious property hunters nationwide.' }}</p>
                    @if ($listBlock?->template === 'feature' && $listBlock?->image_path)
                        <img class="img-fluid rounded-3 mb-3" src="{{ str_starts_with($listBlock->image_path, 'http') ? $listBlock->image_path : Storage::url($listBlock->image_path) }}" alt="List property">
                    @endif
                    <a class="btn btn-primary" href="{{ $listBlock->cta_link ?? route('agents') }}">{{ $listBlock->cta_text ?? 'Get started' }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
