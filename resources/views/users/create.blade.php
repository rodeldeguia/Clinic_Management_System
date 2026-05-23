@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New User</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="receptionist">Receptionist</option>
            <option value="patient">Patient</option>
            <option value="medical_store">Medical Store</option>
        </select>
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="text" name="contact_number" placeholder="Contact Number" required>
        <input type="email" name="email_address" placeholder="Email">

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
