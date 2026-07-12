@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">Area Guides</h1>
        <p class="text-muted">Get local insights before you choose a neighborhood.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse ($areaGuides as $guide)
                <div class="col-md-4">
                    <div class="npc-card p-4 h-100">
                        <h5 class="fw-bold">{{ $guide->name }}</h5>
                        <p class="text-muted small mb-2">{{ $guide->state }}</p>
                        <p class="text-muted">{{ $guide->summary }}</p>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('areas.show', $guide) }}">View guide</a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No area guides published yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
