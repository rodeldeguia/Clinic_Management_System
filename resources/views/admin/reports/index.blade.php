@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('header', 'Reports & Analytics')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-primary"></i>
                <h5 class="mt-2">Patient Reports</h5>
                <p>View patient history and treatment records</p>
                <a href="{{ route('admin.reports.patients') }}" class="btn btn-primary">Generate</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-md fa-3x text-success"></i>
                <h5 class="mt-2">Doctor Activity</h5>
                <p>Appointments, prescriptions, and feedback</p>
                <a href="{{ route('admin.reports.doctors') }}" class="btn btn-success">Generate</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-info"></i>
                <h5 class="mt-2">Financial Reports</h5>
                <p>Income, outstanding bills, insurance claims</p>
                <a href="{{ route('admin.reports.financial') }}" class="btn btn-info">Generate</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-pills fa-3x text-warning"></i>
                <h5 class="mt-2">Medicine Stock</h5>
                <p>Usage, shortages, expiry alerts</p>
                <a href="{{ route('admin.reports.medicines') }}" class="btn btn-warning">Generate</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-tie fa-3x text-secondary"></i>
                <h5 class="mt-2">Receptionist Activity</h5>
                <p>Registrations and scheduling efficiency</p>
                <a href="{{ route('admin.reports.receptionists') }}" class="btn btn-secondary">Generate</a>
            </div>
        </div>
    </div>
</div>
@endsection