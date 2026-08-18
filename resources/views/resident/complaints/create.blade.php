@extends('layouts.resident')

@section('title', 'Log Maintenance Complaint')
@section('page-title', 'Log Maintenance Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-tools me-2" style="color: var(--primary-brand);"></i> Submit Helpdesk Ticket</h5>
                <a href="{{ route('resident.complaints.index') }}" class="btn btn-secondary-custom btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>

            <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Fault Category *</label>
                    <select name="category" class="form-select form-select-glass" required>
                        <option value="Plumbing">Plumbing Fault</option>
                        <option value="Electrical">Electrical Issue</option>
                        <option value="Elevator">Elevator / Lift Fault</option>
                        <option value="Security">Security Concern</option>
                        <option value="General">General Maintenance</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Detailed Fault Description *</label>
                    <textarea name="description" class="form-control form-control-glass" rows="4" placeholder="Describe the issue clearly..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Upload Photo Evidence (Optional)</label>
                    <input type="file" name="photo" class="form-control form-control-glass" accept="image/*">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <a href="{{ route('resident.complaints.index') }}" class="btn btn-secondary-custom">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-send me-1"></i> Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
