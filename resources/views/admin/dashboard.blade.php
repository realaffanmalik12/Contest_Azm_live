@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administrator Dashboard Overview')

@section('header-actions')
  <a href="{{ route('admin.emergency-alerts.index') }}" class="btn btn-emergency">
    <i class="bi bi-megaphone-fill"></i> Broadcast Alert
  </a>
@endsection

@section('content')

<!-- Metric Cards Grid -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Flats</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--primary-brand);">{{ \App\Models\Flat::count() }}</h2>
                    <span class="badge badge-neutral-custom"><i class="bi bi-building me-1"></i> Housing Infrastructure</span>
                </div>
                <div class="metric-icon-box">
                    <i class="bi bi-houses-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Active Residents</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--primary-brand);">
                        {{ \App\Models\User::where('role', 'resident')->where('status', 'active')->count() }}
                    </h2>
                    <span class="badge badge-success-custom"><i class="bi bi-check-circle me-1"></i> Verified Active</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Pending Helpdesk</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--warning-color);">
                        {{ \App\Models\Complaint::where('status', 'pending')->count() }}
                    </h2>
                    <span class="badge badge-warning-custom"><i class="bi bi-clock me-1"></i> Action Required</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(211, 84, 0, 0.15); color: #B94A00;">
                    <i class="bi bi-headset"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Active Emergency Alerts</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--danger-color);">
                        {{ \App\Models\EmergencyAlert::where('status', 'active')->count() }}
                    </h2>
                    <span class="badge badge-danger-custom"><i class="bi bi-exclamation-triangle me-1"></i> System Alerts</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(192, 57, 43, 0.15); color: var(--danger-color);">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions Panel -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Quick Management Actions</h5>
            </div>
            <p class="text-muted small mb-4">Direct access to core society admin workflows and operations.</p>

            <div class="d-grid gap-3 d-sm-flex flex-wrap">
                <a href="{{ route('admin.flats.create') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle"></i> Add New Flat
                </a>
                <a href="{{ route('admin.bills.create') }}" class="btn btn-secondary-custom">
                    <i class="bi bi-receipt"></i> Generate Bills
                </a>
                <a href="{{ route('admin.emergency-alerts.index') }}" class="btn btn-emergency">
                    <i class="bi bi-megaphone"></i> Issue Alert
                </a>
            </div>
        </div>
    </div>

    <!-- Audit Trail Activity -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-journal-text me-2" style="color: var(--primary-brand);"></i> System Audit Trail</h5>
                <span class="badge badge-neutral-custom">Latest Activity</span>
            </div>

            <div class="table-responsive">
                <table class="table table-glass align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Performed By</th>
                            <th class="text-end">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->take(5)->get() as $log)
                            <tr>
                                <td><span class="fw-bold" style="color: var(--primary-brand);">{{ $log->action }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="width: 26px; height: 26px; font-size: 0.7rem;">
                                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="small fw-semibold">{{ $log->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="text-end text-muted small">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No audit logs recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
