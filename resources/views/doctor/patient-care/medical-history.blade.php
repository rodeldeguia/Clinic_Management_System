@extends('layouts.doctor')

@section('title', 'Patient Medical History')
@section('header', 'Medical History')
@section('subheader', 'Patient health records')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Medical Records</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Date</th><th>Diagnosis</th><th>Prescription</th><th>Notes</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date }}</td>
                    <td>{{ $appointment->medicalRecord->diagnosis ?? 'N/A' }}</td>
                    <td>{{ $appointment->medicalRecord->prescription_text ?? 'N/A' }}</td>
                    <td>{{ $appointment->medicalRecord->notes ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No medical records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection