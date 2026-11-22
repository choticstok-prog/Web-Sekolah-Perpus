<!-- ===================================================================
     LANDING SCREEN AWALAN BUKA WEB (Layar Pembuka Mandiri / Fullscreen)
     File: resources/views/partials/landing-screen.blade.php
     =================================================================== -->
<div id="landing-welcome-screen" class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center text-white" 
     style="z-index: 99999; background: linear-gradient(135deg, #0b1120 0%, #0f172a 50%, #1e293b 100%); transition: transform 0.85s cubic-bezier(0.77, 0, 0.175, 1);">

    <!-- Efek Cahaya Lingkaran di Latar Belakang -->
    <div class="position-absolute top-50 start-50 translate-middle rounded-circle pointer-events-none" 
         style="width: 600px; height: 600px; background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(15, 23, 42, 0) 70%); filter: blur(60px);"></div>

    <!-- Kotak Konten Utama -->
    <div class="position-relative z-1 px-4" style="max-width: 780px;">
        
        <!-- 1. Lencana Resmi -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-4 shadow-sm" 
             style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.35); color: #93c5fd; font-size: 0.88rem; font-weight: 600;">
            <i class="bi bi-mortarboard-fill text-primary"></i>
            <span>Pustaka Digital Sekolah & E-Reader Resmi</span>
        </div>

        <!-- 2. Judul Besar Sambutan -->
        <h1 class="display-4 fw-bold mb-3 text-white lh-sm">
            Selamat Datang di <span style="color: #60a5fa;">PustakaSekolah</span>
        </h1>

        <!-- 3. Deskripsi Singkat -->
        <p class="lead mb-4 fs-6 text-slate-300" style="color: #cbd5e1; line-height: 1.8; max-width: 640px; margin: 0 auto;">
            Akses <strong>74+ buku pelajaran resmi</strong> Kurikulum Merdeka (SMP, SMA, SMK) serta <strong>20 novel sastra nusantara pilihan</strong>. Baca langsung di peramban secara digital, gratis, dan nyaman.
        </p>

        <!-- 4. Tombol Mulai Baca (Pemicu Animasi Geser ke Atas) -->
        <div class="d-flex flex-column align-items-center gap-2 mt-4">
            <button type="button" id="btn-mulai-baca" 
                    class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg d-inline-flex align-items-center gap-3 landing-btn-pulse"
                    style="font-size: 1.15rem; letter-spacing: 0.5px;">
                <i class="bi bi-book-half fs-4"></i>
                <span>Mulai Baca</span>
                <i class="bi bi-arrow-up-circle-fill fs-4 bounce-arrow"></i>
            </button>
            <span class="small opacity-75 mt-2" style="color: #94a3b8; font-size: 0.82rem;">
                <i class="bi bi-hand-index-thumb me-1"></i> Klik untuk masuk ke website perpustakaan
            </span>
        </div>
    </div>
</div>

<!-- ================= STYLE CSS ANIMASI ================= -->
<style>
    /* Animasi Panah Memantul ke Atas */
    @keyframes bounceUp {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .bounce-arrow { display: inline-block; animation: bounceUp 1.3s infinite ease-in-out; }

    /* Efek Hover Tombol Mulai Baca */
    .landing-btn-pulse {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .landing-btn-pulse:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 16px 35px rgba(37, 99, 235, 0.45) !important;
    }
</style>

<!-- ================= SCRIPT JS GESER KE ATAS ================= -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnMulaiBaca = document.getElementById('btn-mulai-baca');
        const landingScreen = document.getElementById('landing-welcome-screen');

        if (btnMulaiBaca && landingScreen) {
            btnMulaiBaca.addEventListener('click', function () {
                // Memicu animasi geser ke atas (meluncur ke atas layar)
                landingScreen.style.transform = 'translateY(-100%)';

                // Hapus elemen setelah selesai animasi agar tidak menghalangi klik
                setTimeout(function () {
                    landingScreen.style.display = 'none';
                }, 850);
            });
        }
    });
</script>
