@extends('layouts.doctor')

@section('title', 'My Schedule')
@section('header', 'Schedule Management')
@section('subheader', 'View and manage your availability')

@section('content')
<div class="row">
    <!-- Current Schedule Display -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calendar-week"></i> Current Weekly Schedule
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Slot Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            @endphp
                            @foreach($days as $day)
                            @php
                                $schedule = $schedules->where('day_of_week', $day)->first();
                            @endphp
                            <tr>
                                <td class="fw-bold">{{ $day }}</td>
                                <td>{{ $schedule->start_time ?? '--:--' }}</td>
                                <td>{{ $schedule->end_time ?? '--:--' }}</td>
                                <td>{{ $schedule->slot_duration ?? '-' }} minutes</td>
                                <td>
                                    @if($schedule && $schedule->is_available)
                                        <span class="badge bg-success">Available</span>
                                    @elseif($schedule && !$schedule->is_available)
                                        <span class="badge bg-danger">Unavailable</span>
                                    @else
                                        <span class="badge bg-secondary">Not Set</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Form -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-edit"></i> Edit Schedule
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('doctor.schedule.update-availability') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Slot Duration (mins)</th>
                                    <th>Available</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $index => $day)
                                @php
                                    $schedule = $schedules->where('day_of_week', $day)->first();
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $day }}</td>
                                    <td>
                                        <input type="time" 
                                               name="schedule[{{ $day }}][start_time]" 
                                               class="form-control form-control-sm" 
                                               value="{{ old("schedule.{$day}.start_time", $schedule->start_time ?? '09:00') }}">
                                    </td>
                                    <td>
                                        <input type="time" 
                                               name="schedule[{{ $day }}][end_time]" 
                                               class="form-control form-control-sm" 
                                               value="{{ old("schedule.{$day}.end_time", $schedule->end_time ?? '17:00') }}">
                                    </td>
                                    <td>
                                        <select name="schedule[{{ $day }}][slot_duration]" 
                                                class="form-select form-select-sm">
                                            <option value="15" {{ (old("schedule.{$day}.slot_duration", $schedule->slot_duration ?? 30) == 15) ? 'selected' : '' }}>15 minutes</option>
                                            <option value="30" {{ (old("schedule.{$day}.slot_duration", $schedule->slot_duration ?? 30) == 30) ? 'selected' : '' }}>30 minutes</option>
                                            <option value="45" {{ (old("schedule.{$day}.slot_duration", $schedule->slot_duration ?? 30) == 45) ? 'selected' : '' }}>45 minutes</option>
                                            <option value="60" {{ (old("schedule.{$day}.slot_duration", $schedule->slot_duration ?? 30) == 60) ? 'selected' : '' }}>60 minutes</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="schedule[{{ $day }}][is_available]" 
                                                   value="1" 
                                                   class="form-check-input"
                                                   id="available_{{ $day }}"
                                                   {{ (old("schedule.{$day}.is_available", $schedule->is_available ?? false)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_{{ $day }}">
                                                <i class="fas fa-check-circle text-success"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-time" data-day="{{ $day }}">
                                            <i class="fas fa-copy"></i> Copy from previous
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-info" id="applyToAll">
                                <i class="fas fa-calendar-week"></i> Apply Monday's Schedule to All Weekdays
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save All Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Copy time from previous day
    document.querySelectorAll('.copy-time').forEach(button => {
        button.addEventListener('click', function() {
            const currentDay = this.dataset.day;
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            const currentIndex = days.indexOf(currentDay);
            
            if (currentIndex > 0) {
                const previousDay = days[currentIndex - 1];
                
                // Get previous day values
                const prevStart = document.querySelector(`input[name="schedule[${previousDay}][start_time]"]`).value;
                const prevEnd = document.querySelector(`input[name="schedule[${previousDay}][end_time]"]`).value;
                const prevDuration = document.querySelector(`select[name="schedule[${previousDay}][slot_duration]"]`).value;
                const prevAvailable = document.querySelector(`input[name="schedule[${previousDay}][is_available]"]`).checked;
                
                // Apply to current day
                document.querySelector(`input[name="schedule[${currentDay}][start_time]"]`).value = prevStart;
                document.querySelector(`input[name="schedule[${currentDay}][end_time]"]`).value = prevEnd;
                document.querySelector(`select[name="schedule[${currentDay}][slot_duration]"]`).value = prevDuration;
                document.querySelector(`input[name="schedule[${currentDay}][is_available]"]`).checked = prevAvailable;
                
                // Show feedback
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-copy"></i> Copy from previous';
                }, 1500);
            } else {
                alert('No previous day to copy from');
            }
        });
    });
    
    // Apply Monday's schedule to all weekdays (Mon-Fri)
    document.getElementById('applyToAll').addEventListener('click', function() {
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        const mondayStart = document.querySelector(`input[name="schedule[Monday][start_time]"]`).value;
        const mondayEnd = document.querySelector(`input[name="schedule[Monday][end_time]"]`).value;
        const mondayDuration = document.querySelector(`select[name="schedule[Monday][slot_duration]"]`).value;
        const mondayAvailable = document.querySelector(`input[name="schedule[Monday][is_available]"]`).checked;
        
        for (let i = 1; i < days.length; i++) {
            document.querySelector(`input[name="schedule[${days[i]}][start_time]"]`).value = mondayStart;
            document.querySelector(`input[name="schedule[${days[i]}][end_time]"]`).value = mondayEnd;
            document.querySelector(`select[name="schedule[${days[i]}][slot_duration]"]`).value = mondayDuration;
            document.querySelector(`input[name="schedule[${days[i]}][is_available]"]`).checked = mondayAvailable;
        }
        
        alert('Monday schedule applied to all weekdays (Mon-Fri)!');
    });
</script>
@endpush
@endsection