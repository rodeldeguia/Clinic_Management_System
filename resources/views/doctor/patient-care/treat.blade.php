@extends('layouts.doctor')

@section('title', 'Patient Consultation')
@section('header', 'Patient Consultation')
@section('subheader', 'Treating: ' . ($appointment->patient->firstname ?? 'N/A') . ' ' . ($appointment->patient->lastname ?? ''))

@section('content')
<form method="POST" action="{{ route('doctor.patient-care.update-treatment', $appointment->appointment_id) }}">
    @csrf
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">Patient Information</div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</p>
                    <p><strong>Age:</strong> 
                        @if($appointment->patient->date_of_birth)
                            {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} years
                        @else N/A
                        @endif
                    </p>
                    <p><strong>Gender:</strong> {{ ucfirst($appointment->patient->gender ?? 'N/A') }}</p>
                    <p><strong>Blood Group:</strong> {{ $appointment->patient->blood_group ?? 'N/A' }}</p>
                    <p><strong>Contact:</strong> {{ $appointment->patient->contact_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Diagnosis & Prescription</div>
                <div class="card-body">
                    <!-- Diagnosis Section -->
                    <div class="mb-3">
                        <label>Diagnosis *</label>
                        <textarea name="diagnosis" class="form-control" rows="2" required placeholder="Enter diagnosis...">{{ $medicalRecord->diagnosis ?? '' }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label>Doctor's Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ $medicalRecord->notes ?? '' }}</textarea>
                    </div>
                    
                    <hr>
                    <h5><i class="fas fa-pills"></i> Prescription</h5>
                    
                    <!-- Prescription Section -->
                    <div id="prescriptions-container">
                        <div class="prescription-row row mb-3">
                            <div class="col-md-5">
                                <label>Medicine</label>
                                <select name="prescriptions[0][medicine_id]" class="form-control">
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines ?? [] as $medicine)
                                        <option value="{{ $medicine->medicine_id }}">{{ $medicine->medicine_name }} ({{ $medicine->category }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Dosage</label>
                                <input type="text" name="prescriptions[0][dosage]" class="form-control" placeholder="e.g., 2x daily">
                            </div>
                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" name="prescriptions[0][quantity]" class="form-control" placeholder="Number of tablets" min="1">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger form-control remove-row" style="display: none;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm" id="add-prescription">
                        <i class="fas fa-plus"></i> Add Another Medicine
                    </button>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Diagnosis & Prescription
                        </button>
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    let counter = 1;
    
    document.getElementById('add-prescription').addEventListener('click', function() {
        const container = document.getElementById('prescriptions-container');
        const newRow = document.createElement('div');
        newRow.className = 'prescription-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="prescriptions[${counter}][medicine_id]" class="form-control">
                    <option value="">Select Medicine</option>
                    @foreach($medicines ?? [] as $medicine)
                        <option value="{{ $medicine->medicine_id }}">{{ $medicine->medicine_name }} ({{ $medicine->category }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="prescriptions[${counter}][dosage]" class="form-control" placeholder="e.g., 2x daily">
            </div>
            <div class="col-md-3">
                <input type="number" name="prescriptions[${counter}][quantity]" class="form-control" placeholder="Number of tablets" min="1">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger form-control remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        counter++;
        
        attachRemoveEvents();
        updateRemoveButtons();
    });
    
    function attachRemoveEvents() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.removeEventListener('click', removeRow);
            btn.addEventListener('click', removeRow);
        });
    }
    
    function removeRow(e) {
        if(document.querySelectorAll('.prescription-row').length > 1) {
            e.target.closest('.prescription-row').remove();
            updateRemoveButtons();
        } else {
            alert('At least one medicine is required');
        }
    }
    
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.prescription-row');
        const removeBtns = document.querySelectorAll('.remove-row');
        
        if(rows.length > 1) {
            removeBtns.forEach(btn => btn.style.display = 'block');
        } else {
            removeBtns.forEach(btn => btn.style.display = 'none');
        }
    }
    
    attachRemoveEvents();
    updateRemoveButtons();
</script>
@endpush
@endsection