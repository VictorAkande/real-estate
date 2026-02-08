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
            <div class="col-lg-4">
                <div class="npc-card p-4 h-100">
                    <h5 class="fw-bold">Average prices</h5>
                    <p class="text-muted">Analyze pricing benchmarks for apartments, houses, and land.</p>
                    <button class="btn btn-outline-primary">View report</button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="npc-card p-4 h-100">
                    <h5 class="fw-bold">Demand hotspots</h5>
                    <p class="text-muted">Spot fast-moving locations and track new demand signals.</p>
                    <button class="btn btn-outline-primary">Explore demand</button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="npc-card p-4 h-100">
                    <h5 class="fw-bold">Rental yields</h5>
                    <p class="text-muted">Compare rental yields across leading neighborhoods.</p>
                    <button class="btn btn-outline-primary">Compare yields</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
