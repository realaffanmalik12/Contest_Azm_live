@extends('layouts.admin')

@section('title', 'Maintenance Billing Engine')
@section('page-title', 'Maintenance & Financial Billing Engine')

@section('header-actions')
    <a href="{{ route('admin.bills.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-file-earmark-plus"></i> Bulk Generate Bills
    </a>
@endsection

@section('content')

<!-- Revenue Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Revenue Collected</span>
                    <h2 class="font-heading fw-bold my-1" style="color: #1E8449;">${{ number_format($totalCollected, 2) }}</h2>
                    <span class="badge badge-success-custom"><i class="bi bi-check-circle me-1"></i> Paid Collections</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(39, 174, 96, 0.15); color: #1E8449;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="glass-panel metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Outstanding Dues</span>
                    <h2 class="font-heading fw-bold my-1" style="color: var(--warning-color);">${{ number_format($totalOutstanding, 2) }}</h2>
                    <span class="badge badge-warning-custom"><i class="bi bi-exclamation-circle me-1"></i> Pending Receivable</span>
                </div>
                <div class="metric-icon-box" style="background: rgba(211, 84, 0, 0.15); color: #B94A00;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="glass-panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.bills.index') }}" class="row g-3">
        <div class="col-md-4">
            <input type="month" name="billing_month" class="form-control form-control-glass" value="{{ request('billing_month') }}">
        </div>
        <div class="col-md-4">
            <select name="payment_status" class="form-select form-select-glass">
                <option value="">All Payment Statuses</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-secondary-custom w-100 justify-content-center"><i class="bi bi-funnel"></i> Filter Report</button>
        </div>
    </form>
</div>

<!-- Bills Table -->
<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Bill #</th>
                    <th>Flat</th>
                    <th>Month</th>
                    <th>Charges Breakdown</th>
                    <th>Penalty</th>
                    <th>Total Due</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">{{ $bill->bill_number }}</span></td>
                        <td><strong style="color: var(--dark-text);">{{ $bill->flat->block_name }} - {{ $bill->flat->flat_number }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($bill->billing_month)->format('M Y') }}</td>
                        <td>
                            <small class="d-block text-muted">Water: ${{ number_format($bill->water_charges, 2) }}</small>
                            <small class="d-block text-muted">Security: ${{ number_format($bill->security_charges, 2) }}</small>
                            <small class="d-block text-muted">Repairs: ${{ number_format($bill->repair_charges, 2) }}</small>
                        </td>
                        <td>
                            @if($bill->penalty > 0)
                                <span class="text-danger fw-bold">+${{ number_format($bill->penalty, 2) }}</span>
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td><strong class="fs-6" style="color: var(--dark-text);">${{ number_format($bill->amount_due, 2) }}</strong></td>
                        <td><small class="text-muted">{{ \Carbon\Carbon::parse($bill->due_date)->format('Y-m-d') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $bill->payment_status == 'paid' ? 'success' : ($bill->payment_status == 'overdue' ? 'danger' : 'warning') }}-custom">
                                {{ ucfirst($bill->payment_status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No maintenance bills generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $bills->links() }}
    </div>
</div>
@endsection
