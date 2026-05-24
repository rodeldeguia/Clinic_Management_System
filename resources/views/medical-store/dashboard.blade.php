@extends('layouts.medical-store')

@section('title', 'Dashboard')
@section('header', 'Pharmacy Dashboard')
@section('subheader', 'Welcome back, ' . Auth::user()->firstname . '!')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-prescription-bottle"></i>
            <h3 class="mt-2">{{ $pending_prescriptions ?? 0 }}</h3>
            <p class="text-muted mb-0">Pending Prescriptions</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-check-circle"></i>
            <h3 class="mt-2">{{ $dispensed_today ?? 0 }}</h3>
            <p class="text-muted mb-0">Dispensed Today</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-exclamation-triangle"></i>
            <h3 class="mt-2">{{ $low_stock_items ?? 0 }}</h3>
            <p class="text-muted mb-0">Low Stock Items</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-calendar-alt"></i>
            <h3 class="mt-2">{{ $expiring_soon ?? 0 }}</h3>
            <p class="text-muted mb-0">Expiring Soon</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-prescription-bottle"></i> Recent Prescriptions
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_prescriptions ?? [] as $prescription)
                        <tr>
                            <td>{{ $prescription->created_at }}</td>
                            <td>{{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}</td>
                            <td>Dr. {{ $prescription->medicalRecord->doctor->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->doctor->lastname ?? '' }}</td>
                            <td>{{ $prescription->medicine->medicine_name ?? 'N/A' }}</td>
                            <td>{{ $prescription->quantity_prescribed }} pc(s)</td>
                            <td>
                                @if($prescription->status == 'dispensed')
                                    <span class="badge bg-success">Dispensed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                             </div>
                            <td>
                                @if($prescription->status != 'dispensed')
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#dispenseModal{{ $prescription->prescription_id }}">
                                        <i class="fas fa-pills"></i> Dispense
                                    </button>
                                @else
                                    <span class="text-muted">Dispensed</span>
                                @endif
                             </div>
                        </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No prescriptions found</td></tr>
                        @endforelse
                    </tbody>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection