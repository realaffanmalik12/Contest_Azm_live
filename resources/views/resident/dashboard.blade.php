@extends('layouts.resident')

@section('title', 'Resident Dashboard')
@section('page-title', 'Welcome back, ' . auth()->user()->name)

@section('header-actions')
  <a href="{{ route('resident.visitor-passes.create') }}" class="btn btn-primary-custom">
    <i class="bi bi-qr-code-scan"></i> New Gate Pass
  </a>
@endsection

@section('content')

<!-- Metric Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Assigned Flat</span>
                    <h3 class="font-heading fw-bold my-1" style="color: var(--primary-brand);">
                        @if(auth()->user()->residentProfile && auth()->user()->residentProfile->flat)
                            {{ auth()->user()->residentProfile->flat->block_name }} - {{ auth()->user()->residentProfile->flat->flat_number }}
                        @else
                            No Flat Linked
                        @endif
                    </h3>
                    <span class="badge badge-neutral-custom"><i class="bi bi-geo-alt me-1"></i> Residence Unit</span>
                </div>
                <div class="metric-icon-box">
                    <i class="bi bi-house-door-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Active Gate Passes</span>
                    <h3 class="font-heading fw-bold my-1" style="color: #1E8449;">
                        @if(auth()->user()->residentProfile)
                            {{ \App\Models\Visitor::where('resident_id', auth()->user()->residentProfile->id)->where('valid_until', '>=', now())->count() }}
                        @else
                            0
                        @endif
                    </h3>
                    <span class="badge badge-success-custom"><i class="bi bi-shield-check me-1"></i> Authorized Visitors</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449;">
                    <i class="bi bi-qr-code"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Pending Dues</span>
                    <h3 class="font-heading fw-bold my-1" style="color: var(--warning-color);">
                        @if(auth()->user()->residentProfile)
                            {{ \App\Models\MaintenanceBill::where('flat_id', auth()->user()->residentProfile->flat_id)->whereIn('payment_status', ['unpaid', 'overdue'])->count() }}
                        @else
                            0
                        @endif
                    </h3>
                    <span class="badge badge-warning-custom"><i class="bi bi-clock-history me-1"></i> Due Maintenance</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(211, 84, 0, 0.15); color: #B94A00;">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Widgets Grid -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449; width: 42px; height: 42px; font-size: 1.2rem;">
                    <i class="bi bi-qr-code-scan"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">Visitor Gate Pass</h5>
                    <span class="text-muted small">Instant visitor pre-authorization</span>
                </div>
            </div>
            <p class="text-muted small mb-4">Generate digital entry passes with OTP/QR validation for your guests, delivery drivers, or household help.</p>
            <a href="{{ route('resident.visitor-passes.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Pre-Authorize Guest
            </a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(41, 128, 185, 0.15); color: #1F618D; width: 42px; height: 42px; font-size: 1.2rem;">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">Helpdesk & Maintenance</h5>
                    <span class="text-muted small">Report & track complaints</span>
                </div>
            </div>
            <p class="text-muted small mb-4">Experiencing plumbing, electrical, or elevator faults? Submit a ticket to notify the maintenance staff immediately.</p>
            <a href="{{ route('resident.complaints.create') }}" class="btn btn-secondary-custom">
                <i class="bi bi-headset"></i> Raise Complaint Ticket
            </a>
        </div>
    </div>
</div>

@endsection
