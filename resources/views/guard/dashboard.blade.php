@extends('layouts.guard')

@section('title', 'Security Checkpoint Station')

@section('content')

<div class="row g-4 mb-4">
    <!-- Fast Pass Verification Section -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(166, 124, 82, 0.2); color: var(--primary-brand); width: 44px; height: 44px;">
                    <i class="bi bi-qr-code-scan fs-4"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">Fast Pass Clearance Scanner</h5>
                    <span class="text-muted small">Scan QR or enter 6-digit numeric pass code</span>
                </div>
            </div>

            <form method="POST" action="{{ route('guard.pass.verify') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-warning fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Gate Pass Code</label>
                    <div class="input-group">
                        <input type="text" name="gate_pass_code" class="form-control form-control-glass font-monospace fw-bold text-center fs-2" placeholder="123456" autofocus required style="letter-spacing: 4px;">
                        <button type="submit" class="btn btn-primary-custom px-4"><i class="bi bi-shield-check"></i> Verify Pass</button>
                    </div>
                    <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Real-time automated verification & clearance logging.</small>
                </div>
            </form>
        </div>
    </div>

    <!-- Walk-in Guest Registration -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449; width: 44px; height: 44px;">
                    <i class="bi bi-person-plus-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">Log Walk-in Visitor / Vendor</h5>
                    <span class="text-muted small">Manual gate entry registration</span>
                </div>
            </div>

            <form method="POST" action="{{ route('guard.walkin.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <input type="text" name="visitor_name" class="form-control form-control-glass" placeholder="Visitor Name *" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="phone" class="form-control form-control-glass" placeholder="Phone Number">
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <select name="flat_id" class="form-select form-select-glass" required>
                            <option value="">Destination Flat *</option>
                            @foreach($flats as $flat)
                                <option value="{{ $flat->id }}">{{ $flat->block_name }} - {{ $flat->flat_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="vehicle_number" class="form-control form-control-glass" placeholder="Vehicle Plate # (e.g. ABC-123)">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" name="purpose" class="form-control form-control-glass" placeholder="Purpose (Guest / Delivery)">
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="photo" class="form-control form-control-glass" accept="image/*">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100 justify-content-center">
                    <i class="bi bi-box-arrow-in-right"></i> Authorize & Log Entry
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Overstay Alerts Banner -->
@if($overstayLogs->isNotEmpty())
    <div class="glass-panel p-4 mb-4" style="border-color: var(--danger-color); background: rgba(192, 57, 43, 0.08);">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="font-heading fw-bold text-danger mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> OVERSTAY ALERT: {{ $overstayLogs->count() }} Visitor(s) Inside Premises > 4 Hours
            </h5>
            <span class="badge badge-danger-custom">Action Required</span>
        </div>

        <div class="table-responsive">
            <table class="table table-glass align-middle mb-0">
                <thead>
                    <tr>
                        <th>Visitor Name</th>
                        <th>Target Flat</th>
                        <th>Vehicle #</th>
                        <th>Entry Time</th>
                        <th>Duration Inside</th>
                        <th class="text-end">Checkout</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overstayLogs as $overstay)
                        <tr>
                            <td><strong class="text-danger">{{ $overstay->visitor->visitor_name ?? 'Guest' }}</strong></td>
                            <td><span class="badge badge-info-custom">{{ $overstay->flat->block_name ?? '' }}-{{ $overstay->flat->flat_number ?? '' }}</span></td>
                            <td>{{ $overstay->vehicle_number ?? 'Walk-in' }}</td>
                            <td>{{ \Carbon\Carbon::parse($overstay->entry_time)->format('H:i:s') }}</td>
                            <td><span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($overstay->entry_time)->diffForHumans(null, true) }}</span></td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('guard.checkout', $overstay) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary-custom">Record Exit</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Live Gate Access Activity Stream -->
<div class="glass-panel p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="font-heading fw-bold mb-0"><i class="bi bi-journal-text me-2" style="color: var(--primary-brand);"></i> Live Gate Activity Stream</h5>
            <span class="text-muted small">Real-time gate clearance history & status</span>
        </div>
        <span class="badge badge-success-custom fs-6"><i class="bi bi-people-fill me-1"></i> Active Inside: {{ $activeEntriesCount }}</span>
    </div>

    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Visitor Details</th>
                    <th>Destination</th>
                    <th>Vehicle #</th>
                    <th>Duty Guard</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">#LOG-{{ $log->id }}</span></td>
                        <td>
                            <div><strong style="color: var(--dark-text);">{{ $log->visitor->visitor_name ?? 'Walk-in Guest' }}</strong></div>
                            <small class="text-muted">{{ $log->visitor->phone ?? 'No contact' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-neutral-custom fw-semibold">
                                {{ $log->flat->block_name ?? '' }} - {{ $log->flat->flat_number ?? '' }}
                            </span>
                        </td>
                        <td>{{ $log->visitor->vehicle_number ?? ($log->vehicle_number ?? 'Walk-in') }}</td>
                        <td><small class="text-muted"><i class="bi bi-shield-person me-1"></i> {{ $log->guard->name ?? 'Guard' }}</small></td>
                        <td><span class="text-success fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> {{ \Carbon\Carbon::parse($log->entry_time)->format('H:i:s') }}</span></td>
                        <td>
                            @if($log->exit_time)
                                <span class="text-danger"><i class="bi bi-box-arrow-right me-1"></i> {{ \Carbon\Carbon::parse($log->exit_time)->format('H:i:s') }}</span>
                            @else
                                <span class="badge badge-warning-custom"><i class="bi bi-hourglass-split me-1"></i> INSIDE</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(!$log->exit_time)
                                <form method="POST" action="{{ route('guard.checkout', $log) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary-custom text-danger" title="Record Exit"><i class="bi bi-box-arrow-right me-1"></i> Checkout</button>
                                </form>
                            @else
                                <span class="text-muted small">Checked Out</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No gate activity logs recorded today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
