<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emergency Directory & Society Guidelines — SmartSociety</title>
  
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

  <!-- Header Navbar -->
  <header class="glass-panel mx-3 my-3 p-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <div class="brand-logo-icon">
        <i class="bi bi-building text-white"></i>
      </div>
      <div>
        <h4 class="mb-0 font-heading fw-bold">SmartSociety Information Center</h4>
        <span class="badge badge-neutral-custom" style="font-size: 0.7rem;">COMMUNITY RESOURCES</span>
      </div>
    </div>

    <div>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary-custom"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
    </div>
  </header>

  <div class="container py-4">
    <div class="text-center mb-5">
      <h2 class="font-heading fw-bold mb-2" style="color: var(--primary-brand);">Emergency Directory & Community Guidelines</h2>
      <p class="text-muted">Critical emergency contact hotlines & official society operational guidelines.</p>
    </div>

    <!-- Emergency Contact Directory -->
    <h4 class="font-heading fw-bold mb-4 text-danger"><i class="bi bi-telephone-fill me-2"></i> Emergency Contact Directory</h4>
    <div class="row g-4 mb-5">
      @foreach($emergencyContacts as $contact)
        <div class="col-md-3">
          <div class="glass-panel p-4 text-center h-100">
            <i class="bi {{ $contact['icon'] }} fs-1 mb-3 text-{{ $contact['color'] }}"></i>
            <h6 class="fw-bold font-heading mb-1">{{ $contact['service'] }}</h6>
            <h5 class="fw-bold text-{{ $contact['color'] }} mb-0">{{ $contact['phone'] }}</h5>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Society Guidelines -->
    <h4 class="font-heading fw-bold mb-4" style="color: var(--primary-brand);"><i class="bi bi-book-fill me-2"></i> Society Rules & Guidelines</h4>
    <div class="row g-4">
      @foreach($guidelines as $title => $rule)
        <div class="col-md-6">
          <div class="glass-panel p-4 h-100" style="border-left: 4px solid var(--primary-brand);">
            <h5 class="font-heading fw-bold mb-2" style="color: var(--primary-brand);">{{ $title }}</h5>
            <p class="text-muted mb-0 small" style="line-height: 1.6;">{{ $rule }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
