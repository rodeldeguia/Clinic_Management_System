@extends('layouts.patient')

@section('title', 'Reschedule Appointment')
@section('header', 'Reschedule Appointment')
@section('subheader', 'Change your appointment date or time')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('patient.appointments.update', $appointment->appointment_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Doctor</label>
                    <input type="text" class="form-control" value="Dr. {{ $appointment->doctor->firstname ?? '' }} {{ $appointment->doctor->lastname ?? '' }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label>New Appointment Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" 
                           min="{{ date('Y-m-d') }}" value="{{ old('appointment_date', $appointment->appointment_date) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>New Time Slot *</label>
                    <select name="time_slot" id="time_slot" class="form-control" required>
                        <option value="{{ $appointment->time_slot }}" selected>{{ $appointment->time_slot }}</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Reason for Visit</label>
                    <textarea name="reason_for_visit" class="form-control" rows="3">{{ old('reason_for_visit', $appointment->reason_for_visit) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#appointment_date').change(function() {
        var doctorId = '{{ $appointment->doctor_id }}';
        var date = $(this).val();
        
        if(doctorId && date) {
            $.get('{{ route("patient.appointments.available-slots", "") }}/' + doctorId, {
                date: date
            }, function(data) {
                var select = $('#time_slot');
                select.empty();
                if(data.slots && data.slots.length > 0) {
                    select.append('<option value="">Select Time Slot</option>');
                    $.each(data.slots, function(i, slot) {
                        select.append('<option value="' + slot + '">' + slot + '</option>');
                    });
                } else {
                    select.append('<option value="">No available slots</option>');
                }
            });
        }
    });
</script>
@endpush
@endsection