@extends('layouts.doctor')

@section('title', 'My Availability')
@section('header', 'My Availability for Patients')
@section('subheader', 'Patients can book appointments during these times')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-clock"></i> Available Time Slots
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($schedules as $schedule)
                @if($schedule->is_available)
                <div class="col-md-6 mb-3">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <strong>{{ $schedule->day_of_week }}</strong>
                        </div>
                        <div class="card-body">
                            @php
                                $slots = [];
                                $start = strtotime($schedule->start_time);
                                $end = strtotime($schedule->end_time);
                                $duration = $schedule->slot_duration * 60;
                                
                                for ($time = $start; $time < $end; $time += $duration) {
                                    $slots[] = date('g:i A', $time);
                                }
                            @endphp
                            
                            <p><strong>Hours:</strong> {{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}</p>
                            <p><strong>Slot Duration:</strong> {{ $schedule->slot_duration }} minutes</p>
                            <p><strong>Available Slots:</strong></p>
                            <div class="d-flex flex-wrap">
                                @foreach($slots as $slot)
                                    <span class="badge bg-info m-1 p-2">{{ $slot }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No schedule available. Please set your availability.</div>
                </div>
            @endforelse
        </div>
        <a href="{{ route('doctor.schedule.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-edit"></i> Edit My Schedule
        </a>
    </div>
</div>
@endsection