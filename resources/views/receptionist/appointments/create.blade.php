@extends('layouts.receptionist')

@section('title', 'Schedule Appointment')
@section('header', 'Schedule New Appointment')
@section('subheader', 'Book an appointment for a patient')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('receptionist.appointments.store') }}" id="appointmentForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Patient *</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->user_id }}" {{ old('patient_id', request('patient_id')) == $patient->user_id ? 'selected' : '' }}>
                                {{ $patient->firstname }} {{ $patient->lastname }} (ID: {{ $patient->user_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Doctor *</label>
                    <select name="doctor_id" class="form-control" id="doctor_id" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->user_id }}" {{ old('doctor_id') == $doctor->user_id ? 'selected' : '' }}>
                                Dr. {{ $doctor->firstname }} {{ $doctor->lastname }} - {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Appointment Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="{{ old('appointment_date') }}" required>
                    @error('appointment_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Time Slot *</label>
                    <select name="time_slot" id="time_slot" class="form-control" required>
                        <option value="">Select doctor and date first</option>
                        @if(old('time_slot'))
                            <option value="{{ old('time_slot') }}" selected>{{ old('time_slot') }}</option>
                        @endif
                    </select>
                    @error('time_slot') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label>Reason for Visit</label>
                <textarea name="reason_for_visit" class="form-control" rows="3">{{ old('reason_for_visit') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Schedule Appointment</button>
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#doctor_id, #appointment_date').change(function() {
        var doctorId = $('#doctor_id').val();
        var date = $('#appointment_date').val();
        
        if(doctorId && date) {
            // Disable time slot dropdown while loading
            var timeSlotSelect = $('#time_slot');
            timeSlotSelect.html('<option value="">Loading available slots...</option>');
            timeSlotSelect.prop('disabled', true);
            
            $.get('{{ route("receptionist.appointments.available-slots") }}', {
                doctor_id: doctorId,
                date: date
            }, function(data) {
                timeSlotSelect.prop('disabled', false);
                timeSlotSelect.empty();
                
                if(data.slots && data.slots.length > 0) {
                    timeSlotSelect.append('<option value="">Select Time Slot</option>');
                    $.each(data.slots, function(i, slot) {
                        timeSlotSelect.append('<option value="' + slot.value + '">' + slot.display + '</option>');
                    });
                } else {
                    timeSlotSelect.append('<option value="">No available slots for ' + data.day + '</option>');
                    if(data.message) {
                        alert(data.message);
                    }
                }
            }).fail(function() {
                timeSlotSelect.prop('disabled', false);
                timeSlotSelect.html('<option value="">Error loading slots. Please try again.</option>');
            });
        }
    });
    
    // Trigger change on page load if both are selected
    if($('#doctor_id').val() && $('#appointment_date').val()) {
        $('#doctor_id').trigger('change');
    }
</script>
@endpush
@endsection