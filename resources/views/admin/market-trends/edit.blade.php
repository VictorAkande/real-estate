@extends('layouts.admin', ['heading' => 'Edit Market Trend'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.market-trends.update', $trend) }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-8">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" value="{{ old('title', $trend->title) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Metric</label>
            <input class="form-control" name="metric" value="{{ old('metric', $trend->metric) }}" placeholder="e.g. +8.2% YoY">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sort Order</label>
            <input class="form-control" type="number" min="0" name="sort_order" value="{{ old('sort_order', $trend->sort_order) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $trend->is_active))>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description', $trend->description) }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update trend</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.market-trends.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
