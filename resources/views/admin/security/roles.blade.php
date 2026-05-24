@extends('layouts.admin')

@section('title', 'Security & Access Control')
@section('header', 'Role Management')

@section('content')
<div class="row">
    @foreach($roles as $role)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ ucfirst($role) }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.security.permissions.update', $role) }}" method="POST">
                    @csrf
                    @foreach($permissions[$role] ?? [] as $permission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="{{ $role }}_{{ $permission }}" checked>
                        <label class="form-check-label" for="{{ $role }}_{{ $permission }}">
                            {{ ucfirst(str_replace('_', ' ', $permission)) }}
                        </label>
                    </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5>Login Activity</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.security.login-activity') }}" class="btn btn-info">View Login Logs</a>
        <a href="{{ route('admin.security.audit-logs') }}" class="btn btn-secondary">View Audit Logs</a>
    </div>
</div>
@endsection