<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan SMA/SMK - Peminjaman Buku Pelajaran') - PustakaSekolah</title>

    <!-- Immediate Theme Initialization (Mencegah Layar Kedip Hitam/Putih) -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('pustaka_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
            } catch(e) {}
        })();
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #0ea5e9;
            --accent-color: #f59e0b;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --navbar-bg: rgba(255, 255, 255, 0.94);
            --topbar-bg: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        [data-bs-theme="dark"] {
            --primary-color: #3b82f6;
            --primary-hover: #60a5fa;
            --secondary-color: #38bdf8;
            --accent-color: #fbbf24;
            --body-bg: #0b0f19;
            --card-bg: #151d30;
            --card-border: #23304a;
            --navbar-bg: rgba(15, 23, 42, 0.94);
            --topbar-bg: #0d1424;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        html {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Living Gradient Orbs (Background Bergerak Hidup) */
        .ambient-canvas-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }

        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(85px);
            opacity: 0.22;
            animation: orbFloat 22s infinite alternate ease-in-out;
        }

        [data-bs-theme="dark"] .ambient-orb {
            opacity: 0.16;
            filter: blur(95px);
        }

        .ambient-orb-1 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, #3b82f6 0%, rgba(37, 99, 235, 0.2) 70%);
            top: -120px;
            left: -120px;
        }

        .ambient-orb-2 {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, #06b6d4 0%, rgba(14, 165, 233, 0.2) 70%);
            bottom: 5%;
            right: -150px;
            animation-duration: 26s;
            animation-delay: -6s;
        }

        .ambient-orb-3 {
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, #8b5cf6 0%, rgba(139, 92, 246, 0.15) 70%);
            top: 45%;
            left: 45%;
            animation-duration: 20s;
            animation-delay: -12s;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(45px, 65px) scale(1.12) rotate(45deg); }
            100% { transform: translate(-35px, 35px) scale(0.92) rotate(-30deg); }
        }

        /* Top Operational Live Bar */
        .top-live-bar {
            background-color: var(--topbar-bg);
            border-bottom: 1px solid var(--card-border);
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--card-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }

        [data-bs-theme="dark"] .navbar-brand {
            color: #60a5fa !important;
        }

        [data-bs-theme="dark"] .brand-subtext {
            color: #38bdf8 !important;
        }

        [data-bs-theme="dark"] .brand-badge {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.2s ease;
            white-space: nowrap !important;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        [data-bs-theme="dark"] .nav-link {
            color: #94a3b8;
        }

        [data-bs-theme="dark"] .nav-link:hover, 
        [data-bs-theme="dark"] .nav-link.active {
            color: #60a5fa !important;
        }

        /* Hero Search Box (Konsisten & Rapi di Mode Terang maupun Gelap) */
        .hero-search-box {
            background-color: #ffffff !important;
            border-radius: 50rem;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
            display: flex;
            align-items: center;
            padding: 0.25rem 0.35rem 0.25rem 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 100%;
        }

        .hero-search-box .input-group-text {
            background-color: transparent !important;
            border: none !important;
            color: #2563eb !important;
            padding-right: 0.4rem;
            padding-left: 0;
            font-size: 1.25rem;
        }

        .hero-search-box .form-control {
            background-color: transparent !important;
            border: none !important;
            color: #0f172a !important;
            font-size: 0.95rem;
            box-shadow: none !important;
            padding-left: 0.25rem;
        }

        .hero-search-box .form-control::placeholder {
            color: #64748b !important;
            opacity: 0.95;
        }

        .hero-search-box .form-control:focus {
            background-color: transparent !important;
            box-shadow: none !important;
            color: #0f172a !important;
        }

        /* Hero Search Tag Chips (Tidak Pernah Putih Kosong) */
        .hero-search-tag {
            background-color: rgba(255, 255, 255, 0.16) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.32) !important;
            border-radius: 50rem;
            padding: 0.28rem 0.8rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .hero-search-tag:hover {
            background-color: rgba(255, 255, 255, 0.35) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            border-color: rgba(255, 255, 255, 0.6) !important;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 30px -6px rgba(15, 23, 42, 0.14), 0 8px 14px -6px rgba(15, 23, 42, 0.08) !important;
            border-color: rgba(37, 99, 235, 0.4) !important;
        }

        [data-bs-theme="dark"] .card-hover:hover {
            box-shadow: 0 20px 32px -4px rgba(0, 0, 0, 0.5), 0 0 18px rgba(59, 130, 246, 0.25) !important;
            border-color: rgba(59, 130, 246, 0.5) !important;
        }

        /* Card Shine Sweep Effect */
        .card-shine {
            position: relative;
            overflow: hidden;
        }
        .card-shine::after {
            content: '';
            position: absolute;
            top: -60%;
            left: -60%;
            width: 220%;
            height: 220%;
            background: linear-gradient(60deg, transparent 40%, rgba(255, 255, 255, 0.16) 50%, transparent 60%);
            transform: rotate(25deg) translateY(-100%);
            transition: transform 0.75s ease;
            pointer-events: none;
            z-index: 5;
        }
        .card-shine:hover::after {
            transform: rotate(25deg) translateY(100%);
        }

        /* ========================================================
           MODERN STAT CARDS & METRIC CHIPS (PREMIUM DASHBOARD)
           ======================================================== */
        .stat-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 1.25rem 1.35rem;
            position: relative;
            box-shadow: 0 4px 14px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -2px rgba(15, 23, 42, 0.03);
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 28px -6px rgba(15, 23, 42, 0.1), 0 6px 14px -4px rgba(15, 23, 42, 0.06);
            border-color: rgba(37, 99, 245, 0.3);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            transition: all 0.25s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.08) rotate(3deg);
        }

        .stat-card .stat-label {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-number {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 0.25rem;
        }

        .stat-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.32rem 0.75rem;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.2s ease;
            width: fit-content;
            margin-top: 0.85rem;
        }

        .stat-link i {
            font-size: 0.85rem;
            transition: transform 0.2s ease;
        }

        .stat-link:hover i {
            transform: translateX(3px);
        }

        .stat-link-primary {
            background-color: rgba(37, 99, 235, 0.08);
            color: #2563eb !important;
        }
        .stat-link-primary:hover {
            background-color: rgba(37, 99, 235, 0.16);
            color: #1d4ed8 !important;
        }

        .stat-link-info {
            background-color: rgba(6, 182, 212, 0.08);
            color: #0284c7 !important;
        }
        .stat-link-info:hover {
            background-color: rgba(6, 182, 212, 0.16);
            color: #0369a1 !important;
        }

        .stat-link-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706 !important;
        }
        .stat-link-warning:hover {
            background-color: rgba(245, 158, 11, 0.2);
            color: #b45309 !important;
        }

        .stat-link-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669 !important;
        }
        .stat-link-success:hover {
            background-color: rgba(16, 185, 129, 0.2);
            color: #047857 !important;
        }

        /* Theme Switcher Button */
        .theme-toggle-btn {
            background-color: var(--card-bg);
            border-color: var(--card-border) !important;
            color: var(--text-main);
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .theme-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: var(--primary-color);
        }

        /* Floating Theme Feedback Toast */
        .theme-toast {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background-color: #0f172a;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            z-index: 9999;
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        [data-bs-theme="dark"] .theme-toast {
            background-color: #1e293b;
            color: #f8fafc;
            border-color: rgba(255, 255, 255, 0.2);
        }
        .theme-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* Floating Back to Top Button */
        .back-to-top-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            z-index: 990;
            opacity: 0;
            visibility: hidden;
            transform: scale(0.8);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-to-top-btn.visible {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }
        .back-to-top-btn:hover {
            transform: scale(1.1) translateY(-3px);
        }

        /* Pulsing Live Dot Animation */
        @keyframes pulseRing {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: pulseRing 2s infinite;
        }

        /* Tombol Hijau Khas Baca Online Sekarang (btn-reader) */
        .btn-reader {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
        }

        .btn-reader:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.5) !important;
            color: #ffffff !important;
        }

        .btn-reader:active {
            transform: translateY(0);
        }

        /* Badge E-Reader Hijau */
        .badge-reader {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #059669 !important;
            border: 1px solid rgba(16, 185, 129, 0.35) !important;
            font-weight: 700;
        }

        [data-bs-theme="dark"] .badge-reader {
            background: rgba(16, 185, 129, 0.25) !important;
            color: #34d399 !important;
            border-color: rgba(52, 211, 153, 0.5) !important;
        }

        /* Status Badge Peminjaman */
        .badge-status {
            font-weight: 600;
            padding: 0.45em 0.85em;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        .badge-menunggu {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fde68a !important;
        }

        .badge-dipinjam {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border: 1px solid #bfdbfe !important;
        }

        .badge-dikembalikan {
            background-color: #dcfce7 !important;
            color: #166534 !important;
            border: 1px solid #bbf7d0 !important;
        }

        .badge-ditolak {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
        }

        .badge-terlambat {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
            border: 1px solid #fca5a5 !important;
        }

        [data-bs-theme="dark"] .badge-menunggu {
            background-color: rgba(245, 158, 11, 0.2) !important;
            color: #fbbf24 !important;
            border-color: rgba(245, 158, 11, 0.4) !important;
        }

        [data-bs-theme="dark"] .badge-dipinjam {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #60a5fa !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        [data-bs-theme="dark"] .badge-dikembalikan {
            background-color: rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
        }

        [data-bs-theme="dark"] .badge-ditolak {
            background-color: rgba(239, 68, 68, 0.2) !important;
            color: #f87171 !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
        }

        [data-bs-theme="dark"] .badge-terlambat {
            background-color: rgba(220, 38, 38, 0.25) !important;
            color: #fca5a5 !important;
            border-color: rgba(220, 38, 38, 0.5) !important;
        }

        /* Dark mode button & badge refinements */
        [data-bs-theme="dark"] .btn-light {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        [data-bs-theme="dark"] .btn-light:hover {
            background-color: #2b3952 !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }
        [data-bs-theme="dark"] .badge.bg-light {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }

        /* ========================================================
           DARK MODE TYPOGRAPHY & VISIBILITY (SEMUA TEKS PUTIH & JELAS)
           ======================================================== */
        [data-bs-theme="dark"] {
            color-scheme: dark;
            --bs-body-color: #f8fafc;
            --bs-body-color-rgb: 248, 250, 252;
            --bs-body-bg: #0b0f19;
            --bs-body-bg-rgb: 11, 15, 25;
            --bs-emphasis-color: #ffffff;
            --bs-emphasis-color-rgb: 255, 255, 255;
            --bs-secondary-color: #cbd5e1;
            --bs-secondary-color-rgb: 203, 213, 225;
            --bs-dark: #ffffff;
            --bs-dark-rgb: 255, 255, 255;
            --bs-heading-color: #ffffff;
        }

        [data-bs-theme="dark"] body {
            color: #f8fafc !important;
            background-color: #0b0f19 !important;
        }

        /* Semua teks bertema dark diubah jadi PUTIH BERSIH */
        [data-bs-theme="dark"] .text-dark,
        [data-bs-theme="dark"] a.text-dark,
        [data-bs-theme="dark"] h1.text-dark,
        [data-bs-theme="dark"] h2.text-dark,
        [data-bs-theme="dark"] h3.text-dark,
        [data-bs-theme="dark"] h4.text-dark,
        [data-bs-theme="dark"] h5.text-dark,
        [data-bs-theme="dark"] h6.text-dark,
        [data-bs-theme="dark"] strong.text-dark,
        [data-bs-theme="dark"] span.text-dark,
        [data-bs-theme="dark"] p.text-dark,
        [data-bs-theme="dark"] div.text-dark {
            color: #ffffff !important;
        }

        /* ========================================================
           ELEMEN & BADGE BERLATAR PUTIH: TEKS HARUS HITAM PEKAT
           ======================================================== */
        .book-category-badge,
        .book-category-badge *,
        .badge-white,
        .badge-white *,
        .badge.bg-white,
        .badge.bg-white *,
        .bg-white .text-dark,
        .bg-warning.text-dark,
        .bg-warning .text-dark {
            color: #0f172a !important;
        }

        [data-bs-theme="dark"] .book-category-badge,
        [data-bs-theme="dark"] .book-category-badge *,
        [data-bs-theme="dark"] .book-category-badge .category-name-text,
        [data-bs-theme="dark"] .badge-white,
        [data-bs-theme="dark"] .badge-white *,
        [data-bs-theme="dark"] .badge.bg-white,
        [data-bs-theme="dark"] .badge.bg-white *,
        [data-bs-theme="dark"] .bg-white .text-dark,
        [data-bs-theme="dark"] .bg-white span.text-dark,
        [data-bs-theme="dark"] .bg-warning.text-dark,
        [data-bs-theme="dark"] .bg-warning .text-dark,
        [data-bs-theme="dark"] .bg-warning span.text-dark {
            color: #0f172a !important;
        }

        /* Badge Angka Jumlah Buku di Filter Mapel */
        .category-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 50rem;
            background-color: rgba(148, 163, 184, 0.25);
            color: inherit;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .btn-primary .category-count-badge {
            background-color: #ffffff !important;
            color: #2563eb !important;
        }

        [data-bs-theme="dark"] .btn-outline-secondary .category-count-badge {
            background-color: rgba(255, 255, 255, 0.18) !important;
            color: #ffffff !important;
        }

        [data-bs-theme="light"] .btn-outline-secondary .category-count-badge {
            background-color: #e2e8f0 !important;
            color: #1e293b !important;
        }

        .book-category-badge {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.28) !important;
            border: 1px solid rgba(255, 255, 255, 0.95) !important;
            letter-spacing: -0.2px;
        }

        [data-bs-theme="dark"] .book-category-badge {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        .book-category-badge .text-warning,
        [data-bs-theme="dark"] .book-category-badge .text-warning {
            color: #d97706 !important;
        }

        /* Semua Judul / Heading di Mode Gelap menjadi PUTIH */
        [data-bs-theme="dark"] h1,
        [data-bs-theme="dark"] h2,
        [data-bs-theme="dark"] h3,
        [data-bs-theme="dark"] h4,
        [data-bs-theme="dark"] h5,
        [data-bs-theme="dark"] h6,
        [data-bs-theme="dark"] .card-title,
        [data-bs-theme="dark"] .card-title a {
            color: #ffffff !important;
        }

        /* Teks abu-abu (muted & secondary) dibuat terang (Silver / Light Slate) agar sangat jelas terbaca */
        [data-bs-theme="dark"] .text-muted,
        [data-bs-theme="dark"] small.text-muted,
        [data-bs-theme="dark"] p.text-muted,
        [data-bs-theme="dark"] span.text-muted,
        [data-bs-theme="dark"] div.text-muted,
        [data-bs-theme="dark"] .text-secondary:not(.brand-subtext) {
            color: #cbd5e1 !important;
        }

        /* Kartu & Container di Mode Gelap */
        [data-bs-theme="dark"] .card {
            background-color: #151d30 !important;
            border-color: #23304a !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border-bottom-color: #23304a !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .list-group-item {
            background-color: transparent !important;
            border-color: #23304a !important;
            color: #cbd5e1 !important;
        }

        /* Stat Cards di Mode Gelap */
        [data-bs-theme="dark"] .stat-card {
            background: linear-gradient(145deg, #172238 0%, #111827 100%) !important;
            border: 1px solid #23304a !important;
            box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.4) !important;
        }

        [data-bs-theme="dark"] .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(96, 165, 250, 0.4) !important;
            box-shadow: 0 16px 30px -4px rgba(0, 0, 0, 0.6), 0 0 16px rgba(59, 130, 246, 0.18) !important;
        }

        [data-bs-theme="dark"] .stat-card .stat-label {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .stat-card h3 {
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .stat-card small {
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .stat-link-primary {
            background-color: rgba(59, 130, 246, 0.16) !important;
            color: #93c5fd !important;
            border: 1px solid rgba(59, 130, 246, 0.25) !important;
        }
        [data-bs-theme="dark"] .stat-link-primary:hover {
            background-color: rgba(59, 130, 246, 0.3) !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(59, 130, 246, 0.3) !important;
        }

        [data-bs-theme="dark"] .stat-link-info {
            background-color: rgba(6, 182, 212, 0.16) !important;
            color: #67e8f9 !important;
            border: 1px solid rgba(6, 182, 212, 0.25) !important;
        }
        [data-bs-theme="dark"] .stat-link-info:hover {
            background-color: rgba(6, 182, 212, 0.3) !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.3) !important;
        }

        [data-bs-theme="dark"] .stat-link-warning {
            background-color: rgba(245, 158, 11, 0.16) !important;
            color: #fde047 !important;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
        }
        [data-bs-theme="dark"] .stat-link-warning:hover {
            background-color: rgba(245, 158, 11, 0.3) !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.3) !important;
        }

        [data-bs-theme="dark"] .stat-link-success {
            background-color: rgba(16, 185, 129, 0.16) !important;
            color: #6ee7b7 !important;
            border: 1px solid rgba(16, 185, 129, 0.25) !important;
        }
        [data-bs-theme="dark"] .stat-link-success:hover {
            background-color: rgba(16, 185, 129, 0.3) !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.3) !important;
        }

        /* Subtle Badge & Icon Container di Mode Gelap */
        [data-bs-theme="dark"] .bg-primary-subtle {
            background-color: rgba(59, 130, 246, 0.22) !important;
            color: #60a5fa !important;
        }
        [data-bs-theme="dark"] .bg-info-subtle {
            background-color: rgba(6, 182, 212, 0.22) !important;
            color: #38bdf8 !important;
        }
        [data-bs-theme="dark"] .bg-success-subtle {
            background-color: rgba(16, 185, 129, 0.22) !important;
            color: #34d399 !important;
        }
        [data-bs-theme="dark"] .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.22) !important;
            color: #fbbf24 !important;
        }

        [data-bs-theme="dark"] .bg-white:not(.hero-search-box):not(.hero-search-box *),
        [data-bs-theme="dark"] .bg-light {
            background-color: #151d30 !important;
            color: #f8fafc !important;
        }

        /* Link navigasi navbar */
        [data-bs-theme="dark"] .navbar .nav-link {
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .navbar .nav-link:hover,
        [data-bs-theme="dark"] .navbar .nav-link.active {
            color: #60a5fa !important;
        }

        /* Table Global & Mode Gelap */
        thead.table-light th {
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #64748b;
        }

        [data-bs-theme="dark"] .table {
            --bs-table-color: #f1f5f9 !important;
            --bs-table-bg: transparent !important;
            --bs-table-border-color: #23304a !important;
            color: #f1f5f9 !important;
        }

        [data-bs-theme="dark"] .table thead,
        [data-bs-theme="dark"] thead.table-light,
        [data-bs-theme="dark"] .table-light,
        [data-bs-theme="dark"] thead.table-light tr,
        [data-bs-theme="dark"] thead.table-light th {
            --bs-table-bg: #1a2337 !important;
            --bs-table-color: #cbd5e1 !important;
            background-color: #1a2337 !important;
            color: #cbd5e1 !important;
            border-bottom: 1px solid #23304a !important;
        }

        [data-bs-theme="dark"] .table th {
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] thead.table-light th {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .table td {
            color: #cbd5e1 !important;
            border-color: #23304a !important;
        }

        /* Footer di Mode Gelap */
        [data-bs-theme="dark"] footer {
            background-color: rgba(15, 23, 42, 0.94) !important;
            border-top-color: #23304a !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] footer strong {
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] footer a {
            color: #94a3b8 !important;
        }

        /* ========================================================
           AUTHENTICATION CARDS & MODERN FORM CONTROLS
           ======================================================== */
        .auth-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .auth-card {
            background-color: #151d30 !important;
            border: 1px solid rgba(59, 130, 246, 0.22) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.65), 0 0 25px rgba(59, 130, 246, 0.06) !important;
        }

        .auth-input-group {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        [data-bs-theme="dark"] .auth-input-group {
            background-color: #0b1120 !important;
            border-color: #23304a !important;
        }

        .auth-input-group:focus-within {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
            background-color: #ffffff !important;
        }

        [data-bs-theme="dark"] .auth-input-group:focus-within {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3) !important;
            background-color: #0f172a !important;
        }

        .auth-input-group.is-invalid-group {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }

        .auth-input-icon {
            padding-left: 1rem;
            padding-right: 0.5rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            transition: color 0.2s ease;
            user-select: none;
        }

        .auth-input-group:focus-within .auth-input-icon {
            color: #2563eb;
        }

        [data-bs-theme="dark"] .auth-input-group:focus-within .auth-input-icon {
            color: #60a5fa;
        }

        .auth-form-control {
            border: none !important;
            background: transparent !important;
            padding: 0.7rem 0.75rem 0.7rem 0.25rem !important;
            font-size: 0.95rem;
            color: var(--text-main) !important;
            box-shadow: none !important;
            flex: 1 1 auto;
            width: 1%;
            line-height: 1.5;
        }

        [data-bs-theme="dark"] .auth-form-control {
            color: #ffffff !important;
        }

        .auth-form-control:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .auth-form-control::placeholder {
            color: #94a3b8 !important;
            font-size: 0.9rem;
        }

        [data-bs-theme="dark"] .auth-form-control::placeholder {
            color: #64748b !important;
        }

        .auth-toggle-pwd {
            padding: 0.5rem 0.85rem;
            color: #94a3b8;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .auth-toggle-pwd:hover {
            color: #2563eb;
        }

        [data-bs-theme="dark"] .auth-toggle-pwd:hover {
            color: #60a5fa;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Ambient Living Gradient Orbs (Background Bergerak Hidup) -->
    <div class="ambient-canvas-wrapper" aria-hidden="true">
        <div class="ambient-orb ambient-orb-1"></div>
        <div class="ambient-orb ambient-orb-2"></div>
        <div class="ambient-orb ambient-orb-3"></div>
    </div>

    <!-- Live Operational Ticker & School Info Top Bar (Membuat Web Hidup) -->
    <div class="top-live-bar py-1 px-3 border-bottom">
        <div class="container-fluid px-3 px-md-4 px-xl-5 d-flex flex-wrap justify-content-between align-items-center gap-2 small" style="max-width: 1440px;">
            <div class="d-flex align-items-center gap-2 gap-sm-3 flex-wrap">
                <span class="d-inline-flex align-items-center gap-1 fw-bold text-primary" id="liveGreetingWrapper">
                    <span id="liveGreetingIcon">🌅</span>
                    <span id="liveGreetingText">Selamat Belajar</span>
                </span>
                <span class="text-secondary opacity-50 d-none d-sm-inline">|</span>
                <span class="d-inline-flex align-items-center gap-1 text-muted" id="liveClockWrapper">
                    <i class="bi bi-clock-history text-secondary"></i>
                    <span id="liveClockText">WIB</span>
                </span>
                <span class="text-secondary opacity-50 d-none d-sm-inline">|</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill py-1 px-2 d-inline-flex align-items-center gap-1">
                    <span class="live-dot" style="width: 6px; height: 6px;"></span>
                    <span><strong id="liveReadersNum">21</strong> Siswa Sedang Membaca</span>
                </span>
            </div>

            <!-- Dynamic Literacy Quotes Cycling -->
            <div class="d-none d-lg-flex align-items-center gap-2 text-truncate" style="max-width: 460px;">
                <i class="bi bi-quote text-warning fs-6"></i>
                <span id="quoteTickerText" class="text-muted text-truncate fst-italic" style="font-size: 0.78rem;">
                    "Membaca adalah jendela dunia dan kunci masa depan."
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top py-2.5 py-lg-3">
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1440px;">
            <a class="navbar-brand d-flex align-items-center gap-2 flex-shrink-0" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill text-primary fs-3 animate-float"></i>
                <span>Pustaka<span class="text-secondary brand-subtext">Sekolah</span></span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small ms-1 brand-badge d-none d-xl-inline-block" style="font-size: 0.65rem; font-weight: 700;">SMA / SMK</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-2 ms-xl-3 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link px-2 px-xl-2.5 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 px-xl-2.5 {{ request()->routeIs('catalog.index') && !request('baca_online') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                            <i class="bi bi-grid me-1"></i> <span class="d-none d-xl-inline">Katalog Buku</span><span class="d-inline d-xl-none">Katalog</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 px-xl-2.5 d-flex align-items-center gap-1 {{ request('baca_online') ? 'active fw-bold text-success' : 'text-success fw-semibold' }}" href="{{ route('catalog.index', ['baca_online' => 1]) }}">
                            <span class="live-dot me-1"></span>
                            <span>Baca Online</span>
                            <span class="badge badge-reader rounded-pill px-1.5 py-0 ms-1 small d-none d-xl-inline" style="font-size: 0.65rem;">E-Reader</span>
                        </a>
                    </li>
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link px-2 px-xl-2.5 {{ request()->routeIs('borrowings.*') ? 'active' : '' }}" href="{{ route('borrowings.index') }}">
                                    <i class="bi bi-clock-history me-1"></i> <span class="d-none d-xl-inline">Peminjaman Saya</span><span class="d-inline d-xl-none">Peminjaman</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary fw-bold px-2 px-xl-2.5 {{ request()->is('admin*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-check me-1"></i> Panel Admin
                                </a>
                                <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                             <i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.borrowings.index') }}">
                                            <i class="bi bi-arrow-left-right text-warning me-2"></i> Kelola Peminjaman
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.books.index') }}">
                                            <i class="bi bi-journal-bookmark text-success me-2"></i> Data Buku
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.categories.index') }}">
                                            <i class="bi bi-tags text-info me-2"></i> Kategori Buku
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.members.index') }}">
                                            <i class="bi bi-people text-secondary me-2"></i> Data Anggota
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-2 flex-nowrap ms-auto flex-shrink-0">
                    <!-- Hitam / Putih Theme Switcher Button -->
                    <button type="button" class="btn btn-sm rounded-circle theme-toggle-btn d-inline-flex align-items-center justify-content-center border shadow-sm flex-shrink-0" id="themeToggleBtn" onclick="toggleTheme()" title="Ganti Mode Hitam / Putih (Dark & Light)" style="width: 34px; height: 34px; min-width: 34px; padding: 0;">
                        <i class="bi bi-moon-stars-fill text-warning fs-6" id="themeIcon"></i>
                    </button>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary px-3 py-1.5 rounded-pill text-nowrap fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary px-3 py-1.5 rounded-pill text-nowrap fw-semibold shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    @else
                        <div class="dropdown flex-shrink-0">
                            <button class="btn btn-light rounded-pill border dropdown-toggle d-flex align-items-center gap-1.5 px-2.5 py-1 text-nowrap shadow-sm" type="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; min-width: 28px; font-weight: 700; font-size: 0.8rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="text-start d-none d-sm-block text-nowrap pe-1" style="max-width: 130px;">
                                    <div class="fw-bold small lh-1 text-truncate" title="{{ auth()->user()->name }}">{{ preg_replace('/\s*\([^)]*\)/', '', auth()->user()->name) }}</div>
                                    <small class="text-muted d-block mt-1 text-truncate" style="font-size: 0.68rem; line-height: 1;">
                                        @if(auth()->user()->isAdminBesar())
                                            <span class="text-danger fw-bold"><i class="bi bi-shield-fill-check"></i> Admin</span>
                                        @elseif(auth()->user()->isAdmin())
                                            Petugas
                                        @else
                                            Siswa
                                        @endif
                                    </small>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2" style="min-width: 220px;">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                                    @if(auth()->user()->isAdminBesar())
                                        <span class="badge bg-danger shadow-sm mt-1">
                                            <i class="bi bi-shield-shaded me-1"></i> Admin Besar (Utama)
                                        </span>
                                    @elseif(auth()->user()->isAdmin())
                                        <span class="badge bg-danger mt-1">Petugas Perpustakaan</span>
                                    @else
                                        <span class="badge bg-primary mt-1">Siswa SMA</span>
                                    @endif
                                </li>
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard Admin
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('borrowings.index') }}">
                                            <i class="bi bi-book me-2 text-primary"></i> Peminjaman Saya
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-4">
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1440px;">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Terjadi kesalahan input:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-muted small mt-auto">
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1440px;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <div>
                    <strong>PustakaSekolah</strong> &copy; {{ date('Y') }} - Perpustakaan Digital SMA & SMK: Layanan Peminjaman Buku Pelajaran Kelas 10-12.
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none text-muted">Katalog</a>
                    @guest
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted">Login</a>
                        <a href="{{ route('register') }}" class="text-decoration-none text-muted">Register</a>
                    @endguest
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Back to Top Button -->
    <button type="button" class="btn btn-primary rounded-circle shadow-lg back-to-top-btn" id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Kembali ke Atas">
        <i class="bi bi-arrow-up fs-5"></i>
    </button>

    <!-- Floating Theme Feedback Toast -->
    <div id="themeToast" class="theme-toast shadow-lg rounded-pill px-3 py-2 small fw-semibold">
        <span id="themeToastIcon">🌙</span> <span id="themeToastMsg">Mode Gelap Diaktifkan</span>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Dynamic Features (Hitam/Putih & Web Hidup) -->
    <script>
        // 1. FITUR HITAM / PUTIH (DARK / LIGHT THEME TOGGLE)
        function updateThemeButtonUI(theme) {
            const btn = document.getElementById('themeToggleBtn');
            const icon = document.getElementById('themeIcon');
            const text = document.getElementById('themeText');
            if (!btn || !icon) return;

            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill text-warning';
                if (text) text.textContent = 'Mode Terang';
                btn.setAttribute('title', 'Beralih ke Mode Terang (Putih)');
            } else {
                icon.className = 'bi bi-moon-stars-fill text-warning';
                if (text) text.textContent = 'Mode Gelap';
                btn.setAttribute('title', 'Beralih ke Mode Gelap (Hitam)');
            }
        }

        function showThemeToast(msg, icon) {
            const toast = document.getElementById('themeToast');
            const toastMsg = document.getElementById('themeToastMsg');
            const toastIcon = document.getElementById('themeToastIcon');
            if (!toast) return;

            toastMsg.textContent = msg;
            toastIcon.textContent = icon;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2400);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-bs-theme', newTheme);
            try {
                localStorage.setItem('pustaka_theme', newTheme);
            } catch(e) {}

            updateThemeButtonUI(newTheme);

            if (newTheme === 'dark') {
                showThemeToast('Mode Gelap (Hitam) Aktif', '🌙');
            } else {
                showThemeToast('Mode Terang (Putih) Aktif', '☀️');
            }
        }

        // Initialize button state immediately on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeButtonUI(currentTheme);

            // 2. JAM DIGITAL REALTIME & SAPAAN WAKTU DINAMIS
            function updateClockAndGreeting() {
                const now = new Date();
                const hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                // Hari & Tanggal Indonesia
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const dayName = days[now.getDay()];
                const dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

                const clockElem = document.getElementById('liveClockText');
                if (clockElem) {
                    clockElem.textContent = `${dayName}, ${dateStr} • ${hours}:${minutes}:${seconds} WIB`;
                }

                // Sapaan Ramah Siswa
                const greetingIcon = document.getElementById('liveGreetingIcon');
                const greetingText = document.getElementById('liveGreetingText');
                if (greetingText && greetingIcon) {
                    if (hours >= 4 && hours < 11) {
                        greetingIcon.textContent = '🌅';
                        greetingText.textContent = 'Selamat Pagi, Semangat Belajar!';
                    } else if (hours >= 11 && hours < 15) {
                        greetingIcon.textContent = '☀️';
                        greetingText.textContent = 'Selamat Siang, Waktunya Literasi!';
                    } else if (hours >= 15 && hours < 18) {
                        greetingIcon.textContent = '🌇';
                        greetingText.textContent = 'Selamat Sore, Santai Sambil Membaca!';
                    } else {
                        greetingIcon.textContent = '🌙';
                        greetingText.textContent = 'Selamat Malam, Selamat Membaca!';
                    }
                }
            }
            updateClockAndGreeting();
            setInterval(updateClockAndGreeting, 1000);

            // 3. KUTIPAN LITERASI INSPIRATIF YANG BERGANTI-GANTI (TICKER)
            const quotes = [
                '"Buku adalah jembatan ilmu yang menghubungkan impian dengan masa depan."',
                '"Jadikan setiap tempat sebagai sekolah, jadikan setiap orang sebagai guru." — Ki Hajar Dewantara',
                '"Belajar tanpa berpikir itu sia-sia, berpikir tanpa belajar itu sangat berbahaya." — B.J. Habibie',
                '"Cuma perlu satu buku untuk jatuh cinta pada membaca. Mari temukan buku itu." — Najwa Shihab',
                '"Aku rela dipenjara asalkan bersama buku, karena dengan buku aku bebas." — Mohammad Hatta',
                '"Orang boleh pandai setinggi langit, tapi selama ia tidak menulis, ia akan hilang di masyarakat." — Pramoedya Ananta Toer',
                '"Membaca meluaskan cakrawala, membuka jendela dunia langsung di genggaman Anda."'
            ];
            let currentQuoteIdx = 0;
            const quoteElem = document.getElementById('quoteTickerText');
            if (quoteElem) {
                setInterval(() => {
                    quoteElem.style.opacity = '0';
                    quoteElem.style.transition = 'opacity 0.4s ease';
                    setTimeout(() => {
                        currentQuoteIdx = (currentQuoteIdx + 1) % quotes.length;
                        quoteElem.textContent = quotes[currentQuoteIdx];
                        quoteElem.style.opacity = '1';
                    }, 400);
                }, 6000);
            }

            // 4. SIMULASI PEMBACA AKTIF REAL-TIME (MEMBUAT WEB LEBIH HIDUP)
            let readersCount = 22;
            const readersElem = document.getElementById('liveReadersNum');
            if (readersElem) {
                setInterval(() => {
                    const delta = Math.floor(Math.random() * 3) - 1; // -1, 0, or +1
                    readersCount = Math.max(16, Math.min(32, readersCount + delta));
                    readersElem.textContent = readersCount;
                }, 10000);
            }

            // 5. TOMBOL FLOATING "KEMBALI KE ATAS"
            const backToTopBtn = document.getElementById('backToTopBtn');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
