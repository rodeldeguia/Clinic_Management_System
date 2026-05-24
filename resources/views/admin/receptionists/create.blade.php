@extends('layouts.admin')

@section('title', 'Add Receptionist')
@section('header', 'Add New Receptionist')
@section('subheader', 'Create a new receptionist account')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.receptionists.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Username *</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email_address" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Shift Timing *</label>
                    <input type="text" name="shift_timing" class="form-control" placeholder="e.g., 7:00 AM - 3:00 PM" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Assigned Section</label>
                    <input type="text" name="assigned_section" class="form-control" placeholder="e.g., Front Desk, Records, Billing">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Receptionist</button>
            <a href="{{ route('admin.receptionists.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection