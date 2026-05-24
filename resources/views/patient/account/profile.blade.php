@extends('layouts.patient')

@section('title', 'My Profile')
@section('header', 'My Profile')
@section('subheader', 'View and manage your personal information')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                @if($patient->profile_photo)
                    <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="rounded-circle mb-3" width="150" height="150">
                @else
                    <i class="fas fa-user-circle fa-5x mb-3 text-secondary"></i>
                @endif
                <h5>{{ $patient->firstname }} {{ $patient->lastname }}</h5>
                <p class="text-muted">Patient ID: {{ $patient->user_id }}</p>
                <hr>
                <a href="{{ route('patient.account.edit') }}" class="btn btn-primary w-100">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <table class="table">
                    <tr><th>Full Name</th><td>{{ $patient->firstname }} {{ $patient->lastname }}</td></tr>
                    <tr><th>Username</th><td>{{ $patient->username }}</td></tr>
                    <tr><th>Email</th><td>{{ $patient->email_address ?? 'Not provided' }}</td></tr>
                    <tr><th>Contact Number</th><td>{{ $patient->contact_number }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $patient->date_of_birth }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($patient->gender) }}</td></tr>
                    <tr><th>Blood Group</th><td>{{ $patient->blood_group ?? 'Not specified' }}</td></tr>
                    <tr><th>Emergency Contact</th><td>{{ $patient->emergency_contact ?? 'Not specified' }}</td></tr>
                    <tr><th>Address</th><td>{{ $patient->address }}</td></tr>
                    <tr><th>Registered On</th><td>{{ $patient->registration_date }}</td></tr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection