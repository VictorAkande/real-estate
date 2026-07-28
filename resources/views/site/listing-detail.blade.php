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
                            $imageUrl = fn (?string $path) => $path && str_starts_with($path, 'http') ? $path : Storage::url($path);
                            $cover = $listing->cover_image
                                ? $imageUrl($listing->cover_image)
                                : ($listing->images->first()
                                    ? $imageUrl($listing->images->first()->image_path)
                                    : null);
                            $slides = collect([$cover])
                                ->merge($listing->images->map(fn ($image) => $imageUrl($image->image_path)))
                                ->filter()
                                ->unique()
                                ->values();
                            $typeLabel = match ($listing->listing_type) {
                                'sale', 'land' => 'For Sale',
                                'rent' => 'For Rent',
                                'shortlet' => 'Short Let',
                                default => ucfirst($listing->listing_type),
                            };
                        @endphp
                        <div class="listing-media">
                            <div class="listing-main" style="background-image: url('{{ $cover }}');"></div>
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
                                <button type="button" class="npc-card-nav npc-card-nav-prev listing-nav" data-dir="-1" aria-label="Previous photo">&lsaquo;</button>
                                <button type="button" class="npc-card-nav npc-card-nav-next listing-nav" data-dir="1" aria-label="Next photo">&rsaquo;</button>
                            @endif
                        </div>
                        <div class="listing-thumbs">
                            @foreach ($listing->images as $image)
                                <button type="button" class="listing-thumb" data-image="{{ $imageUrl($image->image_path) }}" style="background-image: url('{{ $imageUrl($image->thumb_path ?? $image->image_path) }}');"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="npc-card p-4 mt-4">
                    <h4 class="fw-bold">Property details</h4>
                    <div class="row text-muted mt-3">
                        @if ($listing->listing_type !== 'land')
                            <div class="col-6 col-md-3">{{ $listing->bedrooms ?? 0 }} Beds</div>
                            <div class="col-6 col-md-3">{{ $listing->bathrooms ?? 0 }} Baths</div>
                            <div class="col-6 col-md-3">{{ $listing->toilets ?? 0 }} Toilets</div>
                            <div class="col-6 col-md-3">{{ $listing->parking_spaces ?? 0 }} Parking</div>
                        @endif
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
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Agent details</h5>
                        @if ($listing->agent?->phone)
                            @php
                                $agentDigits = preg_replace('/\D/', '', $listing->agent->phone);
                                $agentWaNumber = str_starts_with($agentDigits, '0') ? '234'.substr($agentDigits, 1) : $agentDigits;
                            @endphp
                            <div class="d-flex gap-2">
                                <a class="npc-icon-btn npc-icon-btn-whatsapp" href="https://wa.me/{{ $agentWaNumber }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
                                    <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.9.529 3.68 1.446 5.198L2 22l4.938-1.294A9.955 9.955 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm5.472 12.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"></path></svg>
                                </a>
                                <a class="npc-icon-btn npc-icon-btn-call" href="tel:{{ $listing->agent->phone }}" aria-label="Call agent">
                                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.902.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.908.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </a>
                            </div>
                        @endif
                    </div>
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
    const listingSlides = @json($slides ?? []);
    const listingMain = document.querySelector('.listing-main');
    let listingSlideIndex = 0;

    document.querySelectorAll('.listing-thumb').forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            const image = thumb.getAttribute('data-image');
            if (listingMain && image) {
                listingMain.style.backgroundImage = `url('${image}')`;
                listingSlideIndex = index;
            }
        });
    });

    document.querySelectorAll('.listing-nav').forEach((btn) => {
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            if (!listingMain || !listingSlides.length) return;
            listingSlideIndex = (listingSlideIndex + parseInt(btn.dataset.dir, 10) + listingSlides.length) % listingSlides.length;
            listingMain.style.backgroundImage = `url('${listingSlides[listingSlideIndex]}')`;
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
