@extends('layouts.admin')

@section('title', 'Edit Receptionist')
@section('header', 'Edit Receptionist')
@section('subheader', 'Update receptionist information')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.receptionists.update', $receptionist->user_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $receptionist->firstname) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $receptionist->lastname) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="{{ $receptionist->username }}">
                    <small class="text-muted">Username cannot be changed</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address', $receptionist->email_address) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $receptionist->contact_number) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Shift Timing *</label>
                    <input type="text" name="shift_timing" class="form-control" value="{{ old('shift_timing', $receptionist->shift_timing) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Assigned Section</label>
                    <input type="text" name="assigned_section" class="form-control" value="{{ old('assigned_section', $receptionist->assigned_section) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male" {{ $receptionist->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $receptionist->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $receptionist->date_of_birth) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $receptionist->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$receptionist->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $receptionist->address) }}</textarea>
            </div>
            <div class="mb-3">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control" placeholder="Enter new password to change">
            </div>
            <button type="submit" class="btn btn-primary">Update Receptionist</button>
            <a href="{{ route('admin.receptionists.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection