@extends('layouts.resident')

@section('title', 'Maintenance Bills & Invoices')
@section('page-title', 'Maintenance Invoices & Dues')

@section('content')

<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Bill #</th>
                    <th>Billing Month</th>
                    <th>Amount Due</th>
                    <th>Penalty</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                    <tr>
                        <td><span class="fw-bold" style="color: var(--primary-brand);">{{ $bill->bill_number }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($bill->billing_month)->format('M Y') }}</td>
                        <td><strong class="fs-6" style="color: var(--dark-text);">${{ number_format($bill->amount_due, 2) }}</strong></td>
                        <td>
                            @if($bill->penalty > 0)
                                <span class="text-danger fw-bold">+${{ number_format($bill->penalty, 2) }}</span>
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ \Carbon\Carbon::parse($bill->due_date)->format('Y-m-d') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $bill->payment_status == 'paid' ? 'success' : ($bill->payment_status == 'overdue' ? 'danger' : 'warning') }}-custom">
                                {{ ucfirst($bill->payment_status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-eye"></i> View & Pay
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No maintenance bills issued for your flat yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        @if($bills instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $bills->links() }}
        @endif
    </div>
</div>
@endsection
