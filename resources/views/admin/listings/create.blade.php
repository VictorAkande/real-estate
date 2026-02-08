@extends('layouts.admin', ['heading' => 'Add Listing'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.listings.store') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        <div class="col-md-8">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Listing Type</label>
            <select class="form-select" name="listing_type" required>
                @foreach (['sale' => 'Sale', 'rent' => 'Rent', 'shortlet' => 'Short Let'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('listing_type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Property Type</label>
            <input class="form-control" name="property_type" value="{{ old('property_type') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Price</label>
            <input class="form-control" name="price" value="{{ old('price') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <input class="form-control" name="status" value="{{ old('status', 'active') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Bedrooms</label>
            <input class="form-control" name="bedrooms" value="{{ old('bedrooms') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Bathrooms</label>
            <input class="form-control" name="bathrooms" value="{{ old('bathrooms') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Toilets</label>
            <input class="form-control" name="toilets" value="{{ old('toilets') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Parking</label>
            <input class="form-control" name="parking_spaces" value="{{ old('parking_spaces') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Area (sqm)</label>
            <input class="form-control" name="area_sqm" value="{{ old('area_sqm') }}">
        </div>
        <div class="col-md-5">
            <label class="form-label">Address</label>
            <input class="form-control" name="address" value="{{ old('address') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Location</label>
            <select class="form-select" name="location_id" required>
                <option value="">Select location</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('location_id') == $location->id)>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Agent/Developer</label>
            <select class="form-select" name="agent_id">
                <option value="">Unassigned</option>
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}" @selected(old('agent_id') == $agent->id)>{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover Image URL</label>
            <input class="form-control" name="cover_image" value="{{ old('cover_image') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover Image Upload</label>
            <input class="form-control" type="file" name="cover_image_file" accept="image/*">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" @checked(old('featured'))>
                <label class="form-check-label" for="featured">Featured</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save listing</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.listings.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
