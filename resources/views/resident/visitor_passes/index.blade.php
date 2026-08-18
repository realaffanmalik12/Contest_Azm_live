@extends('layouts.resident')

@section('title', 'Visitor Pre-Approval & Gate Passes')
@section('page-title', 'Visitor Gate Passes')

@section('header-actions')
    <a href="{{ route('resident.visitor-passes.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-qr-code-scan"></i> Generate New Gate Pass
    </a>
@endsection

@section('content')

<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-glass align-middle mb-0">
            <thead>
                <tr>
                    <th>Visitor Name</th>
                    <th>Phone</th>
                    <th>Vehicle Plate</th>
                    <th>Pass Code (Numeric / QR)</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td><strong style="color: var(--dark-text);">{{ $visitor->visitor_name }}</strong></td>
                        <td><small class="text-muted">{{ $visitor->phone ?? 'N/A' }}</small></td>
                        <td>{{ $visitor->vehicle_number ?? 'Walk-in' }}</td>
                        <td>
                            <span class="badge badge-neutral-custom font-monospace fs-6 px-3 py-1.5" style="letter-spacing: 2px;">
                                <i class="bi bi-key-fill me-1 text-warning"></i> {{ $visitor->gate_pass_code }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ \Carbon\Carbon::parse($visitor->valid_until)->format('Y-m-d H:i') }}</small></td>
                        <td>
                            @if(now()->greaterThan($visitor->valid_until))
                                <span class="badge badge-neutral-custom">Expired</span>
                            @else
                                <span class="badge badge-success-custom"><i class="bi bi-check-circle me-1"></i> Active / Approved</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('resident.visitor-passes.show', $visitor) }}" class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-qr-code me-1"></i> View QR & Pass
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No visitor gate passes generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        @if($visitors instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $visitors->links() }}
        @endif
    </div>
</div>
@endsection
