@extends('layouts.patient')

@section('title', 'Edit Profile')
@section('header', 'Edit Profile')
@section('subheader', 'Update your personal information')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('patient.account.update') }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input type="text" class="form-control" value="{{ $patient->firstname }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input type="text" class="form-control" value="{{ $patient->lastname }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $patient->contact_number) }}" required>
                    @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address', $patient->email_address) }}">
                    @error('email_address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $patient->emergency_contact) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Blood Group</label>
                    <select name="blood_group" class="form-control">
                        <option value="">Select</option>
                        <option value="A+" {{ $patient->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ $patient->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ $patient->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ $patient->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="O+" {{ $patient->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ $patient->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                        <option value="AB+" {{ $patient->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ $patient->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $patient->address) }}</textarea>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Username and Name cannot be changed. Contact reception for any corrections.
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="{{ route('patient.account.profile') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <i class="fas fa-camera"></i> Update Profile Photo
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('patient.account.upload-photo') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <input type="file" name="photo" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Upload Photo</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection