@extends('layouts.admin')

@section('title', 'Security Supervision Logs')
@section('page-title', 'Security Gate Entry & Exit Supervision Logs')

@section('content')

<!-- Filter Bar -->
<div class="glass-panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.gate-logs.index') }}" class="row g-3">
        <div class="col-md-6">
            <input type="date" name="date" class="form-control form-control-glass" value="{{ request('date') }}" placeholder="Filter by Entry Date">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-secondary-custom w-100 justify-content-center"><i class="bi bi-funnel"></i> Filter Supervision Logs</button>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Visitor Details</th>
                    <th>Destination Flat</th>
                    <th>Vehicle Plate</th>
                    <th>Gate Guard</th>
                    <th>Entry Timestamp</th>
                    <th>Exit Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">#LOG-{{ $log->id }}</span></td>
                        <td>
                            <div><strong style="color: var(--dark-text);">{{ $log->visitor->visitor_name ?? 'Walk-in Guest' }}</strong></div>
                            <small class="text-muted">{{ $log->visitor->phone ?? 'No phone' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info-custom">
                                {{ $log->flat->block_name ?? '' }} - {{ $log->flat->flat_number ?? '' }}
                            </span>
                        </td>
                        <td>{{ $log->visitor->vehicle_number ?? ($log->vehicle_number ?? 'None') }}</td>
                        <td><small class="text-muted"><i class="bi bi-shield-person me-1"></i> {{ $log->guard->name ?? 'System' }}</small></td>
                        <td><span class="text-success fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> {{ \Carbon\Carbon::parse($log->entry_time)->format('Y-m-d H:i:s') }}</span></td>
                        <td>
                            @if($log->exit_time)
                                <span class="text-danger"><i class="bi bi-box-arrow-right me-1"></i> {{ \Carbon\Carbon::parse($log->exit_time)->format('Y-m-d H:i:s') }}</span>
                            @else
                                <span class="badge badge-warning-custom"><i class="bi bi-hourglass-split me-1"></i> Inside Premises</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No security gate logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
