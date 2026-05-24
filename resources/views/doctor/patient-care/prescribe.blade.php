@extends('layouts.doctor')

@section('title', 'Add Prescription')
@section('header', 'Add Prescription')
@section('subheader', 'Prescribe medicines for ' . ($appointment->patient->firstname ?? 'N/A') . ' ' . ($appointment->patient->lastname ?? ''))

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.patient-care.prescribe.store', $appointment->appointment_id) }}">
            @csrf
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Add at least one medicine for the patient.
            </div>
            
            <div id="prescriptions-container">
                <div class="prescription-row row mb-3">
                    <div class="col-md-5">
                        <label>Medicine *</label>
                        <select name="prescriptions[0][medicine_id]" class="form-control" required>
                            <option value="">Select Medicine</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->medicine_id }}">{{ $medicine->medicine_name }} ({{ $medicine->category }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Dosage *</label>
                        <input type="text" name="prescriptions[0][dosage]" class="form-control" placeholder="e.g., 2x daily" required>
                    </div>
                    <div class="col-md-3">
                        <label>Quantity *</label>
                        <input type="number" name="prescriptions[0][quantity]" class="form-control" placeholder="Number of tablets" min="1" required>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger form-control remove-row" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn btn-secondary" id="add-prescription">
                <i class="fas fa-plus"></i> Add Another Medicine
            </button>
            <button type="submit" class="btn btn-success float-end">
                <i class="fas fa-save"></i> Save Prescription & Complete Consultation
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let counter = 1;
    
    document.getElementById('add-prescription').addEventListener('click', function() {
        const container = document.getElementById('prescriptions-container');
        const newRow = document.createElement('div');
        newRow.className = 'prescription-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="prescriptions[${counter}][medicine_id]" class="form-control" required>
                    <option value="">Select Medicine</option>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->medicine_id }}">{{ $medicine->medicine_name }} ({{ $medicine->category }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="prescriptions[${counter}][dosage]" class="form-control" placeholder="e.g., 2x daily" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="prescriptions[${counter}][quantity]" class="form-control" placeholder="Number of tablets" min="1" required>
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