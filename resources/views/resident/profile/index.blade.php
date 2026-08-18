@extends('layouts.resident')

@section('title', 'Resident Profile & Vehicles')
@section('page-title', 'My Profile & Account Preferences')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <div class="avatar-circle" style="width: 46px; height: 46px; font-size: 1.1rem;">
                    {{ strtoupper(substr($user->name ?? 'R', 0, 1)) }}
                </div>
                <div>
                    <h5 class="font-heading fw-bold mb-0">{{ $user->name }}</h5>
                    <span class="text-muted small">Resident Profile & Emergency Details</span>
                </div>
            </div>

            <form method="POST" action="{{ route('resident.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Full Name</label>
                    <input type="text" class="form-control form-control-glass" value="{{ $user->name }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Assigned Flat Unit</label>
                    <input type="text" class="form-control form-control-glass fw-bold" style="color: var(--primary-brand);" value="{{ $profile->flat->block_name ?? 'N/A' }} - {{ $profile->flat->flat_number ?? 'N/A' }} ({{ $profile->flat->occupancy_type ?? 'N/A' }})" disabled>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-glass" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">CNIC / Govt ID</label>
                        <input type="text" name="cnic" class="form-control form-control-glass" value="{{ old('cnic', $profile->cnic ?? '') }}">
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact" class="form-control form-control-glass" value="{{ old('emergency_contact', $profile->emergency_contact ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Emergency Contact Phone</label>
                        <input type="text" name="emergency_phone" class="form-control form-control-glass" value="{{ old('emergency_phone', $profile->emergency_phone ?? '') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100 justify-content-center">
                    <i class="bi bi-save"></i> Save Profile Updates
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- Family Members -->
        <div class="glass-panel p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-people me-2" style="color: var(--primary-brand);"></i> Family / Household Members</h5>
                <span class="badge badge-neutral-custom">Registered</span>
            </div>

            <form method="POST" action="{{ route('resident.family.store') }}" class="row g-2 mb-3">
                @csrf
                <div class="col-5">
                    <input type="text" name="name" class="form-control form-control-glass form-control-sm" placeholder="Member Name *" required>
                </div>
                <div class="col-4">
                    <input type="text" name="relationship" class="form-control form-control-glass form-control-sm" placeholder="Relation (Spouse/Child)" required>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-sm btn-primary-custom w-100 justify-content-center"><i class="bi bi-plus-lg"></i> Add</button>
                </div>
            </form>

            <ul class="list-group list-group-flush">
                @forelse($profile->familyMembers ?? [] as $family)
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 border-bottom border-secondary border-opacity-10">
                        <div>
                            <strong style="color: var(--dark-text);">{{ $family->name }}</strong> 
                            <small class="text-muted">({{ $family->relationship }})</small>
                        </div>
                        <form method="POST" action="{{ route('resident.family.destroy', $family) }}" onsubmit="return confirm('Remove family member?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm text-danger p-0 border-0"><i class="bi bi-trash fs-6"></i></button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item bg-transparent text-muted text-center py-2 px-0 border-0 small">No family members registered.</li>
                @endforelse
            </ul>
        </div>

        <!-- Registered Vehicles -->
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-car-front me-2" style="color: var(--primary-brand);"></i> Registered Vehicles</h5>
                <span class="badge badge-neutral-custom">Gate Stickers</span>
            </div>

            <form method="POST" action="{{ route('resident.vehicles.store') }}" class="row g-2 mb-3">
                @csrf
                <div class="col-5">
                    <input type="text" name="vehicle_number" class="form-control form-control-glass form-control-sm" placeholder="Plate # (e.g. ABC-123)" required>
                </div>
                <div class="col-4">
                    <select name="vehicle_type" class="form-select form-select-glass form-select-sm" required>
                        <option value="Car">Car</option>
                        <option value="Bike">Bike</option>
                        <option value="SUV">SUV</option>
                    </select>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-sm btn-primary-custom w-100 justify-content-center"><i class="bi bi-plus-lg"></i> Add</button>
                </div>
            </form>

            <ul class="list-group list-group-flush">
                @forelse($profile->vehicles ?? [] as $vehicle)
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 border-bottom border-secondary border-opacity-10">
                        <div>
                            <span class="badge badge-neutral-custom me-2">{{ $vehicle->vehicle_type }}</span>
                            <strong style="color: var(--dark-text);">{{ $vehicle->vehicle_number }}</strong>
                        </div>
                        <form method="POST" action="{{ route('resident.vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Remove vehicle?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm text-danger p-0 border-0"><i class="bi bi-trash fs-6"></i></button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item bg-transparent text-muted text-center py-2 px-0 border-0 small">No vehicles registered.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
