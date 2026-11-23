<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Online SMP, SMA & SMK - Pustaka Digital & E-Reader') - PustakaSekolah</title>

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
            --secondary-color: #475569;
            --accent-color: #2563eb;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --navbar-bg: rgba(255, 255, 255, 0.96);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        [data-bs-theme="dark"] {
            --primary-color: #3b82f6;
            --primary-hover: #60a5fa;
            --secondary-color: #94a3b8;
            --accent-color: #60a5fa;
            --body-bg: #0b0f19;
            --card-bg: #151d30;
            --card-border: #23304a;
            --navbar-bg: rgba(15, 23, 42, 0.96);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        html {
            transition: background-color 0.25s ease, color 0.25s ease;
            scroll-behavior: smooth;
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

        /* ========================================================
           PAGE SLIDE-IN ENTRY TRANSITION (SUPER SMOOTH)
           ======================================================== */
        .page-slide-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            animation: pageSlideIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            will-change: transform, opacity;
        }

        @keyframes pageSlideIn {
            0% {
                opacity: 0;
                transform: translateX(45px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Ambient elements disabled for clean, non-distracting reading experience */
        .ambient-canvas-wrapper {
            display: none !important;
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
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08), 0 4px 8px -2px rgba(15, 23, 42, 0.04) !important;
            border-color: rgba(37, 99, 235, 0.3) !important;
        }

        [data-bs-theme="dark"] .card-hover:hover {
            box-shadow: 0 16px 28px -4px rgba(0, 0, 0, 0.45) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        .card-shine {
            position: relative;
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

        @media (min-width: 768px) {
            .border-start-md {
                border-left: 1px solid var(--card-border) !important;
            }
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
            transition: all 0.2s ease;
            cursor: pointer;
            width: 36px;
            height: 36px;
            min-width: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .theme-toggle-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-color) !important;
            color: var(--primary-color);
        }

        /* Navbar User Profile Pill Button (Rapi & Modern) */
        .nav-user-btn {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            border-radius: 50rem;
            padding: 0.2rem 0.65rem 0.2rem 0.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            height: 36px;
        }

        .nav-user-btn::after {
            display: none !important;
        }

        .nav-user-btn:hover,
        .nav-user-btn:focus-visible,
        .nav-user-btn[aria-expanded="true"] {
            border-color: var(--primary-color);
            background-color: var(--card-bg);
            color: var(--text-main);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
            transform: translateY(-1px);
        }

        [data-bs-theme="dark"] .nav-user-btn {
            background-color: rgba(21, 29, 48, 0.9);
            border-color: var(--card-border);
        }

        [data-bs-theme="dark"] .nav-user-btn:hover,
        [data-bs-theme="dark"] .nav-user-btn[aria-expanded="true"] {
            border-color: #60a5fa;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.15);
            background-color: rgba(27, 38, 62, 0.95);
        }

        .nav-user-btn .user-avatar {
            width: 28px;
            height: 28px;
            min-width: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
            flex-shrink: 0;
        }

        .nav-user-btn .user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.15;
            max-width: 120px;
        }

        .nav-user-btn .user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-user-btn .user-role {
            font-size: 0.65rem;
            font-weight: 500;
            color: var(--text-muted);
            line-height: 1;
            margin-top: 1px;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .nav-user-btn .user-chevron {
            font-size: 0.7rem;
            color: var(--text-muted);
            transition: transform 0.2s ease;
            margin-left: 0.1rem;
        }

        .dropdown.show .nav-user-btn .user-chevron,
        .nav-user-btn[aria-expanded="true"] .user-chevron {
            transform: rotate(180deg);
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

        /* Tombol Baca Online Sekarang (btn-reader) - Tenang & Harmonis */
        .btn-reader {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
            border: 1px solid transparent !important;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2) !important;
        }

        .btn-reader:hover {
            background-color: var(--primary-hover) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important;
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
        .hero-search-box .text-dark,
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
        [data-bs-theme="dark"] .hero-search-box .text-dark,
        [data-bs-theme="dark"] .hero-search-box span.text-dark,
        [data-bs-theme="dark"] .bg-warning.text-dark,
        [data-bs-theme="dark"] .bg-warning .text-dark,
        [data-bs-theme="dark"] .bg-warning span.text-dark {
            color: #0f172a !important;
        }

        /* 3 Steps Guide Section (Konsisten & Kontras di Mode Terang maupun Gelap) */
        .step-guide-wrapper {
            background-color: var(--card-bg);
            border-color: var(--card-border) !important;
        }
        [data-bs-theme="light"] .step-guide-wrapper {
            background-color: #f8fafc;
        }
        .step-guide-card {
            background-color: var(--card-bg);
            border-color: var(--card-border) !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .step-guide-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08) !important;
        }
        .step-guide-heading,
        .step-guide-title {
            color: var(--text-main) !important;
        }
        [data-bs-theme="dark"] .step-guide-heading,
        [data-bs-theme="dark"] .step-guide-title {
            color: #ffffff !important;
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

        /* ========================================================
           RESPONSIVITAS MOBILE & TAMPILAN OPTIMAL DI SMARTPHONE
           ======================================================== */
        @media (max-width: 768px) {
            /* Navbar Mobile */
            .navbar {
                padding-top: 0.65rem !important;
                padding-bottom: 0.65rem !important;
            }

            .navbar-brand {
                font-size: 1.18rem !important;
            }

            /* Container Spacing di HP */
            .container-fluid,
            .container {
                padding-left: 0.85rem !important;
                padding-right: 0.85rem !important;
            }

            /* Hero & Header Card Spacing di HP */
            .card.p-4.p-md-5,
            .card.p-md-5 {
                padding: 1.5rem 1.15rem !important;
                border-radius: 16px !important;
            }

            h1.display-6 {
                font-size: 1.55rem !important;
                line-height: 1.25 !important;
            }

            /* Hero Search Box di HP */
            .hero-search-box {
                flex-direction: column !important;
                border-radius: 18px !important;
                padding: 0.6rem !important;
                gap: 0.5rem !important;
                background-color: #ffffff !important;
            }

            .hero-search-box .form-control {
                width: 100% !important;
                padding: 0.4rem 0.6rem !important;
                font-size: 0.9rem !important;
            }

            .hero-search-box .btn-primary {
                width: 100% !important;
                justify-content: center !important;
                padding: 0.65rem 1rem !important;
                border-radius: 12px !important;
            }

            /* Stat Cards Mobile: 2 kolom rapi tanpa teks terpotong */
            .stat-card {
                padding: 0.9rem !important;
                border-radius: 14px !important;
            }

            .stat-icon {
                width: 38px !important;
                height: 38px !important;
                min-width: 38px !important;
                font-size: 1.1rem !important;
                border-radius: 10px !important;
            }

            .stat-card h3 {
                font-size: 1.25rem !important;
            }

            .stat-card small {
                font-size: 0.72rem !important;
            }

            /* Book Cards di HP: Tampilan cover proporsional */
            .card .object-fit-cover {
                height: 180px !important;
            }

            /* Floating Buttons Mobile Adjustments */
            .back-to-top-btn {
                bottom: 16px !important;
                right: 16px !important;
                width: 40px !important;
                height: 40px !important;
                font-size: 0.9rem !important;
            }

            .theme-toast {
                bottom: 16px !important;
                font-size: 0.75rem !important;
                padding: 0.4rem 0.85rem !important;
                max-width: 90vw !important;
                text-align: center;
            }

            /* Table scroll di ponsel */
            .table-responsive {
                border-radius: 12px;
                border: 1px solid var(--card-border);
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top py-2.5 py-lg-3">
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1440px;">
            <a class="navbar-brand d-flex align-items-center gap-2 flex-shrink-0" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
                <span>Pustaka<span class="text-secondary brand-subtext">Sekolah</span></span>
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
                            <i class="bi bi-grid me-1"></i> <span class="d-none d-xl-inline">Koleksi Buku</span><span class="d-inline d-xl-none">Koleksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 px-xl-2.5 d-flex align-items-center gap-1 {{ request('baca_online') ? 'active fw-bold' : '' }}" href="{{ route('catalog.index', ['baca_online' => 1]) }}">
                            <i class="bi bi-book-half me-1"></i>
                            <span>Baca Online</span>
                        </a>
                    </li>
                    @auth
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
                    <button type="button" class="btn btn-sm rounded-circle theme-toggle-btn border shadow-sm flex-shrink-0" id="themeToggleBtn" onclick="toggleTheme()" title="Ganti Mode Hitam / Putih (Dark & Light)">
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
                            <button class="nav-user-btn text-nowrap" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="user-info d-none d-sm-flex">
                                    <span class="user-name" title="{{ auth()->user()->name }}">
                                        {{ preg_replace('/\s*\([^)]*\)/', '', auth()->user()->name) }}
                                    </span>
                                    <span class="user-role">
                                        @if(auth()->user()->isAdminBesar())
                                            <span class="text-danger fw-semibold"><i class="bi bi-shield-fill-check"></i> Admin</span>
                                        @elseif(auth()->user()->isAdmin())
                                            <span class="text-primary fw-semibold"><i class="bi bi-person-badge"></i> Petugas</span>
                                        @else
                                            <span class="text-muted"><i class="bi bi-mortarboard"></i> Siswa</span>
                                        @endif
                                    </span>
                                </div>
                                <i class="bi bi-chevron-down user-chevron"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 mt-2 py-2" style="min-width: 230px;">
                                <li class="px-3 py-2.5 border-bottom mb-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="user-avatar" style="width: 36px; height: 36px; min-width: 36px; font-size: 0.95rem;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div class="text-truncate">
                                            <div class="fw-bold text-truncate" style="font-size: 0.88rem; color: var(--text-main);">{{ auth()->user()->name }}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                                        </div>
                                    </div>
                                    @if(auth()->user()->isAdminBesar())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                            <i class="bi bi-shield-shaded me-1"></i> Admin Utama
                                        </span>
                                    @elseif(auth()->user()->isAdmin())
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                            <i class="bi bi-person-badge me-1"></i> Petugas Perpustakaan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill" style="font-size: 0.68rem; font-weight: 600;">
                                            <i class="bi bi-mortarboard me-1"></i> Siswa / Pelajar
                                        </span>
                                    @endif
                                </li>
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 rounded-2 mx-1" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 text-primary"></i> Dashboard Admin
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 rounded-2 mx-1" href="{{ route('catalog.index', ['baca_online' => 1]) }}">
                                            <i class="bi bi-book-half text-success"></i> Baca Buku Online
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger rounded-2 mx-1">
                                            <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
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

    <!-- Main Content Area with Smooth Slide In -->
    <main class="page-slide-wrapper py-4">
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1440px;">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
                    <div>{{ session('warning') }}</div>
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
                    <strong>PustakaSekolah</strong> &copy; {{ date('Y') }} - Perpustakaan Online SMP, SMA & SMK: Pustaka Digital & Akses Baca E-Reader Siswa.
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none text-muted">Koleksi Buku</a>
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

            // Tombol Floating "Kembali ke Atas"
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
