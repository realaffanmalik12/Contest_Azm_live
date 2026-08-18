@extends('layouts.admin')

@section('title', 'Emergency Broadcast')
@section('page-title', 'Emergency Broadcast System')

@section('content')

<div class="row g-4">
    <!-- Issue Alert Form -->
    <div class="col-lg-5">
        <div class="glass-panel p-4" style="border-color: var(--danger-color); background: rgba(192, 57, 43, 0.05);">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(192, 57, 43, 0.15); color: var(--danger-color); width: 44px; height: 44px;">
                    <i class="bi bi-broadcast fs-4"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold text-danger mb-0">Issue Emergency Alert</h5>
                    <span class="text-muted small">Broadcast urgent alerts to resident portals</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.emergency-alerts.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Alert Title / Headline *</label>
                    <input type="text" name="title" class="form-control form-control-glass" placeholder="e.g. Urgent: Main Gate Maintenance" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Alert Type *</label>
                        <input type="text" name="alert_type" class="form-control form-control-glass" placeholder="e.g. Security / Utility" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Severity Level *</label>
                        <select name="severity" class="form-select form-select-glass" required>
                            <option value="info">Info (General Notice)</option>
                            <option value="warning" selected>Warning (Moderate)</option>
                            <option value="critical">Critical (Emergency)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Alert Message / Instructions *</label>
                    <textarea name="description" class="form-control form-control-glass" rows="4" placeholder="Provide detailed instructions for residents..." required></textarea>
                </div>

                <button type="submit" class="btn btn-emergency w-100 justify-content-center py-2.5" onclick="return confirm('Broadcast this emergency alert to all residents?')">
                    <i class="bi bi-megaphone-fill"></i> Broadcast Emergency Alert Now
                </button>
            </form>
        </div>
    </div>

    <!-- Alert History Table -->
    <div class="col-lg-7">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0">Broadcast History Log</h5>
                <span class="badge badge-neutral-custom">System Records</span>
            </div>

            <div class="table-responsive">
                <table class="table table-glass align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title & Type</th>
                            <th>Severity</th>
                            <th>Description</th>
                            <th>Issued</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alerts as $alert)
                            <tr>
                                <td>
                                    <strong style="color: var(--dark-text);">{{ $alert->title }}</strong>
                                    <span class="d-block text-muted small"><i class="bi bi-tag me-1"></i>{{ $alert->alert_type }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $alert->severity == 'critical' ? 'danger' : ($alert->severity == 'warning' ? 'warning' : 'info') }}-custom">
                                        {{ strtoupper($alert->severity) }}
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ Str::limit($alert->description, 50) }}</small></td>
                                <td><small class="text-muted">{{ $alert->created_at->diffForHumans() }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No emergency alerts broadcasted.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $alerts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
