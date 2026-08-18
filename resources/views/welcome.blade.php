<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartSociety — Smart Housing Ecosystem</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Gemini Theme CSS -->
  <link rel="stylesheet" href="{{ asset('css/gemini-theme.css') }}">
</head>
<body>

  <!-- Ambient Blobs -->
  <div class="bg-blob blob-top-right"></div>
  <div class="bg-blob blob-bottom-left"></div>

  <!-- Header Navigation -->
  <header class="glass-panel mx-3 my-3 p-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <div class="brand-logo-icon">
        <i class="bi bi-building text-white"></i>
      </div>
      <div>
        <h4 class="mb-0 font-heading fw-bold">SmartSociety</h4>
        <span class="badge badge-neutral-custom" style="font-size: 0.7rem;">COMMUNITY MANAGEMENT ECOSYSTEM</span>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('directory.guidelines') }}" class="btn btn-secondary-custom btn-sm">
        <i class="bi bi-telephone-fill me-1 text-danger"></i> Emergency Directory
      </a>
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-primary-custom btn-sm">
          <i class="bi bi-grid-fill me-1"></i> My Dashboard
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-primary-custom btn-sm">
          <i class="bi bi-box-arrow-in-right me-1"></i> Member Sign In
        </a>
      @endauth
    </div>
  </header>

  <!-- Main Hero Section -->
  <div class="container py-5">
    <div class="row align-items-center mb-5">
      <div class="col-lg-7 mb-4 mb-lg-0">
        <span class="badge badge-warning-custom px-3 py-2 fs-6 mb-3">
          <i class="bi bi-stars me-1"></i> Next-Gen Smart Housing Platform
        </span>
        <h1 class="font-heading display-4 fw-bold mb-3" style="color: var(--primary-brand); line-height: 1.15;">
          Modern Housing Society & Estate Management
        </h1>
        <p class="text-muted leading-relaxed fs-5 mb-4" style="max-width: 600px;">
          Seamless digital governance for residents, administrators, gate security officers, and maintenance staff. Automated billing, digital gate passes, and real-time helpdesk ticketing.
        </p>

        <div class="d-flex flex-wrap gap-3">
          @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary-custom btn-lg">
              <i class="bi bi-speedometer2 me-2"></i> Go to My Portal Dashboard
            </a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg">
              <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Portal
            </a>
          @endauth
          <a href="{{ route('directory.guidelines') }}" class="btn btn-secondary-custom btn-lg">
            <i class="bi bi-book me-2"></i> View Society Guidelines
          </a>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="glass-panel p-4 text-center" style="background: rgba(255, 248, 240, 0.85);">
          <div class="metric-icon-box mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
            <i class="bi bi-shield-check"></i>
          </div>
          <h4 class="font-heading fw-bold mb-2">SmartSociety Portals</h4>
          <p class="text-muted small mb-4">Select your operational portal or log in to access account details.</p>

          <div class="d-grid gap-2">
            <a href="{{ route('login') }}" class="glass-panel p-3 text-start text-decoration-none d-flex align-items-center justify-content-between hover-lift">
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle" style="width: 36px; height: 36px;">
                  <i class="bi bi-houses-fill"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold" style="color: var(--dark-text);">Resident Portal</h6>
                  <small class="text-muted">Bills, Gate Passes, Complaints</small>
                </div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('login') }}" class="glass-panel p-3 text-start text-decoration-none d-flex align-items-center justify-content-between hover-lift">
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle" style="width: 36px; height: 36px; background: var(--primary-brand); color: #fff;">
                  <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold" style="color: var(--dark-text);">Admin Portal</h6>
                  <small class="text-muted">Occupancy, Finance, Alerts</small>
                </div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('login') }}" class="glass-panel p-3 text-start text-decoration-none d-flex align-items-center justify-content-between hover-lift">
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle" style="width: 36px; height: 36px; background: var(--accent-brown); color: #fff;">
                  <i class="bi bi-qr-code-scan"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold" style="color: var(--dark-text);">Security Gate Station</h6>
                  <small class="text-muted">Visitor Clearance & Log</small>
                </div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Feature Cards Grid -->
    <div class="row g-4 mt-2">
      <div class="col-md-3">
        <div class="glass-panel p-4 h-100">
          <div class="metric-icon-box mb-3" style="background: rgba(39, 174, 96, 0.15); color: #1E8449;">
            <i class="bi bi-qr-code"></i>
          </div>
          <h5 class="font-heading fw-bold mb-2">Digital Gate Passes</h5>
          <p class="text-muted small mb-0">Pre-authorize guests & vendors with instant 6-digit numeric passcodes and QR verification.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="glass-panel p-4 h-100">
          <div class="metric-icon-box mb-3" style="background: rgba(41, 128, 185, 0.15); color: #1F618D;">
            <i class="bi bi-receipt"></i>
          </div>
          <h5 class="font-heading fw-bold mb-2">Automated Billing</h5>
          <p class="text-muted small mb-0">Monthly maintenance calculation, late fee tracking, and online digital checkout.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="glass-panel p-4 h-100">
          <div class="metric-icon-box mb-3" style="background: rgba(211, 84, 0, 0.15); color: #B94A00;">
            <i class="bi bi-tools"></i>
          </div>
          <h5 class="font-heading fw-bold mb-2">Helpdesk & SLA</h5>
          <p class="text-muted small mb-0">Track maintenance work orders, assign technicians, and monitor resolution timelines.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="glass-panel p-4 h-100">
          <div class="metric-icon-box mb-3" style="background: rgba(192, 57, 43, 0.15); color: var(--danger-color);">
            <i class="bi bi-broadcast"></i>
          </div>
          <h5 class="font-heading fw-bold mb-2">Emergency Alerts</h5>
          <p class="text-muted small mb-0">Instant broadcasting for security alerts, utility shutdowns, and emergency warnings.</p>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center py-4 border-top border-secondary border-opacity-25 mt-5">
    <p class="text-muted small mb-0">SmartSociety Management Ecosystem &copy; {{ date('Y') }}. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
