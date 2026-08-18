@extends('layouts.admin')

@section('title', 'Bulk Generate Bills')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Bulk Generate Monthly Bills</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bills.generate') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Billing Month</label>
                            <input type="date" name="billing_month" class="form-control" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Due Date</label>
                            <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-25') }}">
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3 text-primary">Itemized Charges Per Flat</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Water Charges ($)</label>
                            <input type="number" step="0.01" name="water_charges" class="form-control" value="50.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Security Charges ($)</label>
                            <input type="number" step="0.01" name="security_charges" class="form-control" value="100.00" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Repair & Maintenance ($)</label>
                            <input type="number" step="0.01" name="repair_charges" class="form-control" value="75.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Other / Amenities Charges ($)</label>
                            <input type="number" step="0.01" name="other_charges" class="form-control" value="0.00">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i> This action will create invoice records for all currently <strong>occupied</strong> flats. Overdue penalties ($25.00) apply automatically after the due date.
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" onsubmit="return confirm('Generate bills for all occupied flats?')">
                            <i class="bi bi-lightning-charge me-1"></i> Generate & Issue Bills
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
