<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Resident Portal') — SmartSociety</title>
  
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

  <!-- Emergency Alert Top Banner -->
  @php
      $activeAlerts = \App\Models\EmergencyAlert::where('status', 'active')->orderBy('created_at', 'desc')->get();
  @endphp
  @if($activeAlerts->isNotEmpty())
      @foreach($activeAlerts as $alert)
          <div class="alert alert-{{ $alert->severity == 'critical' ? 'danger' : ($alert->severity == 'warning' ? 'warning' : 'info') }} mb-0 text-center fw-bold py-2 rounded-0 shadow-sm border-0 animate-pulse">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> EMERGENCY BROADCAST: {{ $alert->alert_type }} — {{ $alert->description }}
          </div>
      @endforeach
  @endif

  <!-- Sidebar -->
  <aside class="app-sidebar" id="residentSidebar">
    <div class="d-flex align-items-center gap-3 px-2 mb-4">
      <div class="brand-logo-icon">
        <i class="bi bi-building"></i>
      </div>
      <div>
        <h5 class="mb-0 fw-bold font-heading" style="font-size: 1.15rem; color: var(--primary-brand);">SmartSociety</h5>
        <span class="badge badge-neutral-custom" style="font-size: 0.68rem;">RESIDENT PORTAL</span>
      </div>
    </div>

    <nav class="sidebar-nav flex-grow-1">
      <a class="nav-link {{ request()->routeIs('resident.dashboard') ? 'active' : '' }}" href="{{ route('resident.dashboard') }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.profile.*') ? 'active' : '' }}" href="{{ route('resident.profile.index') }}">
        <i class="bi bi-person-vcard"></i>
        <span>Profile & Vehicles</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.bills.*') ? 'active' : '' }}" href="{{ route('resident.bills.index') }}">
        <i class="bi bi-credit-card"></i>
        <span>Bills & Payments</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.visitor-passes.*') ? 'active' : '' }}" href="{{ route('resident.visitor-passes.index') }}">
        <i class="bi bi-qr-code"></i>
        <span>Visitor Gate Passes</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.complaints.*') ? 'active' : '' }}" href="{{ route('resident.complaints.index') }}">
        <i class="bi bi-tools"></i>
        <span>Helpdesk / Complaints</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.amenity-bookings.*') ? 'active' : '' }}" href="{{ route('resident.amenity-bookings.index') }}">
        <i class="bi bi-calendar-event"></i>
        <span>Book Amenities</span>
      </a>
      <a class="nav-link {{ request()->routeIs('resident.notices-polls.*') ? 'active' : '' }}" href="{{ route('resident.notices-polls.index') }}">
        <i class="bi bi-newspaper"></i>
        <span>Notices & Polls</span>
      </a>
    </nav>

    <div class="pt-3 border-top border-secondary border-opacity-25 mt-auto">
      <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(255, 248, 240, 0.5);">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
          <div class="avatar-circle" style="width: 34px; height: 34px; font-size: 0.85rem;">
            {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
          </div>
          <div class="text-truncate">
            <p class="mb-0 fw-semibold text-truncate" style="font-size: 0.85rem; color: var(--dark-text);">{{ auth()->user()->name }}</p>
            <span class="text-muted d-block text-truncate" style="font-size: 0.72rem;">Resident</span>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn btn-sm text-danger p-1" title="Logout">
            <i class="bi bi-box-arrow-right fs-5"></i>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Main Wrapper -->
  <div class="main-wrapper">
    
    <!-- Top Navbar -->
    <header class="glass-panel app-navbar d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm d-lg-none p-0 border-0" type="button" onclick="document.getElementById('residentSidebar').classList.toggle('show')">
          <i class="bi bi-list fs-2" style="color: var(--primary-brand);"></i>
        </button>
        <h4 class="mb-0 font-heading fw-bold">@yield('page-title', 'Resident Dashboard')</h4>
      </div>

      <div class="d-flex align-items-center gap-3">
        @yield('header-actions')
        
        <div class="dropdown">
          <button class="btn p-0 border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar-circle">
              {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
            </div>
            <div class="text-start d-none d-md-block">
              <span class="d-block fw-semibold lh-1" style="font-size: 0.88rem;">{{ auth()->user()->name }}</span>
              <span class="text-muted" style="font-size: 0.72rem;">Resident</span>
            </div>
            <i class="bi bi-chevron-down text-muted small"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end glass-panel border-0 shadow-lg p-2" style="min-width: 200px;">
            <li><h6 class="dropdown-header text-muted small text-uppercase">My Profile</h6></li>
            <li><a class="dropdown-item rounded-2" href="{{ route('resident.profile.index') }}"><i class="bi bi-person-vcard me-2"></i> Account Details</a></li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item rounded-2 text-danger fw-semibold"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Session Alerts -->
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

    <!-- Content -->
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
