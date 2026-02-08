@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="npc-admin-card p-4 h-100">
            <div class="text-muted">Active Listings</div>
            <div class="h3 fw-bold mb-0">{{ number_format($stats['listings']) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="npc-admin-card p-4 h-100">
            <div class="text-muted">Agents & Developers</div>
            <div class="h3 fw-bold mb-0">{{ number_format($stats['agents']) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="npc-admin-card p-4 h-100">
            <div class="text-muted">Locations Covered</div>
            <div class="h3 fw-bold mb-0">{{ number_format($stats['locations']) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="npc-admin-card p-4 h-100">
            <div class="text-muted">Featured Listings</div>
            <div class="h3 fw-bold mb-0">{{ number_format($stats['featured']) }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="npc-admin-card p-4 h-100">
            <h5 class="fw-bold mb-3">Recent listing approvals</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Listing</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentListings as $listing)
                            <tr>
                                <td>{{ $listing->title }}</td>
                                <td>{{ $listing->location->name ?? 'N/A' }}</td>
                                <td><span class="badge text-bg-info">{{ ucfirst($listing->status) }}</span></td>
                                <td>{{ $listing->updated_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">No listings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="npc-admin-card p-4 h-100">
            <h5 class="fw-bold mb-3">Team activity</h5>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between">
                    <span>New agent approvals</span>
                    <span class="fw-semibold">18</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Listings refreshed</span>
                    <span class="fw-semibold">96</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Content updates</span>
                    <span class="fw-semibold">12</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
