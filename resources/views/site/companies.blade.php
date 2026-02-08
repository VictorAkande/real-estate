@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">{{ $title }}</h1>
        <p class="text-muted">{{ $tagline }}</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse ($companies as $company)
                <div class="col-md-4">
                    <div class="npc-card p-4 h-100">
                        @if ($company->logo_thumb || $company->logo_url)
                            <img class="npc-logo" alt="{{ $company->name }}" src="{{ str_starts_with($company->logo_url, 'http') ? $company->logo_url : Storage::url($company->logo_thumb ?? $company->logo_url) }}">
                        @endif
                        <h5 class="fw-bold mb-1">{{ $company->name }}</h5>
                        <p class="text-muted mb-3">{{ $company->company ?? 'Independent professional' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ $company->address ?? 'Nigeria' }}</span>
                            <span class="badge text-bg-light">{{ ucfirst($company->status) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No partners yet.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $companies->links() }}
        </div>
    </div>
</section>
@endsection
