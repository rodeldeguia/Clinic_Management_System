@extends('layouts.doctor')

@section('title', 'Dashboard')
@section('header', 'Doctor Dashboard')
@section('subheader', 'Welcome back, Dr. ' . Auth::user()->firstname . '!')

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
            <p class="text-muted mb-0">Pending Confirmation</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-calendar-week"></i>
            <h3 class="mt-2">{{ $upcoming_appointments ?? 0 }}</h3>
            <p class="text-muted mb-0">Upcoming Appointments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-users"></i>
            <h3 class="mt-2">{{ $total_patients ?? 0 }}</h3>
            <p class="text-muted mb-0">Total Patients</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Appointments -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-clock"></i> Today's Appointments
            </div>
            <div class="card-body">
                @if(isset($today_appointments_list) && $today_appointments_list->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Time</th><th>Patient Name</th><th>Contact</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($today_appointments_list as $appointment)
                            <tr>
                                <td>{{ date('h:i A', strtotime($appointment->time_slot)) }}</td>
                                <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                                <td>{{ $appointment->patient->contact_number ?? 'N/A' }}</td>
                                <td>
                                    @if($appointment->status == 'scheduled')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appointment->status == 'scheduled')
                                        <form action="{{ route('doctor.appointments.confirm', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm this appointment?')">
                                                <i class="fas fa-check"></i> Confirm
                                            </button>
                                        </form>
                                    @elseif($appointment->status == 'confirmed')
                                        <a href="{{ route('doctor.patient-care.treat', $appointment->appointment_id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-stethoscope"></i> Start Consultation
                                        </a>
                                    @endif
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                        <p>No appointments scheduled for today.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Appointments (Waiting for Confirmation) -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <i class="fas fa-hourglass-half"></i> Pending Confirmation
            </div>
            <div class="card-body">
                @if(isset($pending_appointments_list) && $pending_appointments_list->count() > 0)
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr><th>Date</th><th>Time</th><th>Patient</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pending_appointments_list as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ date('h:i A', strtotime($appointment->time_slot)) }}</td>
                                <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                                <td>
                                    <form action="{{ route('doctor.appointments.confirm', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm this appointment?')">
                                            <i class="fas fa-check"></i> Confirm
                                        </button>
                                    </form>
                                    <form action="{{ route('doctor.appointments.cancel', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No pending appointments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Confirmed Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-calendar-check"></i> Upcoming Confirmed Appointments
            </div>
            <div class="card-body">
                @if(isset($upcoming_appointments_list) && $upcoming_appointments_list->count() > 0)
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr><th>Date</th><th>Time</th><th>Patient</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming_appointments_list as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ date('h:i A', strtotime($appointment->time_slot)) }}</td>
                                <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                                <td>
                                    <a href="{{ route('doctor.patient-care.treat', $appointment->appointment_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-stethoscope"></i> Start
                                    </a>
                                 </div>
                             </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No upcoming confirmed appointments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection