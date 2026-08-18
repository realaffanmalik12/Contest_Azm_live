@extends('layouts.resident')

@section('title', 'Generate Gate Pass')
@section('page-title', 'Generate Digital Visitor Gate Pass')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-qr-code me-2" style="color: var(--primary-brand);"></i> Pre-Authorize Visitor Access</h5>
                <a href="{{ route('resident.visitor-passes.index') }}" class="btn btn-secondary-custom btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>

            <form method="POST" action="{{ route('resident.visitor-passes.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Visitor Full Name *</label>
                    <input type="text" name="visitor_name" class="form-control form-control-glass" placeholder="Guest / Cab Driver / Delivery Person" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Visitor Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-glass" placeholder="+123456789">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Vehicle Plate Number</label>
                        <input type="text" name="vehicle_number" class="form-control form-control-glass" placeholder="e.g. ABC-1234 (Optional)">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Visitor Type</label>
                        <select name="visitor_type" class="form-control form-control-glass">
                            <option value="guest">🧑 Guest / Personal Visitor</option>
                            <option value="delivery">📦 Delivery / Parcel</option>
                            <option value="cab">🚕 Cab / Ride Share</option>
                            <option value="vendor">🔧 Vendor / Repair Worker</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Pass Valid Until *</label>
                        <input type="datetime-local" name="valid_until" class="form-control form-control-glass" value="{{ date('Y-m-d\TH:i', strtotime('+12 hours')) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <a href="{{ route('resident.visitor-passes.index') }}" class="btn btn-secondary-custom">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-key me-1"></i> Generate Gate Pass</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
