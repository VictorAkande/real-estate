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
                <div class="col-md-2">
                    <label class="form-label">Beds</label>
                    <select class="form-select" name="bedrooms">
                        <option value="">Any</option>
                        @foreach ([1,2,3,4,5] as $bed)
                            <option value="{{ $bed }}" @selected(request('bedrooms') == $bed)>{{ $bed }}+</option>
                        @endforeach
                    </select>
                </div>
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
                    <div class="alert alert-light">No listings match these filters yet.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $listings->links() }}
        </div>
    </div>
</section>
@endsection
