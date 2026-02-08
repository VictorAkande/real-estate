@extends('layouts.admin', ['heading' => 'Listings'])

@section('content')
<div class="npc-admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Manage listings</h5>
        <a class="btn btn-primary" href="{{ route('admin.listings.create') }}">Add listing</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listings as $listing)
                    <tr>
                        <td>{{ $listing->title }}</td>
                        <td>{{ ucfirst($listing->listing_type) }}</td>
                        <td>{{ $listing->location->name ?? 'N/A' }}</td>
                        <td>₦{{ number_format($listing->price, 2) }}</td>
                        <td><span class="badge text-bg-info">{{ ucfirst($listing->status) }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.listings.edit', $listing) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this listing?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">No listings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $listings->links() }}
    </div>
</div>
@endsection
