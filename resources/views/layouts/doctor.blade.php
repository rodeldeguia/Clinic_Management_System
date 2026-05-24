<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Panel - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar .brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .brand h3 {
            margin: 0;
            font-size: 1.3rem;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 4px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: #3a6bc5;
            padding-left: 28px;
        }
        .sidebar .nav-link.active {
            background-color: #4a7cd6;
        }
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .topbar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            border: none;
            margin-bottom: 20px;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            text-align: center;
        }
        .stats-card i {
            font-size: 2.5rem;
            color: #2a5298;
        }
        .btn-primary {
            background-color: #2a5298;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1e3c72;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <h3><i class="fas fa-user-md"></i> Doctor Portal</h3>
            <small>Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" href="{{ route('doctor.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}" href="{{ route('doctor.appointments.index') }}">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
            <a class="nav-link {{ request()->routeIs('doctor.patient-care.*') ? 'active' : '' }}" href="{{ route('doctor.patient-care.prescriptions') }}">
                <i class="fas fa-notes-medical"></i> Patient Care
            </a>
            <a class="nav-link {{ request()->routeIs('doctor.schedule.*') ? 'active' : '' }}" href="{{ route('doctor.schedule.index') }}">
                <i class="fas fa-clock"></i> My Schedule
            </a>
            <a class="nav-link {{ request()->routeIs('doctor.feedback') ? 'active' : '' }}" href="{{ route('doctor.feedback') }}">
                <i class="fas fa-star"></i> Feedback
            </a>
        </nav>
    </div>

    <div class="content">
        <div class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">@yield('header')</h4>
                <small class="text-muted">@yield('subheader')</small>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle"></i> Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('password.change') }}"><i class="fas fa-key"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>