@extends('layouts.receptionist')

@section('title', 'Register Patient')
@section('header', 'Patient Registration')
@section('subheader', 'Register a new patient in the system')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('receptionist.patients.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender *</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Blood Group</label>
                    <select name="blood_group" class="form-control">
                        <option value="">Select</option>
                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}">
                </div>
            </div>
            <div class="mb-3">
                <label>Address *</label>
                <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Username will be auto-generated as: firstname.lastname + random numbers<br>
                <i class="fas fa-info-circle"></i> Default password will be set to 'password'. Patient can change it after first login.
            </div>
            
            <button type="submit" class="btn btn-primary">Register Patient</button>
            <a href="{{ route('receptionist.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection