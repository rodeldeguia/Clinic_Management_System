@extends('layouts.admin')

@section('title', 'Patient Oversight')
@section('header', 'Patient Oversight')

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.patients.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by name, username, or contact..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Blood Group</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->user_id }}</td>
                    <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
                    <td>{{ $patient->contact_number }}</td>
                    <td>{{ $patient->email_address }}</td>
                    <td>{{ $patient->blood_group ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.patients.show', $patient->user_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.patients.medical-history', $patient->user_id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-notes-medical"></i> Records
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No patients found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $patients->links() }}
    </div>
</div>
@endsection