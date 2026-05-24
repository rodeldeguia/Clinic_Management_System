@extends('layouts.doctor')

@section('title', 'Patient Feedback')
@section('header', 'Feedback & Ratings')
@section('subheader', 'See what patients say about you')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6 text-center">
                <h1 class="display-1">{{ number_format($average_rating, 1) }}</h1>
                <p>Average Rating</p>
                @for($i=1; $i<=5; $i++)
                    <i class="fas fa-star {{ $i <= round($average_rating) ? 'text-warning' : 'text-muted' }} fa-2x"></i>
                @endfor
                <p class="mt-2">Based on {{ $total_feedback }} reviews</p>
            </div>
            <div class="col-md-6">
                <h5>Rating Distribution</h5>
                @for($i=5; $i>=1; $i--)
                @php
                    $count = $rating_counts[$i] ?? 0;
                    $percentage = $total_feedback > 0 ? ($count / $total_feedback) * 100 : 0;
                @endphp
                <div class="mb-2">
                    <span>{{ $i }} stars</span>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $percentage }}%">
                            {{ $count }}
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
        
        <h5>Recent Feedback</h5>
        <table class="table table-bordered">
            <thead>
                <tr><th>Patient</th><th>Rating</th><th>Comment</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($feedback as $item)
                <tr>
                    <td>{{ $item->patient->firstname ?? 'N/A' }} {{ $item->patient->lastname ?? '' }}</td>
                    <td>
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </td>
                    <td>{{ $item->comments }}</td>
                    <td>{{ $item->submitted_at }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No feedback yet</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $feedback->links() }}
    </div>
</div>
@endsection