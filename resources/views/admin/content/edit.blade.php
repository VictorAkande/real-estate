@extends('layouts.admin', ['heading' => 'Edit Content Page'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.content.update', $page) }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-4">
            <label class="form-label">Key</label>
            <input class="form-control" name="key" value="{{ old('key', $page->key) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Template</label>
            <select class="form-select" name="template" required>
                @foreach (['hero' => 'Hero', 'feature' => 'Feature', 'stats' => 'Stats'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('template', $page->template ?? 'feature') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" value="{{ old('title', $page->title) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Subtitle</label>
            <input class="form-control" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $page->is_active))>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">CTA Text</label>
            <input class="form-control" name="cta_text" value="{{ old('cta_text', $page->cta_text) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">CTA Link</label>
            <input class="form-control" name="cta_link" value="{{ old('cta_link', $page->cta_link) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Image Upload</label>
            <input class="form-control" type="file" name="image_file" accept="image/*">
            @if ($page->image_path)
                <div class="small text-muted mt-1">Current: {{ $page->image_path }}</div>
            @endif
        </div>
        <div class="col-12">
            <label class="form-label">Body</label>
            <textarea class="form-control" name="body" rows="6">{{ old('body', $page->body) }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update page</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.content.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
