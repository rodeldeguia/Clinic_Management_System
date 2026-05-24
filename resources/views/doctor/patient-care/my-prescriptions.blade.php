@extends('layouts.doctor')

@section('title', 'My Prescriptions')
@section('header', 'Prescriptions Issued')
@section('subheader', 'View all medicines you have prescribed to patients')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-prescription-bottle"></i> Prescription History</h5>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge bg-primary">{{ $prescriptions->total() }} Total Prescriptions</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($prescriptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Patient Name</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Quantity</th>
                            <th>Dispensed</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $index => $prescription)
                        <tr>
                            <td>{{ $prescriptions->firstItem() + $index }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($prescription->medicalRecord->created_at ?? $prescription->dispensed_at)->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($prescription->medicalRecord->created_at ?? $prescription->dispensed_at)->format('h:i A') }}</small>
                            </td>
                            <td>
                                <strong>{{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }}</strong>
                                {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}
                                <br>
                                <small class="text-muted">ID: {{ $prescription->medicalRecord->appointment->patient->user_id ?? 'N/A' }}</small>
                            </td>
                            <td>
                                {{ $prescription->medicine->medicine_name ?? 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $prescription->medicine->category ?? '' }}</small>
                            </td>
                            <td>{{ $prescription->dosage }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $prescription->quantity_prescribed }} pcs</span>
                             </div>
                            <td class="text-center">
                                @if($prescription->quantity_dispensed > 0)
                                    <span class="badge bg-success">{{ $prescription->quantity_dispensed }} / {{ $prescription->quantity_prescribed }}</span>
                                @else
                                    <span class="badge bg-secondary">0 / {{ $prescription->quantity_prescribed }}</span>
                                @endif
                             </div>
                            <td>
                                @if($prescription->status == 'dispensed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Dispensed
                                    </span>
                                @elseif($prescription->status == 'partially_despensed')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-hourglass-half"></i> Partial
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock"></i> Prescribed
                                    </span>
                                @endif
                             </div>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#prescriptionModal{{ $prescription->prescription_id }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                             </div>
                        </tr>
                        @endforeach
                    </tbody>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $prescriptions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-prescription-bottle fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Prescriptions Yet</h5>
                <p class="text-muted">You haven't prescribed any medicines yet.</p>
                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> View Appointments
                </a>
            </div>
        @endif
    </div>
</div>

@foreach($prescriptions as $prescription)
<!-- Prescription Details Modal -->
<div class="modal fade" id="prescriptionModal{{ $prescription->prescription_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-prescription-bottle"></i> Prescription Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-user"></i> Patient Information
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> {{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}</p>
                                <p><strong>Patient ID:</strong> {{ $prescription->medicalRecord->appointment->patient->user_id ?? 'N/A' }}</p>
                                <p><strong>Contact:</strong> {{ $prescription->medicalRecord->appointment->patient->contact_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-pills"></i> Medicine Information
                            </div>
                            <div class="card-body">
                                <p><strong>Medicine:</strong> {{ $prescription->medicine->medicine_name ?? 'N/A' }}</p>
                                <p><strong>Category:</strong> {{ $prescription->medicine->category ?? 'N/A' }}</p>
                                <p><strong>Manufacturer:</strong> {{ $prescription->medicine->manufacturer ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-clinic-medical"></i> Prescription Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Dosage:</strong><br>{{ $prescription->dosage }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Quantity Prescribed:</strong><br>{{ $prescription->quantity_prescribed }} tablets</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Quantity Dispensed:</strong><br>{{ $prescription->quantity_dispensed ?? 0 }} tablets</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong>
                                    @if($prescription->status == 'dispensed')
                                        <span class="badge bg-success">Fully Dispensed</span>
                                    @elseif($prescription->status == 'partially_despensed')
                                        <span class="badge bg-warning">Partially Dispensed</span>
                                    @else
                                        <span class="badge bg-secondary">Pending Dispensing</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Prescribed On:</strong><br>{{ $prescription->medicalRecord->created_at ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($prescription->dispensed_by)
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <i class="fas fa-store"></i> Dispensing Information
                    </div>
                    <div class="card-body">
                        <p><strong>Dispensed By:</strong> {{ $prescription->dispensedBy->firstname ?? 'N/A' }} {{ $prescription->dispensedBy->lastname ?? '' }}</p>
                        <p><strong>Dispensed On:</strong> {{ $prescription->dispensed_at ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif
                
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-stethoscope"></i> Consultation Notes
                    </div>
                    <div class="card-body">
                        <p><strong>Diagnosis:</strong> {{ $prescription->medicalRecord->diagnosis ?? 'N/A' }}</p>
                        <p><strong>Notes:</strong> {{ $prescription->medicalRecord->notes ?? 'No additional notes' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

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
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush
@endsection