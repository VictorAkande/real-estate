@extends('layouts.site')

@section('content')
<section class="npc-hero py-4">
    <div class="container">
        <h1 class="display-6 fw-bold mb-2">{{ $post->title }}</h1>
        <p class="text-muted">{{ $post->excerpt }}</p>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="npc-card p-4">
                    @if ($post->cover_image)
                        <img class="img-fluid rounded-3 mb-4" src="{{ str_starts_with($post->cover_image, 'http') ? $post->cover_image : Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
                    @endif
                    <div class="text-muted">{!! nl2br(e($post->body)) !!}</div>
                    <a class="btn btn-outline-primary mt-4" href="{{ route('blog') }}">Back to blog</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
