@extends('layouts.admin')

@section('title', 'Onboard Resident')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Onboard Resident to {{ $flat->block_name }} - {{ $flat->flat_number }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.residents.store', $flat) }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="John Doe">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="john@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="johndoe">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 6 characters">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CNIC / ID Number</label>
                            <input type="text" name="cnic" class="form-control" value="{{ old('cnic') }}" placeholder="12345-6789012-3">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Person</label>
                            <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}" placeholder="Contact Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Phone</label>
                            <input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone') }}" placeholder="Emergency Contact Number">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.flats.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="bi bi-person-check me-1"></i> Onboard Resident</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
