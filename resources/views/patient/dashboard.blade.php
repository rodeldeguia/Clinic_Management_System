@extends('layouts.patient')

@section('title', 'Dashboard')
@section('header', 'Patient Dashboard')
@section('subheader', 'Welcome back, ' . Auth::user()->firstname . '!')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-calendar-day"></i>
            <h3 class="mt-2">{{ $upcoming_appointments ?? 0 }}</h3>
            <p class="text-muted mb-0">Upcoming Appointments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-calendar-check"></i>
            <h3 class="mt-2">{{ $completed_appointments ?? 0 }}</h3>
            <p class="text-muted mb-0">Completed Visits</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-receipt"></i>
            <h3 class="mt-2">{{ $pending_bills ?? 0 }}</h3>
            <p class="text-muted mb-0">Pending Bills</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-star"></i>
            <h3 class="mt-2">{{ $feedback_given ?? 0 }}</h3>
            <p class="text-muted mb-0">Feedback Given</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock"></i> Upcoming Appointments
            </div>
            <div class="card-body">
                @if(isset($upcoming_appointments_list) && $upcoming_appointments_list->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Date</th><th>Time</th><th>Doctor</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming_appointments_list as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ date('h:i A', strtotime($appointment->time_slot)) }}</td>
                                <td>Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                                <td>
                                    @if($appointment->status == 'scheduled')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('patient.appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-warning">Reschedule</a>
                                    <form action="{{ route('patient.appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <p>No upcoming appointments.</p>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Book New Appointment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection