@extends('layouts.resident')

@section('title', 'Invoice Details')
@section('page-title', 'Invoice #' . $bill->bill_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <h4 class="font-heading fw-bold mb-0">Invoice Breakdown</h4>
                    <span class="text-muted small">Billing Month: {{ \Carbon\Carbon::parse($bill->billing_month)->format('F Y') }}</span>
                </div>
                <span class="badge badge-{{ $bill->payment_status == 'paid' ? 'success' : ($bill->payment_status == 'overdue' ? 'danger' : 'warning') }}-custom fs-6">
                    {{ strtoupper($bill->payment_status) }}
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Issued To Flat</span>
                    <h6 class="fw-bold my-1" style="color: var(--primary-brand);">{{ $bill->flat->block_name }} - {{ $bill->flat->flat_number }}</h6>
                </div>
                <div class="col-6 text-end">
                    <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Payment Due Date</span>
                    <h6 class="fw-bold my-1 text-danger">{{ \Carbon\Carbon::parse($bill->due_date)->format('Y-m-d') }}</h6>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-glass align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Charge Head Description</th>
                            <th class="text-end">Amount ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Water Supply & Utility Charges</td>
                            <td class="text-end">${{ number_format($bill->water_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Security Guard Personnel Fee</td>
                            <td class="text-end">${{ number_format($bill->security_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Infrastructure & Repair Fund</td>
                            <td class="text-end">${{ number_format($bill->repair_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Amenity / General Maintenance</td>
                            <td class="text-end">${{ number_format($bill->other_charges, 2) }}</td>
                        </tr>
                        @if($bill->penalty > 0)
                            <tr style="background: rgba(192, 57, 43, 0.1);">
                                <td class="text-danger fw-bold">Overdue Late Payment Penalty</td>
                                <td class="text-end text-danger fw-bold">+${{ number_format($bill->penalty, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="fw-bold fs-5">
                            <td>Total Payable Amount</td>
                            <td class="text-end" style="color: var(--primary-brand);">${{ number_format($bill->amount_due, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($bill->payment_status !== 'paid')
                <div class="p-4 rounded-3 mb-4" style="background: rgba(166, 124, 82, 0.1); border: 1px solid var(--borders-dividers);">
                    <h6 class="fw-bold font-heading mb-2" style="color: var(--primary-brand);"><i class="bi bi-wallet2 me-2"></i> Digital Fee Payment Checkout</h6>
                    <p class="text-muted small mb-3">Select your preferred payment channel to settle outstanding dues.</p>
                    
                    <form method="POST" action="{{ route('resident.bills.pay', $bill) }}" class="row g-2 align-items-center">
                        @csrf
                        <div class="col-md-7">
                            <select name="payment_method" class="form-select form-select-glass" required>
                                <option value="Card">Credit / Debit Card (Simulated Gateway)</option>
                                <option value="Bank Transfer">Direct Bank Transfer</option>
                                <option value="UPI">Mobile Wallet / UPI</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary-custom w-100 justify-content-center" onclick="return confirm('Simulate digital fee payment of ${{ number_format($bill->amount_due, 2) }}?')">
                                <i class="bi bi-shield-check"></i> Pay ${{ number_format($bill->amount_due, 2) }} Now
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-3 rounded-3 mb-4 d-flex align-items-center gap-3" style="background: rgba(39, 174, 96, 0.15);">
                    <div class="metric-icon-box" style="background: #1E8449; color: #fff; width: 40px; height: 40px; border-radius: 50%;">
                        <i class="bi bi-check-lg fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-success">Payment Verified & Completed</h6>
                        <small class="text-muted">Transaction receipt generated & payment status cleared.</small>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end pt-3 border-top border-secondary border-opacity-25">
                <a href="{{ route('resident.bills.index') }}" class="btn btn-secondary-custom"><i class="bi bi-arrow-left me-1"></i> Back to Bills</a>
            </div>
        </div>
    </div>
</div>
@endsection
