@extends('layouts.admin')

@section('title', 'Patient Details')
@section('header', 'Patient Details')
@section('subheader', $patient->firstname . ' ' . $patient->lastname)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <table class="table">
                    <tr><th>Full Name</th><td>{{ $patient->firstname }} {{ $patient->lastname }}</td></tr>
                    <tr><th>Email</th><td>{{ $patient->email_address }}</td></tr>
                    <tr><th>Contact</th><td>{{ $patient->contact_number }}</td></tr>
                    <tr><th>Emergency Contact</th><td>{{ $patient->emergency_contact ?? 'N/A' }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $patient->date_of_birth }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($patient->gender) }}</td></tr>
                    <tr><th>Blood Group</th><td>{{ $patient->blood_group ?? 'N/A' }}</td></tr>
                    <tr><th>Address</th><td>{{ $patient->address }}</td></tr>
                    <tr><th>Registered</th><td>{{ $patient->registration_date }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Appointments</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Date</th><th>Doctor</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($appointments ?? [] as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td>{{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                            <td><span class="badge bg-info">{{ $appointment->status }}</span></td>
                            <td><a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No appointments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection