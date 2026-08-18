@extends('layouts.maintenance')

@section('title', 'Assigned Work Ticket Queue')

@section('content')

<!-- Metric Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Pending Work</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--warning-color);">{{ $pendingCount }}</h2>
                    <span class="badge badge-warning-custom"><i class="bi bi-clock me-1"></i> Awaiting Action</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(211, 84, 0, 0.15); color: #B94A00;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">In Progress</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--info-color);">{{ $inProgressCount }}</h2>
                    <span class="badge badge-info-custom"><i class="bi bi-wrench me-1"></i> Active Repairs</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(41, 128, 185, 0.15); color: #1F618D;">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Resolved Tickets</span>
                    <h2 class="font-heading fw-bold my-1" style="color: #1E8449;">{{ $resolvedCount }}</h2>
                    <span class="badge badge-success-custom"><i class="bi bi-check-all me-1"></i> Completed Work</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Queue Filter -->
<div class="glass-panel p-3 mb-4">
    <form method="GET" action="{{ route('maintenance.dashboard') }}" class="row g-3 align-items-center">
        <div class="col-md-8">
            <select name="status" class="form-select form-select-glass">
                <option value="">All Ticket Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-secondary-custom w-100 justify-content-center"><i class="bi bi-funnel me-1"></i> Filter Queue</button>
        </div>
    </form>
</div>

<!-- Work Tickets Table -->
<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Location / Resident</th>
                    <th>Category & Description</th>
                    <th>Logged Date</th>
                    <th>Status</th>
                    <th class="text-end">Update Progress</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">#CMP-{{ $ticket->id }}</span></td>
                        <td>
                            <div><strong style="color: var(--dark-text);">{{ $ticket->flat->block_name ?? '' }} - {{ $ticket->flat->flat_number ?? '' }}</strong></div>
                            <small class="text-muted">{{ $ticket->residentProfile->user->name ?? 'Resident' }} ({{ $ticket->residentProfile->user->phone ?? 'No contact' }})</small>
                        </td>
                        <td>
                            <span class="badge badge-neutral-custom mb-1">{{ $ticket->category }}</span>
                            <p class="mb-0 text-muted small" style="max-width: 320px;">{{ $ticket->description }}</p>
                            @if($ticket->photo_url)
                                <small class="d-block mt-1"><a href="{{ asset('storage/' . $ticket->photo_url) }}" target="_blank" class="text-decoration-none" style="color: var(--accent-brown);"><i class="bi bi-image me-1"></i> View Photo Attachment</a></small>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $ticket->created_at->format('Y-m-d H:i') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $ticket->status == 'resolved' ? 'success' : ($ticket->status == 'in_progress' ? 'info' : 'warning') }}-custom">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($ticket->status !== 'resolved')
                                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#updateModal_{{ $ticket->id }}">
                                    <i class="bi bi-pencil-square"></i> Update
                                </button>

                                <!-- Update Ticket Modal -->
                                <div class="modal fade text-start" id="updateModal_{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content glass-panel border-0 p-2 shadow-lg">
                                            <div class="modal-header border-bottom border-secondary border-opacity-25 pb-2">
                                                <h5 class="modal-title font-heading fw-bold">Update Ticket #CMP-{{ $ticket->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('maintenance.tickets.update', $ticket) }}">
                                                @csrf
                                                <div class="modal-body py-3">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Ticket Status</label>
                                                        <select name="status" class="form-select form-select-glass" required>
                                                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress (Working on it)</option>
                                                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved (Completed)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Completion / Repair Notes</label>
                                                        <textarea name="completion_notes" class="form-control form-control-glass" rows="3" placeholder="Describe repair work done..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top border-secondary border-opacity-25 pt-2">
                                                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary-custom">Save Status Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-success small fw-bold"><i class="bi bi-check-all me-1"></i> Closed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No work tickets assigned to you yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
