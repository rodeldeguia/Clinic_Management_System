@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users List</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>

    @if(session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th><th>Username</th><th>Name</th><th>Role</th><th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->email_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
