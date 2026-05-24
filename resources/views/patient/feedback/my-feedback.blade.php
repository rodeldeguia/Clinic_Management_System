@extends('layouts.patient')

@section('title', 'My Feedback')
@section('header', 'My Feedback')
@section('subheader', 'Reviews you have given')

@section('content')
<div class="card">
    <div class="card-body">
        @forelse($feedback as $item)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Dr. {{ $item->appointment->doctor->firstname ?? 'N/A' }} {{ $item->appointment->doctor->lastname ?? '' }}</h5>
                        <p class="mb-1">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </p>
                        <p class="text-muted small">{{ $item->submitted_at }}</p>
                    </div>
                    <a href="{{ route('patient.feedback.edit', $item->feedback_id) }}" class="btn btn-sm btn-warning">Edit</a>
                </div>
                <hr>
                <p>{{ $item->comments }}</p>
                @if($item->is_public)
                    <span class="badge bg-info">Public</span>
                @else
                    <span class="badge bg-secondary">Private</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center">No feedback submitted yet.</p>
        @endforelse
        {{ $feedback->links() }}
    </div>
</div>
@endsection