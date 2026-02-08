@extends('layouts.admin', ['heading' => 'Edit Location'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <label class="form-label">Location Name</label>
            <input class="form-control" name="name" value="{{ old('name', $location->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">State</label>
            <input class="form-control" name="state" value="{{ old('state', $location->state) }}" required>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description', $location->description) }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update location</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.locations.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
