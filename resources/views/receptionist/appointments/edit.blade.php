@extends('layouts.receptionist')

@section('title', 'Edit Appointment')
@section('header', 'Edit Appointment')
@section('subheader', 'Modify appointment details')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('receptionist.appointments.update', $appointment->appointment_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Patient *</label>
                    <select name="patient_id" class="form-control" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->user_id }}" {{ $appointment->patient_id == $patient->user_id ? 'selected' : '' }}>
                                {{ $patient->firstname }} {{ $patient->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Doctor *</label>
                    <select name="doctor_id" class="form-control" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->user_id }}" {{ $appointment->doctor_id == $doctor->user_id ? 'selected' : '' }}>
                                Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Appointment Date *</label>
                    <input type="date" name="appointment_date" class="form-control" value="{{ $appointment->appointment_date }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Time Slot *</label>
                    <input type="time" name="time_slot" class="form-control" value="{{ $appointment->time_slot }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no-show" {{ $appointment->status == 'no-show' ? 'selected' : '' }}>No-Show</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Cancellation Reason</label>
                    <input type="text" name="cancellation_reason" class="form-control" value="{{ $appointment->cancellation_reason }}">
                </div>
            </div>
            <div class="mb-3">
                <label>Reason for Visit</label>
                <textarea name="reason_for_visit" class="form-control" rows="3">{{ $appointment->reason_for_visit }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection