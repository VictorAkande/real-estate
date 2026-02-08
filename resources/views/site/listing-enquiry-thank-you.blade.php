@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <div class="npc-card p-5 text-center">
            <h1 class="display-6 fw-bold mb-3">Thank you</h1>
            <p class="text-muted">Your enquiry for <strong>{{ $listing->title }}</strong> has been sent. We will reach out shortly.</p>
            <a class="btn btn-primary mt-3" href="{{ route('listing.detail', $listing) }}">Back to listing</a>
        </div>
    </div>
</section>
@endsection
