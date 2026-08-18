@extends('layouts.admin')

@section('title', 'Flats & Residents Management')
@section('page-title', 'Flats & Occupancy Directory')

@section('header-actions')
    <a href="{{ route('admin.flats.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Add New Flat
    </a>
@endsection

@section('content')

<!-- Filter Bar -->
<div class="glass-panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.flats.index') }}" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="block" class="form-control form-control-glass" placeholder="Filter by Block" value="{{ request('block') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="flat_number" class="form-control form-control-glass" placeholder="Filter by Flat Number" value="{{ request('flat_number') }}">
        </div>
        <div class="col-md-3">
            <select name="occupancy_type" class="form-select form-select-glass">
                <option value="">All Occupancy Types</option>
                <option value="Owner" {{ request('occupancy_type') == 'Owner' ? 'selected' : '' }}>Owner</option>
                <option value="Tenant" {{ request('occupancy_type') == 'Tenant' ? 'selected' : '' }}>Tenant</option>
                <option value="Vacant" {{ request('occupancy_type') == 'Vacant' ? 'selected' : '' }}>Vacant</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary-custom w-100 justify-content-center"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Flats Table -->
<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Block</th>
                    <th>Flat Number</th>
                    <th>Floor</th>
                    <th>Occupancy</th>
                    <th>Status</th>
                    <th>Current Resident</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flats as $flat)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">{{ $flat->block_name }}</span></td>
                        <td><strong style="color: var(--dark-text);">{{ $flat->flat_number }}</strong></td>
                        <td>{{ $flat->floor ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $flat->occupancy_type == 'Owner' ? 'info' : ($flat->occupancy_type == 'Tenant' ? 'neutral' : 'warning') }}-custom">
                                {{ $flat->occupancy_type }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $flat->status == 'occupied' ? 'success' : 'warning' }}-custom">
                                {{ ucfirst($flat->status) }}
                            </span>
                        </td>
                        <td>
                            @if($flat->residentProfiles->isNotEmpty())
                                @foreach($flat->residentProfiles as $profile)
                                    @if($profile->user)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong style="color: var(--dark-text);">{{ $profile->user->name }}</strong>
                                            <span class="text-muted small">({{ $profile->user->phone ?? 'No phone' }})</span>
                                            @if($profile->user->status === 'active')
                                                <form method="POST" action="{{ route('admin.residents.offboard', $profile->user) }}" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to offboard/deactivate this resident?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 small" title="Offboard Resident">Deactivate</button>
                                                </form>
                                            @else
                                                <span class="badge badge-danger-custom">Deactivated</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-muted small">No resident linked</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.residents.create', $flat) }}" class="btn btn-sm btn-secondary-custom me-1" title="Onboard Resident"><i class="bi bi-person-plus"></i> Onboard</a>
                            <a href="{{ route('admin.flats.edit', $flat) }}" class="btn btn-sm btn-secondary-custom me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.flats.destroy', $flat) }}" class="d-inline" onsubmit="return confirm('Delete this flat?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary-custom text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No flats found matching search criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $flats->links() }}
    </div>
</div>
@endsection
