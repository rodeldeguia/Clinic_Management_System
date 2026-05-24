@extends('layouts.patient')

@section('title', 'Medical Record Details')
@section('header', 'Medical Record')
@section('subheader', 'Appointment on ' . $appointment->appointment_date)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-notes-medical"></i> Consultation Details
            </div>
            <div class="card-body">
                <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</p>
                <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization ?? 'General' }}</p>
                <p><strong>Date:</strong> {{ $appointment->appointment_date }} at {{ $appointment->time_slot }}</p>
                <hr>
                <p><strong>Diagnosis:</strong></p>
                <div class="border p-3 rounded bg-light">
                    {{ $appointment->medicalRecord->diagnosis ?? 'No diagnosis recorded' }}
                </div>
                <p class="mt-3"><strong>Notes:</strong></p>
                <div class="border p-3 rounded bg-light">
                    {{ $appointment->medicalRecord->notes ?? 'No additional notes' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-pills"></i> Prescriptions
            </div>
            <div class="card-body">
                <p><strong>Prescribed Medicines:</strong></p>
                <div class="border p-3 rounded bg-light">
                    {{ $appointment->medicalRecord->prescription_text ?? 'No prescription recorded' }}
                </div>
                
                @if($appointment->medicalRecord && $appointment->medicalRecord->prescriptions->count() > 0)
                    <hr>
                    <p><strong>Prescribed Medicines List:</strong></p>
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Medicine</th><th>Dosage</th><th>Quantity</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($appointment->medicalRecord->prescriptions as $prescription)
                            <tr>
                                <td>{{ $prescription->medicine->medicine_name ?? 'N/A' }}</td>
                                <td>{{ $prescription->dosage }}</td>
                                <td>{{ $prescription->quantity_prescribed }} tablets</td>
                                <td>
                                    @if($prescription->status == 'dispensed')
                                        <span class="badge bg-success">Dispensed</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('patient.medical-records.index') }}" class="btn btn-secondary">Back to Records</a>
    @if(!$appointment->feedback)
        <a href="{{ route('patient.feedback.create', $appointment->appointment_id) }}" class="btn btn-info">
            <i class="fas fa-star"></i> Give Feedback
        </a>
    @endif
</div>
@endsection