@extends('layouts.admin', ['heading' => 'Agents & Developers'])

@section('content')
<div class="npc-admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Partner directory</h5>
        <a class="btn btn-primary" href="{{ route('admin.agents.create') }}">Add partner</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr>
                        <td>{{ $agent->name }}</td>
                        <td>{{ $agent->is_developer ? 'Developer' : 'Agent' }}</td>
                        <td>{{ $agent->company ?? 'N/A' }}</td>
                        <td><span class="badge text-bg-info">{{ ucfirst($agent->status) }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.agents.edit', $agent) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this partner?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No partners yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $agents->links() }}
    </div>
</div>
@endsection
