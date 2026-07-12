@extends('layouts.site')

@section('content')
<section class="npc-hero py-4">
    <div class="container">
        <h1 class="display-6 fw-bold mb-2">{{ $areaGuide->name }}</h1>
        <p class="text-muted">{{ $areaGuide->state }}</p>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="npc-card p-4">
                    @if ($areaGuide->cover_image)
                        <img class="img-fluid rounded-3 mb-4" src="{{ str_starts_with($areaGuide->cover_image, 'http') ? $areaGuide->cover_image : Storage::url($areaGuide->cover_image) }}" alt="{{ $areaGuide->name }}">
                    @endif
                    <p class="lead">{{ $areaGuide->summary }}</p>
                    <div class="text-muted">{!! nl2br(e($areaGuide->body)) !!}</div>
                    <a class="btn btn-outline-primary mt-4" href="{{ route('areas') }}">Back to area guides</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
