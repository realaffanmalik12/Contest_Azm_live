@extends('layouts.resident')

@section('title', 'Facility & Amenity Booking')
@section('page-title', 'Facility & Shared Amenity Booking')

@section('content')
<div class="row g-4">
    <!-- Booking Form -->
    <div class="col-lg-5">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="metric-icon-box" style="background: rgba(166, 124, 82, 0.15); color: var(--primary-brand); width: 44px; height: 44px;">
                    <i class="bi bi-calendar-plus fs-4"></i>
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">Book Shared Amenity</h5>
                    <span class="text-muted small">Reserve clubhouse, pool, or sports hall</span>
                </div>
            </div>

            <form method="POST" action="{{ route('resident.amenity-bookings.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Facility / Amenity *</label>
                    <select name="amenity_id" class="form-select form-select-glass" required>
                        <option value="">Choose Amenity</option>
                        @foreach($amenities as $amenity)
                            <option value="{{ $amenity->id }}">{{ $amenity->name }} (Cap: {{ $amenity->capacity ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Booking Date *</label>
                    <input type="date" name="booking_date" class="form-control form-control-glass" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Start Time *</label>
                        <input type="time" name="start_time" class="form-control form-control-glass" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">End Time *</label>
                        <input type="time" name="end_time" class="form-control form-control-glass" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 justify-content-center">
                    <i class="bi bi-check-circle"></i> Reserve Slot Now
                </button>
            </form>
        </div>
    </div>

    <!-- Booking History -->
    <div class="col-lg-7">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0">My Amenity Reservations</h5>
                <span class="badge badge-neutral-custom">Active Schedule</span>
            </div>

            <div class="table-responsive">
                <table class="table table-glass align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Amenity</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myBookings as $booking)
                            <tr>
                                <td><strong style="color: var(--dark-text);">{{ $booking->amenity->name ?? 'N/A' }}</strong></td>
                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }}</small></td>
                                <td><small>{{ $booking->start_time }} - {{ $booking->end_time }}</small></td>
                                <td>
                                    <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : 'neutral' }}-custom">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No amenity bookings made yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                @if($myBookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $myBookings->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
