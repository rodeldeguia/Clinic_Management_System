@extends('layouts.admin')

@section('title', 'Edit Doctor')
@section('header', 'Edit Doctor')
@section('subheader', 'Update doctor information')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.doctors.update', $doctor->user_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $doctor->firstname) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $doctor->lastname) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address', $doctor->email_address) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $doctor->contact_number) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Specialization *</label>
                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>License Number *</label>
                    <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $doctor->license_number) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Shift Timing</label>
                    <input type="text" name="shift_timing" class="form-control" value="{{ old('shift_timing', $doctor->shift_timing) }}" placeholder="e.g., 9:00 AM - 5:00 PM">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male" {{ $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', \Carbon\Carbon::parse($doctor->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $doctor->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$doctor->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $doctor->address) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Doctor</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection