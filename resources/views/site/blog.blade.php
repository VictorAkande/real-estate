@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">Property Blog</h1>
        <p class="text-muted">Insights, guides, and news from the property market.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach (range(1, 6) as $item)
                <div class="col-md-4">
                    <div class="npc-card p-4 h-100">
                        <span class="badge text-bg-light mb-2">Market Update</span>
                        <h5 class="fw-bold">Top places to invest in {{ 2020 + $item }}</h5>
                        <p class="text-muted">Understand demand trends, price signals, and investor activity.</p>
                        <button class="btn btn-outline-primary btn-sm">Read more</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
