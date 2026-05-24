@extends('layouts.receptionist')

@section('title', 'Patient Medical Records')
@section('header', 'Medical Records')
@section('subheader', $patient->firstname . ' ' . $patient->lastname)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Appointment & Treatment History</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Date</th><th>Doctor</th><th>Diagnosis</th><th>Prescription</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->patientAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date }} {{ $appointment->time_slot }}</td>
                            <td>Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                            <td>{{ $appointment->medicalRecord->diagnosis ?? 'N/A' }}</td>
                            <td>{{ $appointment->medicalRecord->prescription_text ?? 'N/A' }}</td>
                            <td>{{ $appointment->medicalRecord->notes ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No medical records found</td></tr>
                        @endforelse
                    </tbody>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection