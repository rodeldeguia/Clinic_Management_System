@extends('layouts.patient')

@section('title', 'My Appointments')
@section('header', 'My Appointments')
@section('subheader', 'View and manage your appointments')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus"></i> Book New Appointment
        </a>
    </div>
    <div class="card-body">
        @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}</td>
                            <td>Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                            <td>{{ $appointment->doctor->specialization ?? 'General' }}</td>
                            <td>
                                @if($appointment->status == 'scheduled')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($appointment->status == 'confirmed')
                                    <span class="badge bg-primary">Confirmed</span>
                                @elseif($appointment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($appointment->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($appointment->status == 'scheduled' || $appointment->status == 'confirmed')
                                    <a href="{{ route('patient.appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Reschedule
                                    </a>
                                    <form action="{{ route('patient.appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                @endif
                                
                                @if($appointment->status == 'completed')
                                    @if(!$appointment->feedback)
                                        <a href="{{ route('patient.feedback.create', $appointment->appointment_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-star"></i> Give Feedback
                                        </a>
                                    @else
                                        <span class="badge bg-success">Feedback Given</span>
                                    @endif
                                    <a href="{{ route('patient.medical-records.show', $appointment->appointment_id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-notes-medical"></i> View Record
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">You have no appointments yet.</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Book Your First Appointment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection