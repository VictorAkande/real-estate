@extends('layouts.admin', ['heading' => 'Site Content'])

@section('content')
<div class="npc-admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Content pages</h5>
        <a class="btn btn-primary" href="{{ route('admin.content.create') }}">Add page</a>
    </div>

    <div class="alert alert-info">
        Homepage blocks use keys: <strong>home_hero</strong>, <strong>home_featured</strong>, <strong>home_latest</strong>, <strong>home_market</strong>, <strong>home_list_property</strong>.
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Template</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td>{{ $page->key }}</td>
                        <td>{{ ucfirst($page->template ?? 'feature') }}</td>
                        <td>{{ $page->title }}</td>
                        <td>
                            <span class="badge {{ $page->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $page->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.content.edit', $page) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.content.destroy', $page) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this page?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No content pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pages->links() }}
    </div>
</div>
@endsection
