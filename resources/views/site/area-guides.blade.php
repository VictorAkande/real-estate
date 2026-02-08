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
            @foreach (['Ikoyi', 'Lekki', 'Maitama', 'Gwarinpa', 'Port Harcourt'] as $area)
                <div class="col-md-4">
                    <div class="npc-card p-4 h-100">
                        <h5 class="fw-bold">{{ $area }}</h5>
                        <p class="text-muted">Lifestyle overview, rental expectations, and local amenities.</p>
                        <button class="btn btn-outline-primary btn-sm">View guide</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
