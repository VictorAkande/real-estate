@extends('layouts.site')

@section('content')
<section class="npc-hero py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="display-6 fw-bold mb-2">{{ $listing->title }}</h1>
                <div class="text-muted">{{ $listing->address }} · {{ $listing->location->name ?? 'Nigeria' }}</div>
            </div>
            <div class="text-lg-end">
                <div class="npc-price h3 mb-1">₦{{ number_format($listing->price, 2) }}</div>
                <span class="badge text-bg-light">{{ ucfirst($listing->listing_type) }}</span>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="npc-card p-3">
                    <div class="listing-gallery">
                        @php
                            $cover = $listing->cover_image
                                ? (str_starts_with($listing->cover_image, 'http') ? $listing->cover_image : Storage::url($listing->cover_image))
                                : ($listing->images->first()
                                    ? Storage::url($listing->images->first()->image_path)
                                    : null);
                        @endphp
                        <div class="listing-main" style="background-image: url('{{ $cover }}');"></div>
                        <div class="listing-thumbs">
                            @foreach ($listing->images as $image)
                                <button type="button" class="listing-thumb" data-image="{{ Storage::url($image->image_path) }}" style="background-image: url('{{ Storage::url($image->thumb_path ?? $image->image_path) }}');"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="npc-card p-4 mt-4">
                    <h4 class="fw-bold">Property details</h4>
                    <div class="row text-muted mt-3">
                        <div class="col-6 col-md-3">{{ $listing->bedrooms ?? 0 }} Beds</div>
                        <div class="col-6 col-md-3">{{ $listing->bathrooms ?? 0 }} Baths</div>
                        <div class="col-6 col-md-3">{{ $listing->toilets ?? 0 }} Toilets</div>
                        <div class="col-6 col-md-3">{{ $listing->parking_spaces ?? 0 }} Parking</div>
                        <div class="col-6 col-md-3 mt-3">{{ $listing->area_sqm ?? 'N/A' }} sqm</div>
                        <div class="col-6 col-md-3 mt-3">{{ $listing->property_type }}</div>
                    </div>
                    <p class="text-muted mt-4">{{ $listing->description ?? 'No description provided yet.' }}</p>
                </div>

                <div class="npc-card p-4 mt-4">
                    <h4 class="fw-bold">Location</h4>
                    <div class="ratio ratio-16x9 mt-3">
                        <iframe
                            title="Map"
                            loading="lazy"
                            src="https://www.google.com/maps?q={{ urlencode($listing->address.' '.$listing->location->name) }}&output=embed">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="npc-card p-4">
                    <h5 class="fw-bold">Contact agent</h5>
                    <p class="text-muted">Send an enquiry to the listing agent and get a response within 24 hours.</p>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form class="row g-3" method="POST" action="{{ route('listing.enquiry', $listing) }}">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Full name</label>
                            <input class="form-control" name="name" value="{{ old('name') }}" placeholder="Your name" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="4" placeholder="I am interested in this property..." required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Send enquiry</button>
                        </div>
                    </form>
                </div>

                <div class="npc-card p-4 mt-4">
                    <h5 class="fw-bold">Agent details</h5>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        @if ($listing->agent?->logo_url)
                            <img class="npc-logo" alt="{{ $listing->agent->name }}" src="{{ str_starts_with($listing->agent->logo_url, 'http') ? $listing->agent->logo_url : Storage::url($listing->agent->logo_thumb ?? $listing->agent->logo_url) }}">
                        @endif
                        <div>
                            <div class="fw-semibold">{{ $listing->agent->name ?? 'In-house agent' }}</div>
                            <div class="text-muted small">{{ $listing->agent->company ?? 'Worlden Settler Properties' }}</div>
                        </div>
                    </div>
                    <div class="text-muted mt-3">{{ $listing->agent->phone ?? 'Phone on request' }}</div>
                    <div class="text-muted">{{ $listing->agent->email ?? 'Email on request' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.listing-thumb').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const main = document.querySelector('.listing-main');
            const image = thumb.getAttribute('data-image');
            if (main && image) {
                main.style.backgroundImage = `url('${image}')`;
            }
        });
    });
</script>
@endsection
