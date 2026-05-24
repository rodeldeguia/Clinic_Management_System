@extends('layouts.doctor')

@section('title', 'My Appointments')
@section('header', 'Appointment Management')
@section('subheader', 'View and manage all your appointments')

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'pending' ? 'active' : (request('filter') ? '' : 'active') }}" href="?filter=pending">
                    <i class="fas fa-clock"></i> Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'confirmed' ? 'active' : '' }}" href="?filter=confirmed">
                    <i class="fas fa-check-circle"></i> Confirmed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'today' ? 'active' : '' }}" href="?filter=today">
                    <i class="fas fa-calendar-day"></i> Today
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'upcoming' ? 'active' : '' }}" href="?filter=upcoming">
                    <i class="fas fa-calendar-week"></i> Upcoming
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'completed' ? 'active' : '' }}" href="?filter=completed">
                    <i class="fas fa-check-double"></i> Completed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'all' ? 'active' : '' }}" href="?filter=all">
                    <i class="fas fa-list"></i> All
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient Name</th>
                        <th>Contact</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $index => $appointment)
                    <tr>
                        <td>{{ $appointments->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}</td>
                        <td>
                            <strong>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</strong>
                            <br>
                            <small class="text-muted">ID: {{ $appointment->patient->user_id ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <i class="fas fa-phone"></i> {{ $appointment->patient->contact_number ?? 'N/A' }}<br>
                            <i class="fas fa-envelope"></i> {{ $appointment->patient->email_address ?? 'N/A' }}
                        </td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $appointment->reason_for_visit }}">
                                {{ Str::limit($appointment->reason_for_visit, 30) ?: 'No reason provided' }}
                            </span>
                        </td>
                        <td>
                            @if($appointment->status == 'scheduled')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @elseif($appointment->status == 'confirmed')
                                <span class="badge bg-primary">
                                    <i class="fas fa-check-circle"></i> Confirmed
                                </span>
                            @elseif($appointment->status == 'in-progress')
                                <span class="badge bg-info">
                                    <i class="fas fa-stethoscope"></i> In Progress
                                </span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-double"></i> Completed
                                </span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Cancelled
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $appointment->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                @if($appointment->status == 'scheduled')
                                    <form action="{{ route('doctor.appointments.confirm', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Confirm this appointment?')" title="Confirm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('doctor.appointments.cancel', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this appointment?')" title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($appointment->status == 'confirmed')
                                    <a href="{{ route('doctor.patient-care.treat', $appointment->appointment_id) }}" class="btn btn-primary" title="Start Consultation">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                    <form action="{{ route('doctor.appointments.cancel', $appointment->appointment_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this appointment?')" title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($appointment->status == 'completed')
                                    <a href="{{ route('doctor.patient-care.medical-history', $appointment->patient->user_id) }}" class="btn btn-info" title="View Medical Record">
                                        <i class="fas fa-notes-medical"></i>
                                    </a>
                                @endif
                                
                                <a href="{{ route('doctor.appointments.show', $appointment->appointment_id) }}" class="btn btn-secondary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">No appointments found</p>
                            <small class="text-muted">Appointments will appear here once patients book with you</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    .nav-tabs .nav-link {
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        font-weight: 600;
        color: #2e7d32;
        border-bottom-color: #2e7d32;
    }
</style>
@endpush
@endsection