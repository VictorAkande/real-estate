@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">Market Trends</h1>
        <p class="text-muted">Track price shifts, demand hotspots, and emerging neighborhoods.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse ($trends as $trend)
                <div class="col-lg-4">
                    <div class="npc-card p-4 h-100">
                        @if ($trend->metric)
                            <span class="badge text-bg-light mb-2">{{ $trend->metric }}</span>
                        @endif
                        <h5 class="fw-bold">{{ $trend->title }}</h5>
                        <p class="text-muted">{{ $trend->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No market trends published yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
