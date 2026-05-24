@extends('layouts.admin')

@section('title', 'Receptionist Details')
@section('header', 'Receptionist Details')
@section('subheader', $receptionist->firstname . ' ' . $receptionist->lastname)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <table class="table">
                    <tr><th>ID</th><td>{{ $receptionist->user_id }}</td></tr>
                    <tr><th>Full Name</th><td>{{ $receptionist->firstname }} {{ $receptionist->lastname }}</td></tr>
                    <tr><th>Username</th><td>{{ $receptionist->username }}</td></tr>
                    <tr><th>Email</th><td>{{ $receptionist->email_address }}</td></tr>
                    <tr><th>Contact</th><td>{{ $receptionist->contact_number }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($receptionist->gender) }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $receptionist->date_of_birth }}</td></tr>
                    <tr><th>Address</th><td>{{ $receptionist->address }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Work Information</div>
            <div class="card-body">
                <table class="table">
                    <tr><th>Shift Timing</th><td>{{ $receptionist->shift_timing }}</td></tr>
                    <tr><th>Assigned Section</th><td>{{ $receptionist->assigned_section ?? 'Not assigned' }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            <span class="badge {{ $receptionist->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $receptionist->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Registered</th><td>{{ $receptionist->created_at }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Recent Activity</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Action</th><th>IP Address</th><th>Timestamp</th></tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs ?? [] as $log)
                        <tr>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->timestamp }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No activity logs found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.receptionists.edit', $receptionist->user_id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.receptionists.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection