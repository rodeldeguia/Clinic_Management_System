@extends('layouts.receptionist')

@section('title', 'Verify Patient')
@section('header', 'Patient Verification')
@section('subheader', 'Find existing patient by ID, name, or contact')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('receptionist.patients.verify') }}">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="identifier" class="form-control form-control-lg" 
                           placeholder="Enter Patient ID, Name, or Contact Number" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(isset($patient))
<div class="card mt-3">
    <div class="card-header bg-success text-white">
        <i class="fas fa-user-check"></i> Patient Found
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Patient ID:</strong> {{ $patient->user_id }}</p>
                <p><strong>Name:</strong> {{ $patient->firstname }} {{ $patient->lastname }}</p>
                <p><strong>Contact:</strong> {{ $patient->contact_number }}</p>
                <p><strong>Email:</strong> {{ $patient->email_address }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Blood Group:</strong> {{ $patient->blood_group ?? 'N/A' }}</p>
                <p><strong>Registered:</strong> {{ $patient->registration_date }}</p>
                <p><strong>Address:</strong> {{ $patient->address }}</p>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->user_id]) }}" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Schedule Appointment
            </a>
            <a href="{{ route('receptionist.records.patients.show', $patient->user_id) }}" class="btn btn-info">
                <i class="fas fa-folder-open"></i> View Records
            </a>
        </div>
    </div>
</div>
@endif
@endsection