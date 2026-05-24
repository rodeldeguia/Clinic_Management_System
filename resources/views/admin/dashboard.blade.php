@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . Auth::user()->firstname . '!')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-user-md fa-3x"></i>
            <h3 class="mt-2">{{ $total_doctors ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Doctors</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-user-tie fa-3x"></i>
            <h3 class="mt-2">{{ $total_receptionists ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Receptionists</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-users fa-3x"></i>
            <h3 class="mt-2">{{ $total_patients ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Patients</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-calendar-check fa-3x"></i>
            <h3 class="mt-2">{{ $total_appointments_today ?? 0 }}</h3>
            <p class="text-muted mb-0">Today's Appointments</p>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock"></i> Recent Appointments
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recent_appointments ?? [] as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->firstname ?? 'N/A' }}</td>
                            <td>{{ $appointment->doctor->firstname ?? 'N/A' }}</td>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td><span class="badge bg-info">{{ $appointment->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No recent appointments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-plus"></i> Recent Patients
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Name</th><th>Contact</th><th>Registered</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recent_patients ?? [] as $patient)
                        <tr>
                            <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
                            <td>{{ $patient->contact_number }}</td>
                            <td>{{ $patient->registration_date ? $patient->registration_date->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No recent patients</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection