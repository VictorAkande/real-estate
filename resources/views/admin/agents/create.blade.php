@extends('layouts.admin', ['heading' => 'Add Partner'])

@section('content')
<div class="npc-admin-card p-4">
    <form method="POST" action="{{ route('admin.agents.store') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Company</label>
            <input class="form-control" name="company" value="{{ old('company') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" value="{{ old('email') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input class="form-control" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Website</label>
            <input class="form-control" name="website" value="{{ old('website') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Logo URL</label>
            <input class="form-control" name="logo_url" value="{{ old('logo_url') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Logo Upload</label>
            <input class="form-control" type="file" name="logo_file" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input class="form-control" name="address" value="{{ old('address') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <input class="form-control" name="status" value="{{ old('status', 'active') }}" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_developer" value="1" id="is_developer" @checked(old('is_developer'))>
                <label class="form-check-label" for="is_developer">Developer</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Bio</label>
            <textarea class="form-control" name="bio" rows="4">{{ old('bio') }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save partner</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
