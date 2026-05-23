<!DOCTYPE html>
<html>
<head>
    <title>My Laravel App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('users.index') }}">User Management</a>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
