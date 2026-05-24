@extends('layouts.medical-store')

@section('title', 'Prescriptions')
@section('header', 'Prescription Management')
@section('subheader', 'View and dispense medicines')

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ request('status') != 'dispensed' ? 'active' : '' }}" href="?status=pending">
                    <i class="fas fa-clock"></i> Pending ({{ $pending_count ?? 0 }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'dispensed' ? 'active' : '' }}" href="?status=dispensed">
                    <i class="fas fa-check-circle"></i> Dispensed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'partial' ? 'active' : '' }}" href="?status=partial">
                    <i class="fas fa-hourglass-half"></i> Partially Dispensed
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
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <th>Prescribed</th>
                        <th>Dispensed</th>
                        <th>Remaining</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $index => $prescription)
                    @php
                        $dispensed = $prescription->quantity_dispensed ?? 0;
                        $prescribed = $prescription->quantity_prescribed;
                        $remaining = $prescribed - $dispensed;
                        $progressPercent = ($dispensed / $prescribed) * 100;
                    @endphp
                    <tr>
                        <td>{{ $prescriptions->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($prescription->created_at)->format('M d, Y h:i A') }}</td>
                        <td>
                            {{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }}
                            {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}
                            <br><small class="text-muted">ID: {{ $prescription->medicalRecord->appointment->patient->user_id ?? 'N/A' }}</small>
                         </div>
                        <td>Dr. {{ $prescription->medicalRecord->doctor->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->doctor->lastname ?? '' }}</div>
                        <td>
                            {{ $prescription->medicine->medicine_name ?? 'N/A' }}
                            <br><small class="text-muted">{{ $prescription->medicine->category ?? '' }}</small>
                         </div>
                        <td>{{ $prescription->dosage }}</div>
                        <td class="text-center">{{ $prescribed }} pcs</div>
                        <td class="text-center">
                            <span class="fw-bold text-success">{{ $dispensed }} pcs</span>
                         </div>
                        <td class="text-center">
                            @if($remaining > 0)
                                <span class="fw-bold text-warning">{{ $remaining }} pcs</span>
                            @else
                                <span class="fw-bold text-success">0 pcs</span>
                            @endif
                         </div>
                        <td>
                            @if($prescription->status == 'dispensed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Fully Dispensed
                                </span>
                            @elseif($remaining > 0 && $dispensed > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-hourglass-half"></i> Partial ({{ $progressPercent }}%)
                                </span>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                         </div>
                        <td>
                            @if($remaining > 0)
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#dispenseModal{{ $prescription->prescription_id }}">
                                    <i class="fas fa-pills"></i> Dispense
                                </button>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $prescription->prescription_id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $prescription->prescription_id }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            @endif
                         </div>
                    </tr>
                    @empty
                        <tr><td colspan="11" class="text-center py-4">
                            <i class="fas fa-prescription-bottle fa-3x text-muted mb-3"></i>
                            <p>No prescriptions found</p>
                         </div>
                    @endforelse
                </tbody>
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $prescriptions->links() }}
        </div>
    </div>
</div>

@foreach($prescriptions as $prescription)
@php
    $remaining = $prescription->quantity_prescribed - ($prescription->quantity_dispensed ?? 0);
@endphp
<!-- Dispense Modal -->
<div class="modal fade" id="dispenseModal{{ $prescription->prescription_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('medical-store.prescriptions.dispense', $prescription->prescription_id) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Dispense Medicine</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Medicine:</strong> {{ $prescription->medicine->medicine_name ?? 'N/A' }}</p>
                    <p><strong>Patient:</strong> {{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}</p>
                    <p><strong>Prescribed Quantity:</strong> {{ $prescription->quantity_prescribed }} pc(s)</p>
                    <p><strong>Already Dispensed:</strong> {{ $prescription->quantity_dispensed ?? 0 }} pc(s)</p>
                    <p><strong>Remaining to Dispense:</strong> <span class="fw-bold text-warning">{{ $remaining }} pc(s)</span></p>
                    
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ ($prescription->quantity_dispensed ?? 0) / $prescription->quantity_prescribed * 100 }}%"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Quantity to Dispense *</label>
                        <input type="number" name="quantity_dispensed" class="form-control" 
                               min="1" max="{{ $remaining }}" required>
                        <small class="text-muted">Max: {{ $remaining }} pcs</small>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="partialNote{{ $prescription->prescription_id }}">
                        <label class="form-check-label" for="partialNote{{ $prescription->prescription_id }}">
                            This is a partial dispense (remaining will be dispensed later)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Dispense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal{{ $prescription->prescription_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Patient Information</h6>
                        <p><strong>Name:</strong> {{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}</p>
                        <p><strong>Contact:</strong> {{ $prescription->medicalRecord->appointment->patient->contact_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Doctor Information</h6>
                        <p><strong>Name:</strong> Dr. {{ $prescription->medicalRecord->doctor->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->doctor->lastname ?? '' }}</p>
                        <p><strong>Specialization:</strong> {{ $prescription->medicalRecord->doctor->specialization ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Prescription Details</h6>
                        <p><strong>Medicine:</strong> {{ $prescription->medicine->medicine_name ?? 'N/A' }}</p>
                        <p><strong>Dosage:</strong> {{ $prescription->dosage }}</p>
                        <p><strong>Quantity Prescribed:</strong> {{ $prescription->quantity_prescribed }} pc(s)</p>
                        <p><strong>Quantity Dispensed:</strong> {{ $prescription->quantity_dispensed ?? 0 }} pc(s)</p>
                        <p><strong>Remaining:</strong> {{ $prescription->quantity_prescribed - ($prescription->quantity_dispensed ?? 0) }} pc(s)</p>
                        <p><strong>Diagnosis:</strong> {{ $prescription->medicalRecord->diagnosis ?? 'N/A' }}</p>
                    </div>
                </div>
                @if($prescription->dispensed_at)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Dispensing Information</h6>
                        <p><strong>Dispensed By:</strong> {{ $prescription->dispensedBy->firstname ?? 'N/A' }} {{ $prescription->dispensedBy->lastname ?? '' }}</p>
                        <p><strong>Dispensed At:</strong> {{ $prescription->dispensed_at }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection