@extends('layouts.receptionist')

@section('title', 'Dashboard')
@section('header', 'Receptionist Dashboard')
@section('subheader', 'Welcome back, ' . Auth::user()->firstname . '!')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-calendar-day"></i>
            <h3 class="mt-2">{{ $today_appointments ?? 0 }}</h3>
            <p class="text-muted mb-0">Today's Appointments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-hourglass-half"></i>
            <h3 class="mt-2">{{ $pending_appointments ?? 0 }}</h3>
            <p class="text-muted mb-0">Pending Appointments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-user-plus"></i>
            <h3 class="mt-2">{{ $new_patients_today ?? 0 }}</h3>
            <p class="text-muted mb-0">New Patients Today</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-money-bill"></i>
            <h3 class="mt-2">₱{{ number_format($today_revenue ?? 0, 2) }}</h3>
            <p class="text-muted mb-0">Today's Revenue</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('receptionist.patients.register') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus"></i> Register New Patient
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-calendar-plus"></i> Schedule Appointment
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('receptionist.patients.verify.form') }}" class="btn btn-info w-100">
                            <i class="fas fa-search"></i> Verify Patient
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Today's Schedule
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($today_appointments_list ?? [] as $appointment)
                        <tr>
                            <td>{{ $appointment->time_slot }}</td>
                            <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                            <td>{{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                            <td><span class="badge bg-info">{{ $appointment->status }}</span></td>
                            <td>
                                <a href="{{ route('receptionist.appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-warning">Update</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No appointments today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection