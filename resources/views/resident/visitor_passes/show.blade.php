@extends('layouts.resident')

@section('title', 'Digital Visitor Gate Pass Card')
@section('page-title', 'Digital Gate Pass #' . $visitor->gate_pass_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        
        <!-- Digital Gate Pass Card -->
        <div class="glass-panel p-4 text-center position-relative overflow-hidden" style="border: 2px solid var(--accent-brown); background: rgba(255, 248, 240, 0.9);">
            
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-logo-icon" style="width: 36px; height: 36px; font-size: 1rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <span class="fw-bold font-heading" style="color: var(--primary-brand);">SmartSociety Security Gate</span>
                </div>
                <span class="badge badge-{{ now()->greaterThan($visitor->valid_until) ? 'neutral' : 'success' }}-custom fs-6">
                    {{ now()->greaterThan($visitor->valid_until) ? 'EXPIRED' : 'AUTHORIZED ENTRY' }}
                </span>
            </div>

            <!-- Pass Code Header -->
            <div class="my-3">
                <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 1px;">Numeric 6-Digit Gate Code</span>
                <div class="d-flex justify-content-center align-items-center gap-2 my-2">
                    <div class="glass-panel px-4 py-2" style="background: var(--primary-brand); color: #fff;">
                        <h2 class="font-monospace fw-bold mb-0" style="letter-spacing: 6px; font-size: 2.2rem;">
                            {{ $visitor->gate_pass_code }}
                        </h2>
                    </div>
                </div>
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Show this code or QR scanner at the security guard booth</small>
            </div>

            <!-- Dynamic QR Code Container -->
            <div class="my-4 p-3 d-inline-block rounded-3 glass-panel" style="background: #fff; border: 1px solid var(--borders-dividers);">
                <img 
                    src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($visitor->qr_token) }}&color=6B4C3B&bgcolor=FFF8F0" 
                    alt="Gate Pass QR Code" 
                    class="img-fluid rounded" 
                    style="width: 200px; height: 200px;"
                    onerror="this.onerror=null; this.src='https://chart.googleapis.com/chart?cht=qr&chs=220x220&chl={{ urlencode($visitor->qr_token) }}';"
                >
                <span class="d-block text-muted small mt-2 fw-semibold"><i class="bi bi-qr-code-scan me-1"></i> Scan for Instant Gate Entry</span>
            </div>

            <!-- Visitor & Destination Details -->
            <div class="text-start p-3 rounded-3 mb-4" style="background: rgba(223, 211, 196, 0.3); border: 1px solid var(--borders-dividers);">
                <div class="row g-2">
                    <div class="col-6">
                        <span class="text-muted small d-block">Visitor Full Name</span>
                        <strong style="color: var(--dark-text); font-size: 1rem;">{{ $visitor->visitor_name }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted small d-block">Phone Number</span>
                        <strong style="color: var(--dark-text);">{{ $visitor->phone ?? 'Not provided' }}</strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted small d-block">Vehicle Plate</span>
                        <strong style="color: var(--primary-brand);">{{ $visitor->vehicle_number ?? 'Walk-in Guest' }}</strong>
                    </div>
                    <div class="col-6 text-end mt-2">
                        <span class="text-muted small d-block">Purpose of Visit</span>
                        <strong style="color: var(--dark-text);">{{ $visitor->purpose ?? 'General' }}</strong>
                    </div>
                    <div class="col-12 mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Valid Until:</span>
                        <strong class="text-danger small">{{ \Carbon\Carbon::parse($visitor->valid_until)->format('Y-m-d h:i A') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <button type="button" onclick="window.print()" class="btn btn-primary-custom">
                    <i class="bi bi-printer me-1"></i> Print / Save Pass
                </button>
                <a href="{{ route('resident.visitor-passes.create') }}" class="btn btn-secondary-custom">
                    <i class="bi bi-plus-lg me-1"></i> New Pass
                </a>
                <a href="{{ route('resident.visitor-passes.index') }}" class="btn btn-secondary-custom">
                    <i class="bi bi-list-ul me-1"></i> All Passes
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
