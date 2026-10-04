@extends('layouts.app')

@section('title', 'Masuk ke Akun - Perpustakaan Sekolah')

@section('content')
<div class="row justify-content-center py-4 py-md-5">
    <div class="col-md-7 col-lg-5 col-xl-4">
        <div class="auth-card p-4 p-md-5">
            <!-- Header Login -->
            <div class="text-center mb-4">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 mb-3 fw-semibold small">
                    <i class="bi bi-shield-lock-fill me-1"></i> Portal Siswa & Petugas
                </span>

                <div class="mx-auto d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm mb-3" 
                     style="width: 56px; height: 56px; font-size: 1.5rem; background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%) !important;">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>

                <h3 class="fw-bold mb-1">Masuk ke Akun</h3>
                <p class="text-muted small mb-0">Silakan masuk dengan akun Siswa atau Petugas Perpustakaan.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" id="loginForm" onsubmit="handleLoginSubmit(this)">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-envelope text-primary"></i> Alamat Email
                    </label>
                    <div class="auth-input-group @error('email') is-invalid-group @enderror">
                        <span class="auth-input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="auth-form-control" id="email" name="email" 
                               value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-lock text-primary"></i> Kata Sandi
                    </label>
                    <div class="auth-input-group @error('password') is-invalid-group @enderror">
                        <span class="auth-input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="auth-form-control" id="password" name="password" 
                               required placeholder="Masukkan kata sandi">
                        <button type="button" class="auth-toggle-pwd" onclick="togglePasswordVisibility('password', this)" title="Lihat Sandi" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="remember">
                        Ingat sesi saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 mb-3">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </form>

            <!-- Link Register -->
            <div class="text-center pt-3 border-top">
                <p class="small text-muted mb-0">
                    Belum punya akun anggota? 
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">
                        Daftar di sini <i class="bi bi-arrow-right-short"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function handleLoginSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sedang Masuk...';
        }
    }

    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (!input || !icon) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
