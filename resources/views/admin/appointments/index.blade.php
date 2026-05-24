@extends('layouts.admin')

@section('title', 'Appointment Oversight')
@section('header', 'Appointment Oversight')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="btn-group">
            <a href="{{ route('admin.appointments.daily') }}" class="btn btn-outline-primary">Daily</a>
            <a href="{{ route('admin.appointments.weekly') }}" class="btn btn-outline-primary">Weekly</a>
            <a href="{{ route('admin.appointments.monthly') }}" class="btn btn-outline-primary">Monthly</a>
            <a href="{{ route('admin.appointments.statistics') }}" class="btn btn-outline-info">Statistics</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_id }}</td>
                    <td>{{ $appointment->patient->firstname ?? 'N/A' }} {{ $appointment->patient->lastname ?? '' }}</td>
                    <td>{{ $appointment->doctor->firstname ?? 'N/A' }} {{ $appointment->doctor->lastname ?? '' }}</td>
                    <td>{{ $appointment->appointment_date }}</td>
                    <td>{{ $appointment->time_slot }}</td>
                    <td>
                        <select class="form-select form-select-sm status-select" data-id="{{ $appointment->appointment_id }}">
                            <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no-show" {{ $appointment->status == 'no-show' ? 'selected' : '' }}>No-Show</option>
                        </select>
                    </td>
                    <td>
                        <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No appointments found</td></tr>
                @endforelse
            </tbody>
        \dativo
        {{ $appointments->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.value;
            fetch(`/admin/appointments/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            }).then(response => {
                if(response.ok) location.reload();
            });
        });
    });
</script>
@endpush
@endsection