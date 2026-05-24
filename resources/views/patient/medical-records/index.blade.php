@extends('layouts.patient')

@section('title', 'Medical Records')
@section('header', 'My Medical Records')
@section('subheader', 'View your health history and prescriptions')

@section('content')
<div class="card">
    <div class="card-body">
        @if($appointments->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Diagnosis</th>
                        <th>Prescription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                        <td>Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                        <td>{{ Str::limit($appointment->medicalRecord->diagnosis ?? 'N/A', 50) }}</td>
                        <td>{{ Str::limit($appointment->medicalRecord->prescription_text ?? 'N/A', 50) }}</td>
                        <td>
                            <a href="{{ route('patient.medical-records.show', $appointment->appointment_id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-notes-medical fa-3x text-muted mb-3"></i>
                <p>No medical records found.</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Book an Appointment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection