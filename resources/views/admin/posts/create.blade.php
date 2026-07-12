@extends('layouts.admin', ['heading' => 'Add Blog Post'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.posts.store') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        <div class="col-md-8">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Excerpt</label>
            <input class="form-control" name="excerpt" value="{{ old('excerpt') }}" maxlength="255">
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover Image Upload</label>
            <input class="form-control" type="file" name="cover_image_file" accept="image/*">
        </div>
        <div class="col-12">
            <label class="form-label">Body</label>
            <textarea class="form-control" name="body" rows="10">{{ old('body') }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save post</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.posts.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
