@extends('layouts.admin')

@section('title', 'Add Doctor')
@section('header', 'Add New Doctor')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.doctors.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" required>  <!-- Changed -->
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" required>   <!-- Changed -->
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
                    <input type="email" name="email_address" class="form-control" required>  <!-- Changed -->
                </div>
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Specialization *</label>
                    <input type="text" name="specialization" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Qualification / License Number *</label>
                    <input type="text" name="qualification" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Shift Timing</label>
                    <input type="text" name="shift_timing" class="form-control" placeholder="e.g., 9:00 AM - 5:00 PM">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Doctor</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection