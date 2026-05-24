@extends('layouts.admin')

@section('title', 'Add Medical Store Staff')
@section('header', 'Add Medical Store Staff')
@section('subheader', 'Create new pharmacy staff account')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.medical-store.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Username *</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email_address" class="form-control" value="{{ old('email_address') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Store Role *</label>
                    <select name="store_role" class="form-control" required>
                        <option value="Pharmacist">Pharmacist</option>
                        <option value="Pharmacy Assistant">Pharmacy Assistant</option>
                        <option value="Store Manager">Store Manager</option>
                        <option value="Cashier">Cashier</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Staff Account</button>
            <a href="{{ route('admin.medical-store.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection