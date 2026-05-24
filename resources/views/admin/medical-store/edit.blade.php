@extends('layouts.admin')

@section('title', 'Edit Medical Store Staff')
@section('header', 'Edit Medical Store Staff')
@section('subheader', 'Update staff information')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.medical-store.update', $staff->user_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $staff->firstname) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $staff->lastname) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control" value="{{ $staff->username }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label>New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password to change">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address', $staff->email_address) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $staff->contact_number) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Store Role *</label>
                    <select name="store_role" class="form-control" required>
                        <option value="Pharmacist" {{ $staff->store_role == 'Pharmacist' ? 'selected' : '' }}>Pharmacist</option>
                        <option value="Pharmacy Assistant" {{ $staff->store_role == 'Pharmacy Assistant' ? 'selected' : '' }}>Pharmacy Assistant</option>
                        <option value="Store Manager" {{ $staff->store_role == 'Store Manager' ? 'selected' : '' }}>Store Manager</option>
                        <option value="Cashier" {{ $staff->store_role == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $staff->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$staff->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $staff->address) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Staff</button>
            <a href="{{ route('admin.medical-store.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection