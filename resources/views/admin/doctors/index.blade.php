@extends('layouts.admin')

@section('title', 'Doctor Management')
@section('header', 'Doctor Management')
@section('subheader', 'Manage all doctors in the system')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Specialization</th><th>Contact</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->user_id }}</td>
                    <td>Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}</td>
                    <td>{{ $doctor->specialization }}</td>
                    <td>{{ $doctor->contact_number }}</td>
                    <td>
                        <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.doctors.show', $doctor->user_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.doctors.edit', $doctor->user_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($doctor->is_active)
                        <form action="{{ route('admin.doctors.deactivate', $doctor->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Deactivate this doctor?')">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.doctors.reactivate', $doctor->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Reactivate this doctor?')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No doctors found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $doctors->links() }}
    </div>
</div>
@endsection