@extends('layouts.app')

@section('title', 'Daftar Akun Siswa - Perpustakaan Sekolah')

@section('content')
<div class="row justify-content-center py-4 py-md-5">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card p-4 p-md-5">
            <!-- Header Registrasi -->
            <div class="text-center mb-4">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 mb-3 fw-semibold small">
                    <i class="bi bi-mortarboard-fill me-1"></i> Registrasi Siswa SMA / SMK
                </span>
                
                <div class="mx-auto d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm mb-3" 
                     style="width: 56px; height: 56px; font-size: 1.5rem; background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%) !important;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                
                <h3 class="fw-bold mb-1">Daftar Anggota Perpustakaan</h3>
                <p class="text-muted small mb-0">
                    Buat akun gratis untuk meminjam buku pelajaran SMA/SMK dan membaca koleksi e-reader digital sekolah.
                </p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" id="registerForm" onsubmit="handleFormSubmit(this)">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-person text-primary"></i> Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="auth-input-group @error('name') is-invalid-group @enderror">
                        <span class="auth-input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" class="auth-form-control" id="name" name="name" 
                               value="{{ old('name') }}" required autofocus placeholder="Contoh: Riky Hidayat">
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Alamat Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-envelope text-primary"></i> Alamat Email <span class="text-danger">*</span>
                    </label>
                    <div class="auth-input-group @error('email') is-invalid-group @enderror">
                        <span class="auth-input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="auth-form-control" id="email" name="email" 
                               value="{{ old('email') }}" required placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Kata Sandi & Konfirmasi Kata Sandi -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="password" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                            <i class="bi bi-lock text-primary"></i> Kata Sandi <span class="text-danger">*</span>
                        </label>
                        <div class="auth-input-group @error('password') is-invalid-group @enderror">
                            <span class="auth-input-icon"><i class="bi bi-lock"></i></span>
                            <input type="password" class="auth-form-control" id="password" name="password" 
                                   required placeholder="Min 6 karakter">
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

                    <div class="col-sm-6">
                        <label for="password_confirmation" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                            <i class="bi bi-shield-lock text-primary"></i> Ulangi Sandi <span class="text-danger">*</span>
                        </label>
                        <div class="auth-input-group">
                            <span class="auth-input-icon"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" class="auth-form-control" id="password_confirmation" name="password_confirmation" 
                                   required placeholder="Ulangi sandi">
                            <button type="button" class="auth-toggle-pwd" onclick="togglePasswordVisibility('password_confirmation', this)" title="Lihat Sandi" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Nomor WhatsApp / Telepon -->
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-whatsapp text-success"></i> Nomor WhatsApp / Telepon <span class="text-muted fw-normal">(Opsional)</span>
                    </label>
                    <div class="auth-input-group @error('phone') is-invalid-group @enderror">
                        <span class="auth-input-icon"><i class="bi bi-telephone"></i></span>
                        <input type="tel" class="auth-form-control" id="phone" name="phone" 
                               value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                    </div>
                    @error('phone')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Alamat Domisili / Kelas -->
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold small mb-1.5 d-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt text-danger"></i> Alamat Domisili / Kelas <span class="text-muted fw-normal">(Opsional)</span>
                    </label>
                    <div class="auth-input-group @error('address') is-invalid-group @enderror">
                        <span class="auth-input-icon align-self-start pt-2"><i class="bi bi-geo-alt"></i></span>
                        <textarea class="auth-form-control" id="address" name="address" rows="2" 
                                  placeholder="Contoh: Kelas XII RPL 1 / Jl. Merdeka No. 10">{{ old('address') }}</textarea>
                    </div>
                    @error('address')
                        <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Fast Access Info Note -->
                <div class="p-3 rounded-3 mb-4 d-flex align-items-center gap-2.5" 
                     style="background-color: rgba(37, 99, 235, 0.08); border: 1px dashed rgba(37, 99, 235, 0.3);">
                    <i class="bi bi-check-circle-fill text-primary fs-5 flex-shrink-0"></i>
                    <div class="text-muted" style="font-size: 0.82rem; line-height: 1.4;">
                        Akun otomatis langsung aktif. Anda dapat langsung meminjam buku & menikmati fitur baca online e-reader.
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 mb-3">
                    <i class="bi bi-person-check-fill fs-5"></i>
                    <span>Daftar Sekarang & Masuk</span>
                </button>
            </form>

            <!-- Link Masuk -->
            <div class="text-center pt-3 border-top">
                <p class="small text-muted mb-0">
                    Sudah memiliki akun anggota? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">
                        Masuk di sini <i class="bi bi-arrow-right-short"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function handleFormSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sedang Mendaftarkan...';
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
