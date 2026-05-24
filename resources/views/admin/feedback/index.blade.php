@extends('layouts.admin')

@section('title', 'Feedback Management')
@section('header', 'Patient Feedback')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Patient</th><th>Doctor</th><th>Rating</th><th>Comments</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($feedback as $item)
                <tr>
                    <td>{{ $item->patient->firstname ?? 'N/A' }} {{ $item->patient->lastname ?? '' }}</td>
                    <td>{{ $item->doctor->firstname ?? 'N/A' }} {{ $item->doctor->lastname ?? '' }}</td>
                    <td>
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </td>
                    <td>{{ Str::limit($item->comments, 50) }}</td>
                    <td>{{ $item->submitted_at }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $item->feedback_id }}">
                            View Response
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No feedback found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $feedback->links() }}
    </div>
</div>

@foreach($feedback as $item)
<div class="modal fade" id="feedbackModal{{ $item->feedback_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedback Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Patient:</strong> {{ $item->patient->firstname ?? 'N/A' }} {{ $item->patient->lastname ?? '' }}</p>
                <p><strong>Doctor:</strong> {{ $item->doctor->firstname ?? 'N/A' }} {{ $item->doctor->lastname ?? '' }}</p>
                <p><strong>Rating:</strong> {{ $item->rating }}/5</p>
                <p><strong>Comments:</strong> {{ $item->comments }}</p>
                <hr>
                <form action="{{ route('admin.feedback.respond', $item->feedback_id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Admin Response</label>
                        <textarea name="response" class="form-control" rows="3" placeholder="Type your response here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Response</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection