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
            @forelse ($posts as $post)
                <div class="col-md-4">
                    <div class="npc-card h-100">
                        @if ($post->cover_image)
                            <div class="npc-card-img" style="background-image: url('{{ str_starts_with($post->cover_image, 'http') ? $post->cover_image : Storage::url($post->cover_thumb ?? $post->cover_image) }}');"></div>
                        @endif
                        <div class="p-4">
                            <h5 class="fw-bold">{{ $post->title }}</h5>
                            <p class="text-muted">{{ $post->excerpt }}</p>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('blog.show', $post) }}">Read more</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No blog posts published yet.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    </div>
</section>
@endsection
