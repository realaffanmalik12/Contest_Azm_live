@extends('layouts.resident')

@section('title', 'Helpdesk Complaints')
@section('page-title', 'Helpdesk Complaints & Maintenance')

@section('header-actions')
    <a href="{{ route('resident.complaints.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Log Complaint Ticket
    </a>
@endsection

@section('content')

<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Logged Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">#CMP-{{ $complaint->id }}</span></td>
                        <td><span class="badge badge-neutral-custom">{{ $complaint->category }}</span></td>
                        <td>
                            <p class="mb-0 text-muted small" style="max-width: 380px;">{{ $complaint->description }}</p>
                            @if($complaint->photo_url)
                                <small class="d-block mt-1"><a href="{{ asset('storage/' . $complaint->photo_url) }}" target="_blank" class="text-decoration-none" style="color: var(--accent-brown);"><i class="bi bi-image me-1"></i> View Photo Attachment</a></small>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $complaint->created_at->format('Y-m-d H:i') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'in_progress' ? 'info' : 'warning') }}-custom">
                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No complaint tickets logged yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        @if($complaints instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $complaints->links() }}
        @endif
    </div>
</div>
@endsection
