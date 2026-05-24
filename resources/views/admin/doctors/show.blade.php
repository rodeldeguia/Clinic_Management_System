@extends('layouts.admin')

@section('title', 'Doctor Details')
@section('header', 'Doctor Details')
@section('subheader', 'Dr. ' . $doctor->firstname . ' ' . $doctor->lastname)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Name</th><td>Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}</td></tr>
                    <tr><th>Email</th><td>{{ $doctor->email_address }}</td></tr>
                    <tr><th>Contact</th><td>{{ $doctor->contact_number }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($doctor->gender) }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $doctor->date_of_birth }}</td></tr>
                    <tr><th>Address</th><td>{{ $doctor->address }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Professional Information</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Specialization</th><td>{{ $doctor->specialization }}</td></tr>
                    <tr><th>License Number</th><td>{{ $doctor->license_number }}</td></tr>
                    <tr><th>Shift Timing</th><td>{{ $doctor->shift_timing }}</td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Performance Metrics</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Total Appointments</th><td>{{ $total_appointments ?? 0 }}</td></tr>
                    <tr><th>Completed</th><td>{{ $completed_appointments ?? 0 }}</td></tr>
                    <tr><th>Average Rating</th><td>{{ number_format($average_rating ?? 0, 1) }} / 5</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection