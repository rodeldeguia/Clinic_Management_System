@extends('layouts.patient')

@section('title', 'Edit Feedback')
@section('header', 'Edit Feedback')
@section('subheader', 'Update your feedback')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('patient.feedback.update', $feedback->feedback_id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3 text-center">
                <label class="form-label fw-bold">Rating</label>
                <div class="rating">
                    @for($i=5; $i>=1; $i--)
                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ $feedback->rating == $i ? 'checked' : '' }} required>
                        <label for="star{{ $i }}" class="star-label">★</label>
                    @endfor
                </div>
            </div>
            
            <div class="mb-3">
                <label>Comments</label>
                <textarea name="comments" class="form-control" rows="5" required>{{ $feedback->comments }}</textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_public" value="1" class="form-check-input" id="is_public" {{ $feedback->is_public ? 'checked' : '' }}>
                <label class="form-check-label" for="is_public">
                    Make this feedback public
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Feedback</button>
            <a href="{{ route('patient.feedback.my-feedback') }}" class="btn btn-secondary">Cancel</a>
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