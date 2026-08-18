<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — SmartSociety Management System</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Gemini Theme CSS -->
  <link rel="stylesheet" href="{{ asset('css/gemini-theme.css') }}">
  
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
    .login-wrapper {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 920px;
      background: rgba(255, 248, 240, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(220, 207, 193, 0.8);
      border-radius: 1.5rem;
      box-shadow: 0 25px 50px rgba(74, 63, 53, 0.12);
      overflow: hidden;
    }
    .side-banner {
      background: linear-gradient(135deg, var(--primary-brand) 0%, #4A3F35 100%);
      color: #fff;
      padding: 3rem 2.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      min-height: 480px;
    }
  </style>
</head>
<body>

  <!-- Ambient Blobs -->
  <div class="bg-blob blob-top-right"></div>
  <div class="bg-blob blob-bottom-left"></div>

  <div class="login-wrapper">
    <div class="row g-0">
      
      <!-- Side Visual Banner -->
      <div class="col-lg-5 d-none d-lg-block">
        <div class="side-banner">
          <div>
            <div class="brand-logo-icon mb-4" style="background: var(--accent-brown);">
              <i class="bi bi-building fs-3 text-white"></i>
            </div>
            <h2 class="text-white fw-bold font-heading mb-3">SmartSociety</h2>
            <p class="text-white-50 leading-relaxed mb-0" style="font-size: 0.95rem;">
              Welcome to the next generation housing society management ecosystem. Seamless, secure, and intuitive.
            </p>
          </div>

          <div class="pt-4 border-top border-light border-opacity-10">
            <div class="d-flex align-items-center gap-3 text-white-50" style="font-size: 0.85rem;">
              <div><i class="bi bi-shield-check text-warning fs-4 me-2"></i> Encrypted Access</div>
              <div><i class="bi bi-lightning-charge text-warning fs-4 me-2"></i> Real-time Sync</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Login Form -->
      <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center">
        
        <div class="mb-4">
          <div class="d-flex align-items-center gap-2 d-lg-none mb-3">
            <div class="brand-logo-icon">
              <i class="bi bi-building"></i>
            </div>
            <h4 class="mb-0 fw-bold font-heading" style="color: var(--primary-brand);">SmartSociety</h4>
          </div>
          <h3 class="fw-bold font-heading mb-1" style="color: var(--primary-brand);">Welcome back</h3>
          <p class="text-muted small">Please sign in to your SmartSociety account to proceed.</p>
        </div>

        {{-- Error Alert --}}
        @if ($errors->has('login'))
          <div class="alert alert-danger glass-panel border-0 mb-4 py-2 px-3 text-danger fw-semibold" style="background: rgba(192, 57, 43, 0.15); font-size: 0.88rem;">
            <i class="bi bi-exclamation-circle me-2"></i> {{ $errors->first('login') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
          @csrf

          <div class="mb-3">
            <label for="login" class="form-label fw-semibold small" style="color: var(--secondary-text);">Username or Email</label>
            <div class="input-group">
              <span class="input-group-text border-end-0 glass-panel" style="background: rgba(255, 248, 240, 0.9); border-radius: 0.75rem 0 0 0.75rem; border-color: var(--borders-dividers);">
                <i class="bi bi-person text-muted"></i>
              </span>
              <input 
                type="text" 
                name="login" 
                id="login" 
                class="form-control form-control-glass border-start-0 @error('login') is-invalid @enderror" 
                placeholder="Enter email or username" 
                value="{{ old('login') }}" 
                style="border-radius: 0 0.75rem 0.75rem 0;"
                required 
                autofocus
              >
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label fw-semibold small" style="color: var(--secondary-text);">Password</label>
            <div class="input-group">
              <span class="input-group-text border-end-0 glass-panel" style="background: rgba(255, 248, 240, 0.9); border-radius: 0.75rem 0 0 0.75rem; border-color: var(--borders-dividers);">
                <i class="bi bi-lock text-muted"></i>
              </span>
              <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control form-control-glass border-start-0" 
                placeholder="••••••••" 
                style="border-radius: 0 0.75rem 0.75rem 0;"
                required
              >
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
              <input type="checkbox" name="remember" id="remember" class="form-check-input" style="accent-color: var(--primary-brand);">
              <label for="remember" class="form-check-label small" style="color: var(--secondary-text);">Remember credentials</label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary-custom w-100 py-2.5 justify-content-center">
            <i class="bi bi-box-arrow-in-right"></i> Sign In to Portal
          </button>
        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>