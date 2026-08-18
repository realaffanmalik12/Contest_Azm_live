@extends('layouts.admin')

@section('title', 'Helpdesk Tickets')
@section('page-title', 'Helpdesk & Complaint Routing')

@section('content')

<!-- Filter Bar -->
<div class="glass-panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.complaints.index') }}" class="row g-3">
        <div class="col-md-4">
            <select name="status" class="form-select form-select-glass">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select form-select-glass">
                <option value="">All Categories</option>
                <option value="Plumbing" {{ request('category') == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                <option value="Electrical" {{ request('category') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                <option value="Elevator" {{ request('category') == 'Elevator' ? 'selected' : '' }}>Elevator</option>
                <option value="Security" {{ request('category') == 'Security' ? 'selected' : '' }}>Security</option>
                <option value="General" {{ request('category') == 'General' ? 'selected' : '' }}>General</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-secondary-custom w-100 justify-content-center"><i class="bi bi-funnel"></i> Filter Complaints</button>
        </div>
    </form>
</div>

<!-- Complaints Table -->
<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Resident / Flat</th>
                    <th>Category & Description</th>
                    <th>SLA Status</th>
                    <th>Assigned Staff</th>
                    <th>Status</th>
                    <th class="text-end">Assign Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    @php
                        $daysOpen = $complaint->created_at->diffInDays(now());
                        $isSlaBreached = $complaint->status !== 'resolved' && $daysOpen >= 3;
                    @endphp
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">#CMP-{{ $complaint->id }}</span></td>
                        <td>
                            <div><strong style="color: var(--dark-text);">{{ $complaint->residentProfile->user->name ?? 'Unknown Resident' }}</strong></div>
                            <small class="text-muted">{{ $complaint->flat->block_name ?? '' }} - {{ $complaint->flat->flat_number ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-neutral-custom mb-1">{{ $complaint->category }}</span>
                            <p class="mb-0 text-muted small" style="max-width: 280px;">{{ $complaint->description }}</p>
                        </td>
                        <td>
                            @if($complaint->status === 'resolved')
                                <span class="badge badge-success-custom"><i class="bi bi-check-all me-1"></i> Resolved</span>
                            @elseif($isSlaBreached)
                                <span class="badge badge-danger-custom"><i class="bi bi-exclamation-triangle-fill me-1"></i> SLA Breached ({{ $daysOpen }}d)</span>
                            @else
                                <span class="badge badge-info-custom"><i class="bi bi-clock me-1"></i> {{ $daysOpen }}d open</span>
                            @endif
                        </td>
                        <td>
                            @if($complaint->assignedStaff)
                                <span class="fw-semibold" style="color: var(--primary-brand);"><i class="bi bi-person-badge me-1"></i> {{ $complaint->assignedStaff->name }}</span>
                            @else
                                <span class="text-muted fst-italic small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'in_progress' ? 'info' : 'warning') }}-custom">
                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.complaints.assign', $complaint) }}" class="d-flex justify-content-end gap-1">
                                @csrf
                                <select name="assigned_staff_id" class="form-select form-select-glass form-select-sm w-auto" required>
                                    <option value="">Select Staff</option>
                                    @foreach($maintenanceStaff as $staff)
                                        <option value="{{ $staff->id }}" {{ $complaint->assigned_staff_id == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary-custom">Assign</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No helpdesk tickets found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
