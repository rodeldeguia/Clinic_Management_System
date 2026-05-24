@extends('layouts.patient')

@section('title', 'Book Appointment')
@section('header', 'Book New Appointment')
@section('subheader', 'Schedule a consultation with a doctor')

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('patient.appointments.store') }}" id="appointmentForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Select Doctor *</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->user_id }}" {{ old('doctor_id') == $doctor->user_id ? 'selected' : '' }}>
                                Dr. {{ $doctor->firstname }} {{ $doctor->lastname }} - {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Appointment Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" 
                           min="{{ date('Y-m-d') }}" value="{{ old('appointment_date') }}" required>
                    @error('appointment_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Time Slot *</label>
                    <select name="time_slot" id="time_slot" class="form-control" required>
                        <option value="">Select doctor and date first</option>
                    </select>
                    @error('time_slot') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Reason for Visit</label>
                    <textarea name="reason_for_visit" class="form-control" rows="3" placeholder="Briefly describe your symptoms or reason for visit">{{ old('reason_for_visit') }}</textarea>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Your appointment will be pending confirmation by the doctor. You will be notified once confirmed.
            </div>
            
            <button type="submit" class="btn btn-primary">Book Appointment</button>
            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadTimeSlots() {
        var doctorId = $('#doctor_id').val();
        var date = $('#appointment_date').val();
        
        console.log('Loading slots for doctor:', doctorId, 'date:', date);
        
        if (!doctorId || !date) {
            $('#time_slot').html('<option value="">Select doctor and date first</option>');
            return;
        }
        
        $('#time_slot').html('<option value="">Loading available slots...</option>');
        
        $.ajax({
            url: '/patient/appointments/available-slots/' + doctorId,
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                var select = $('#time_slot');
                select.empty();
                
                if (response.slots && response.slots.length > 0) {
                    select.append('<option value="">Select Time Slot</option>');
                    $.each(response.slots, function(i, slot) {
                        var displaySlot = slot.display || slot;
                        var valueSlot = slot.value || slot;
                        select.append('<option value="' + valueSlot + '">' + displaySlot + '</option>');
                    });
                } else {
                    select.append('<option value="">No available slots for this date</option>');
                    if (response.message) {
                        console.log('Message:', response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                $('#time_slot').html('<option value="">Error loading slots. Please try again.</option>');
            }
        });
    }
    
    $('#doctor_id').change(loadTimeSlots);
    $('#appointment_date').change(loadTimeSlots);
});
</script>
@endpush
@endsection