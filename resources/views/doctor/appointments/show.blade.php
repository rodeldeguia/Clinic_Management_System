@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('header', 'Appointment Details')
@section('subheader', 'View complete appointment information')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calendar-check"></i> Appointment Information
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%">Appointment ID:</th>
                        <td>#{{ $appointment->appointment_id }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Time:</th>
                        <td>{{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($appointment->status == 'scheduled')
                                <span class="badge bg-warning text-dark">Pending Confirmation</span>
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
                    </tr>
                    <tr>
                        <th>Reason for Visit:</th>
                        <td>{{ $appointment->reason_for_visit ?? 'No reason provided' }}</td>
                    </tr>
                    <tr>
                        <th>Created By:</th>
                        <td>
                            @if($appointment->createdBy)
                                {{ $appointment->createdBy->firstname ?? '' }} {{ $appointment->createdBy->lastname ?? '' }}
                                ({{ ucfirst($appointment->createdBy->role ?? '') }})
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $appointment->created_at }}</td>
                    </tr>
                    @if($appointment->cancellation_reason)
                    <tr>
                        <th>Cancellation Reason:</th>
                        <td class="text-danger">{{ $appointment->cancellation_reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-user"></i> Patient Information
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%">Patient ID:</th>
                        <td>{{ $appointment->patient->user_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Full Name:</th>
                        <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Contact Number:</th>
                        <td>{{ $appointment->patient->contact_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $appointment->patient->email_address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td>{{ $appointment->patient->date_of_birth ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td>{{ ucfirst($appointment->patient->gender ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Blood Group:</th>
                        <td>{{ $appointment->patient->blood_group ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{ $appointment->patient->address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-notes-medical"></i> Medical Record
            </div>
            <div class="card-body">
                @if($appointment->medicalRecord)
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 20%">Diagnosis:</th>
                            <td>{{ $appointment->medicalRecord->diagnosis ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Prescription:</th>
                            <td>{{ $appointment->medicalRecord->prescription_text ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $appointment->medicalRecord->notes ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $appointment->medicalRecord->created_at }}</td>
                        </tr>
                    </table>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>No medical record has been created for this appointment yet.</p>
                        @if($appointment->status == 'confirmed')
                            <a href="{{ route('doctor.patient-care.treat', $appointment->appointment_id) }}" class="btn btn-primary">
                                <i class="fas fa-stethoscope"></i> Start Consultation
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-receipt"></i> Billing Information
            </div>
            <div class="card-body">
                @if($appointment->billing)
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 20%">Bill ID:</th>
                            <td>#{{ $appointment->billing->bill_id }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td>₱{{ number_format($appointment->billing->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td>₱{{ number_format($appointment->billing->discount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Tax:</th>
                            <td>₱{{ number_format($appointment->billing->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Net Amount:</th>
                            <td><strong>₱{{ number_format($appointment->billing->net_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                @if($appointment->billing->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p>No bill has been generated for this appointment yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('doctor.appointments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Appointments
    </a>
    
    @if($appointment->status == 'scheduled')
        <form action="{{ route('doctor.appointments.confirm', $appointment->appointment_id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Confirm this appointment?')">
                <i class="fas fa-check"></i> Confirm Appointment
            </button>
        </form>
    @endif
    
    @if($appointment->status == 'confirmed')
        <a href="{{ route('doctor.patient-care.treat', $appointment->appointment_id) }}" class="btn btn-primary">
            <i class="fas fa-stethoscope"></i> Start Consultation
        </a>
    @endif
    
    @if($appointment->status == 'scheduled' || $appointment->status == 'confirmed')
        <form action="{{ route('doctor.appointments.cancel', $appointment->appointment_id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this appointment?')">
                <i class="fas fa-times"></i> Cancel Appointment
            </button>
        </form>
    @endif
</div>
@endsection