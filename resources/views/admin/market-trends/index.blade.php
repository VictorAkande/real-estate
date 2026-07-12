@extends('layouts.admin', ['heading' => 'Market Trends'])

@section('content')
<div class="npc-admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Market trend cards</h5>
        <a class="btn btn-primary" href="{{ route('admin.market-trends.create') }}">Add trend</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Metric</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($marketTrends as $trend)
                    <tr>
                        <td>{{ $trend->title }}</td>
                        <td>{{ $trend->metric }}</td>
                        <td>{{ $trend->sort_order }}</td>
                        <td>
                            <span class="badge {{ $trend->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $trend->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.market-trends.edit', $trend) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.market-trends.destroy', $trend) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this market trend?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No market trends yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $marketTrends->links() }}
    </div>
</div>
@endsection
