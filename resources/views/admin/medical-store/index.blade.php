@extends('layouts.admin')

@section('title', 'Medical Store Management')
@section('header', 'Medical Store Management')
@section('subheader', 'Manage pharmacy and medical store staff')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.medical-store.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Medical Store Staff
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicalStaff as $staff)
                <tr>
                    <td>{{ $staff->user_id }}</td>
                    <td>{{ $staff->firstname }} {{ $staff->lastname }}</td>
                    <td>{{ $staff->username }}</td>
                    <td>{{ $staff->contact_number }}</td>
                    <td>{{ $staff->email_address }}</td>
                    <td>{{ $staff->store_role ?? 'Pharmacist' }}</td>
                    <td>
                        <span class="badge {{ $staff->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $staff->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <td>
                        <a href="{{ route('admin.medical-store.edit', $staff->user_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.medical-store.toggle-status', $staff->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $staff->is_active ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas {{ $staff->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                {{ $staff->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.medical-store.destroy', $staff->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff member?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No medical store staff found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $medicalStaff->links() }}
    </div>
</div>
@endsection