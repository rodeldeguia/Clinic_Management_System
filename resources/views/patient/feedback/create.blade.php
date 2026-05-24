@extends('layouts.patient')

@section('title', 'Give Feedback')
@section('header', 'Patient Feedback')
@section('subheader', 'Share your experience with Dr. ' . ($appointment->doctor->firstname ?? '') . ' ' . ($appointment->doctor->lastname ?? ''))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            Appointment Date: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}
        </div>

        <form method="POST" action="{{ route('patient.feedback.store') }}">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
            
            <div class="mb-3 text-center">
                <label class="form-label fw-bold">How would you rate your experience?</label>
                <div class="rating">
                    @for($i=5; $i>=1; $i--)
                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                        <label for="star{{ $i }}" class="star-label">★</label>
                    @endfor
                </div>
                @error('rating') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-3">
                <label>Your Comments</label>
                <textarea name="comments" class="form-control" rows="5" required placeholder="Please share your feedback about the doctor, clinic, and overall experience...">{{ old('comments') }}</textarea>
                @error('comments') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_public" value="1" class="form-check-input" id="is_public">
                <label class="form-check-label" for="is_public">
                    Allow clinic to share my feedback publicly
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 10px;
    }
    .rating input {
        display: none;
    }
    .star-label {
        font-size: 40px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
</style>
@endsection