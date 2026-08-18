<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Maintenance Queue') — SmartSociety Staff</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Gemini Theme CSS -->
  <link rel="stylesheet" href="{{ asset('css/gemini-theme.css') }}">
  @stack('styles')
</head>
<body>

  <!-- Ambient Blobs -->
  <div class="bg-blob blob-top-right"></div>
  <div class="bg-blob blob-bottom-left"></div>

  <!-- Top Navbar -->
  <header class="glass-panel mx-3 my-3 p-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <div class="brand-logo-icon">
        <i class="bi bi-tools text-white"></i>
      </div>
      <div>
        <h4 class="mb-0 font-heading fw-bold">SmartSociety Maintenance Portal</h4>
        <span class="badge badge-info-custom" style="font-size: 0.7rem;"><i class="bi bi-wrench me-1"></i> WORK ORDERS QUEUE</span>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div class="avatar-circle">
        {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
      </div>
      <div class="text-start d-none d-md-block">
        <span class="d-block fw-semibold lh-1" style="font-size: 0.88rem;">{{ auth()->user()->name }}</span>
        <span class="text-muted" style="font-size: 0.72rem;">Maintenance Staff</span>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="btn btn-sm btn-secondary-custom"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
      </form>
    </div>
  </header>

  <div class="container py-4">
    @if(session('success'))
      <div class="alert alert-success glass-panel alert-dismissible fade show border-0 mb-4 text-success fw-semibold" role="alert" style="background: rgba(39, 174, 96, 0.15);">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger glass-panel alert-dismissible fade show border-0 mb-4 text-danger fw-semibold" role="alert" style="background: rgba(192, 57, 43, 0.15);">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
