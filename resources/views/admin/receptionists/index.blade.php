@extends('layouts.admin')

@section('title', 'Receptionist Management')
@section('header', 'Receptionist Management')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.receptionists.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Receptionist
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Contact</th><th>Shift</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($receptionists as $receptionist)
                <tr>
                    <td>{{ $receptionist->user_id }}</td>
                    <td>{{ $receptionist->firstname }} {{ $receptionist->lastname }}</td>
                    <td>{{ $receptionist->contact_number }}</td>
                    <td>{{ $receptionist->shift_timing }}</td>
                    <td>
                        <span class="badge {{ $receptionist->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $receptionist->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.receptionists.show', $receptionist->user_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.receptionists.edit', $receptionist->user_id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($receptionist->is_active)
                        <form action="{{ route('admin.receptionists.suspend', $receptionist->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Suspend this receptionist?')">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No receptionists found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $receptionists->links() }}
    </div>
</div>
@endsection