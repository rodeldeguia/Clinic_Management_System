@extends('layouts.receptionist')

@section('title', 'Patient Records')
@section('header', 'Patient Records')
@section('subheader', 'View and manage patient information')

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="row">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by name, ID, or contact..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Contact</th><th>Last Visit</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->user_id }}</td>
                    <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
                    <td>{{ $patient->contact_number }}</td>
                    <td>
                        @php
                            $lastAppointment = $patient->patientAppointments->sortByDesc('appointment_date')->first();
                        @endphp
                        {{ $lastAppointment->appointment_date ?? 'Never' }}
                    </td>
                    <td>
                        <a href="{{ route('receptionist.records.patients.show', $patient->user_id) }}" class="btn btn-sm btn-info">View Records</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No patients found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $patients->links() }}
    </div>
</div>
@endsection