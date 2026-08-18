<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $roleLabel }} Dashboard - SmartSociety</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary px-4">
        <span class="navbar-brand mb-0 h1">SmartSociety</span>
        <form method="POST" action="{{ route('logout') }}" class="d-flex">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3>Welcome, {{ auth()->user()->name }}</h3>
                <p class="text-muted mb-0">Role: {{ $roleLabel }}</p>
                <hr>
                <p>This is a placeholder for the {{ $roleLabel }} dashboard. Real features will be built in upcoming steps.</p>
            </div>
        </div>
    </div>

</body>
</html>