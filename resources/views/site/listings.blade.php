@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">{{ $title }}</h1>
        <p class="text-muted">{{ $tagline }}</p>
        <div class="npc-hero-card p-4 mt-4">
            <form class="row g-3" method="GET" action="{{ url()->current() }}">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Title, address, keyword">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Location</label>
                    <select class="form-select" name="location_id">
                        <option value="">All locations</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" @selected(request('location_id') == $location->id)>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Property Type</label>
                    <input class="form-control" name="property_type" value="{{ request('property_type') }}" placeholder="Apartment, Land, Commercial">
                </div>
                @if (($type ?? null) !== 'land')
                    <div class="col-md-2">
                        <label class="form-label">Beds</label>
                        <select class="form-select" name="bedrooms">
                            <option value="">Any</option>
                            @foreach ([1,2,3,4,5] as $bed)
                                <option value="{{ $bed }}" @selected(request('bedrooms') == $bed)>{{ $bed }}+</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2">
                    <label class="form-label">Min Price</label>
                    <input class="form-control" name="min_price" value="{{ request('min_price') }}" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max Price</label>
                    <input class="form-control" name="max_price" value="{{ request('max_price') }}" placeholder="Any">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort</label>
                    <select class="form-select" name="sort">
                        <option value="newest" @selected(request('sort') === 'newest' || !request('sort'))>Newest</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>Price (Low to High)</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>Price (High to Low)</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Filter results</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse ($listings as $listing)
                @php
                    $imageUrl = fn (?string $path) => $path && str_starts_with($path, 'http') ? $path : Storage::url($path);
                    $slides = collect([$listing->cover_image])
                        ->merge($listing->images->pluck('image_path'))
                        ->filter()
                        ->unique()
                        ->map($imageUrl)
                        ->values();
                    $typeLabel = match ($listing->listing_type) {
                        'sale', 'land' => 'For Sale',
                        'rent' => 'For Rent',
                        'shortlet' => 'Short Let',
                        default => ucfirst($listing->listing_type),
                    };
                @endphp
                <div class="col-md-6 col-lg-3">
                    <div class="npc-card h-100">
                        <div class="npc-card-media">
                            <a class="npc-card-media-link" href="{{ route('listing.detail', $listing) }}" aria-label="{{ $listing->title }}">
                                <div class="npc-card-img" data-slides="@json($slides)" data-index="0" @if ($slides->isNotEmpty()) style="background-image: url('{{ $slides->first() }}');" @endif></div>
                            </a>
                            <div class="npc-card-badges">
                                <span class="npc-badge npc-badge-type">{{ $typeLabel }}</span>
                                @if ($listing->property_type)
                                    <span class="npc-badge npc-badge-property">{{ $listing->property_type }}</span>
                                @endif
                            </div>
                            <button type="button" class="npc-card-heart" aria-label="Save listing">
                                <svg viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg>
                            </button>
                            @if ($slides->count() > 1)
                                <button type="button" class="npc-card-nav npc-card-nav-prev" data-dir="-1" aria-label="Previous photo">&lsaquo;</button>
                                <button type="button" class="npc-card-nav npc-card-nav-next" data-dir="1" aria-label="Next photo">&rsaquo;</button>
                            @endif
                            <div class="npc-card-location">{{ $listing->location->name ?? 'Nigeria' }}</div>
                        </div>
                        <a class="text-decoration-none text-reset" href="{{ route('listing.detail', $listing) }}">
                            <div class="p-3">
                                <h6 class="fw-bold mb-1">{{ $listing->title }}</h6>
                                <div class="npc-price">₦{{ number_format($listing->price, 2) }}</div>
                                <div class="text-muted small mt-1">
                                    @if ($listing->listing_type === 'land')
                                        {{ $listing->area_sqm ?? 'N/A' }} sqm · {{ $listing->property_type }}
                                    @else
                                        {{ $listing->bedrooms ?? 0 }} Beds · {{ $listing->bathrooms ?? 0 }} Baths · {{ $listing->parking_spaces ?? 0 }} Parking
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No listings match these filters yet.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $listings->links() }}
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.npc-card-nav').forEach((btn) => {
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            const media = btn.closest('.npc-card-media');
            const img = media.querySelector('.npc-card-img');
            const slides = JSON.parse(img.dataset.slides || '[]');
            if (!slides.length) return;
            const index = (parseInt(img.dataset.index || '0', 10) + parseInt(btn.dataset.dir, 10) + slides.length) % slides.length;
            img.dataset.index = index;
            img.style.backgroundImage = `url('${slides[index]}')`;
        });
    });

    document.querySelectorAll('.npc-card-heart').forEach((btn) => {
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            btn.classList.toggle('is-active');
        });
    });
</script>
@endsection
