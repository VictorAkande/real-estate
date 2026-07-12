@extends('layouts.admin', ['heading' => 'Area Guides'])

@section('content')
<div class="npc-admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Area guides</h5>
        <a class="btn btn-primary" href="{{ route('admin.area-guides.create') }}">Add guide</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>State</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($areaGuides as $guide)
                    <tr>
                        <td>{{ $guide->name }}</td>
                        <td>{{ $guide->state }}</td>
                        <td>{{ $guide->slug }}</td>
                        <td>
                            <span class="badge {{ $guide->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $guide->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.area-guides.edit', $guide) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.area-guides.destroy', $guide) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this area guide?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No area guides yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $areaGuides->links() }}
    </div>
</div>
@endsection
