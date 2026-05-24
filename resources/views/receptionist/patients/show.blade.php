@extends('layouts.receptionist')

@section('title', 'Patient Details')
@section('header', 'Patient Information')
@section('subheader', $patient->firstname . ' ' . $patient->lastname)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <table class="table">
                    <tr><th>Patient ID</th><td>{{ $patient->user_id }}</td></tr>
                    <tr><th>Full Name</th><td>{{ $patient->firstname }} {{ $patient->lastname }}</td></tr>
                    <tr><th>Contact</th><td>{{ $patient->contact_number }}</td></tr>
                    <tr><th>Email</th><td>{{ $patient->email_address }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $patient->date_of_birth }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($patient->gender) }}</td></tr>
                    <tr><th>Blood Group</th><td>{{ $patient->blood_group ?? 'N/A' }}</td></tr>
                    <tr><th>Emergency Contact</th><td>{{ $patient->emergency_contact ?? 'N/A' }}</td></tr>
                    <tr><th>Address</th><td>{{ $patient->address }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Quick Actions</div>
            <div class="card-body">
                <a href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->user_id]) }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-calendar-plus"></i> Schedule Appointment
                </a>
                <a href="{{ route('receptionist.billing.generate', ['appointment_id' => 'new']) }}" class="btn btn-success w-100 mb-2">
                    <i class="fas fa-receipt"></i> Generate Bill
                </a>
                <a href="{{ route('receptionist.records.patients.show', $patient->user_id) }}" class="btn btn-info w-100">
                    <i class="fas fa-folder-open"></i> View Medical Records
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Appointment History</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Date</th><th>Doctor</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->patientAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date }} {{ $appointment->time_slot }} </td>
                            <td>Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                            <td><span class="badge bg-info">{{ $appointment->status }}</span></td>
                            <td>
                                <a href="{{ route('receptionist.appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
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