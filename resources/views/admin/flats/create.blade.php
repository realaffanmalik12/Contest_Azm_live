@extends('layouts.admin')

@section('title', 'Add New Flat')
@section('page-title', 'Create Flat Record')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <h5 class="font-heading fw-bold mb-0">Flat Information Details</h5>
                <a href="{{ route('admin.flats.index') }}" class="btn btn-secondary-custom btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
            </div>

            <form method="POST" action="{{ route('admin.flats.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Block Name *</label>
                        <input type="text" name="block_name" class="form-control form-control-glass @error('block_name') is-invalid @enderror" placeholder="e.g. Block A" value="{{ old('block_name') }}" required>
                        @error('block_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Flat Number *</label>
                        <input type="text" name="flat_number" class="form-control form-control-glass @error('flat_number') is-invalid @enderror" placeholder="e.g. 101" value="{{ old('flat_number') }}" required>
                        @error('flat_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Floor</label>
                        <input type="number" name="floor" class="form-control form-control-glass" placeholder="e.g. 1" value="{{ old('floor') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Occupancy Type *</label>
                        <select name="occupancy_type" class="form-select form-select-glass" required>
                            <option value="Owner" {{ old('occupancy_type') == 'Owner' ? 'selected' : '' }}>Owner</option>
                            <option value="Tenant" {{ old('occupancy_type') == 'Tenant' ? 'selected' : '' }}>Tenant</option>
                            <option value="Vacant" {{ old('occupancy_type') == 'Vacant' ? 'selected' : '' }}>Vacant</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-uppercase" style="color: var(--secondary-text);">Status *</label>
                        <select name="status" class="form-select form-select-glass" required>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <a href="{{ route('admin.flats.index') }}" class="btn btn-secondary-custom">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i> Save Flat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
