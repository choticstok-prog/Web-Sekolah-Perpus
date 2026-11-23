<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Selamat Datang di PustakaSekolah - Perpustakaan Digital & E-Reader</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.5);
            --secondary-glow: rgba(139, 92, 246, 0.4);
            --bg-dark: #070d19;
            --surface-glass: rgba(15, 23, 42, 0.75);
            --border-glass: rgba(255, 255, 255, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ========================================================
           CINEMATIC INTRO SCREEN ("WELCOME TO PERPUS ONLINE")
           ======================================================== */
        #introOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #030712;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #introOverlay.fade-out {
            opacity: 0;
            transform: scale(1.08);
            pointer-events: none;
        }

        .intro-glow-circle {
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35) 0%, rgba(139, 92, 246, 0.2) 40%, transparent 70%);
            filter: blur(50px);
            animation: introPulse 2.4s ease-in-out infinite alternate;
        }

        @keyframes introPulse {
            0% { transform: scale(0.85); opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0.9; }
        }

        .intro-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 1.5rem;
            max-width: 90vw;
        }

        .intro-icon-box {
            width: 88px;
            height: 88px;
            margin: 0 auto 1.5rem;
            border-radius: 26px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.3) 0%, rgba(124, 58, 237, 0.3) 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            color: #60a5fa;
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.45);
            animation: introIconPop 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes introIconPop {
            0% { transform: scale(0) rotate(-15deg); opacity: 0; }
            70% { transform: scale(1.1) rotate(5deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        .intro-badge {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #93c5fd;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.35);
            padding: 0.35rem 1.15rem;
            border-radius: 50rem;
            margin-bottom: 0.85rem;
            opacity: 0;
            animation: introTextReveal 0.7s ease-out 0.3s forwards;
        }

        .intro-title {
            font-size: clamp(2rem, 6vw, 3.8rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 0.65rem;
            opacity: 0;
            animation: introTextReveal 0.8s ease-out 0.5s forwards;
        }

        .intro-title .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .intro-subtext {
            color: #94a3b8;
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            max-width: 540px;
            margin: 0 auto 1.5rem;
            opacity: 0;
            animation: introTextReveal 0.8s ease-out 0.7s forwards;
        }

        @keyframes introTextReveal {
            0% { opacity: 0; transform: translateY(18px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Animated Progress bar at intro */
        .intro-progress-bar {
            width: 180px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50rem;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }

        .intro-progress-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #3b82f6, #a855f7, #38bdf8);
            border-radius: 50rem;
            animation: introProgress 1.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
        }

        @keyframes introProgress {
            0% { width: 0%; }
            60% { width: 75%; }
            100% { width: 100%; }
        }

        /* ========================================================
           PAGE EXIT SLIDE TRANSITION (SUPER SMOOTH BEBAS KAKU)
           ======================================================== */
        #pageExitPortal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 99998;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Slide curtain overlay with elegant gradient shimmer */
        .slide-curtain {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #070d19 0%, #0c1427 50%, #172554 100%);
            transform: translateX(100%);
            transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: -20px 0 60px rgba(0, 0, 0, 0.8), -5px 0 25px rgba(59, 130, 246, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide-curtain::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #60a5fa, #c084fc, #38bdf8);
            box-shadow: 0 0 25px #60a5fa, 0 0 50px #38bdf8;
        }

        #pageExitPortal.active {
            pointer-events: auto;
        }

        #pageExitPortal.active .slide-curtain {
            transform: translateX(0%);
        }

        /* Content inside the sliding curtain */
        .curtain-content {
            text-align: center;
            color: #ffffff;
            opacity: 0;
            transform: translateX(40px) scale(0.95);
            transition: opacity 0.4s ease 0.15s, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1) 0.15s;
        }

        #pageExitPortal.active .curtain-content {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .portal-ring {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.15);
            border-top-color: #60a5fa;
            border-right-color: #a78bfa;
            animation: portalSpin 0.9s linear infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 35px rgba(59, 130, 246, 0.5);
            margin: 0 auto 1.25rem;
        }

        @keyframes portalSpin {
            0% { transform: rotate(0deg) scale(0.85); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(0.85); }
        }

        .portal-icon {
            font-size: 2.2rem;
            color: #ffffff;
            animation: pulseIcon 1.5s ease-in-out infinite alternate;
        }

        @keyframes pulseIcon {
            0% { transform: scale(0.9); opacity: 0.8; }
            100% { transform: scale(1.15); opacity: 1; filter: drop-shadow(0 0 10px #60a5fa); }
        }

        .portal-text {
            font-weight: 700;
            font-size: 1.15rem;
            color: #ffffff;
            letter-spacing: -0.2px;
            animation: fadeIn 0.4s ease;
        }

        .portal-subtext {
            color: #93c5fd;
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        /* Animated Glowing Orbs Background */
        .bg-aurora {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            animation: orbFloat 14s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #2563eb, transparent 70%);
            top: -80px;
            left: -80px;
            animation-duration: 16s;
        }

        .orb-2 {
            width: 460px;
            height: 460px;
            background: radial-gradient(circle, #7c3aed, transparent 70%);
            bottom: -100px;
            right: -100px;
            animation-duration: 20s;
        }

        .orb-3 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, #06b6d4, transparent 70%);
            top: 40%;
            left: 55%;
            animation-duration: 12s;
        }

        @keyframes orbFloat {
            0% {
                transform: translate(0, 0) scale(1);
            }
            50% {
                transform: translate(40px, 30px) scale(1.1);
            }
            100% {
                transform: translate(-30px, 50px) scale(0.95);
            }
        }

        /* Interactive Canvas for Floating Stardust Particles */
        #particleCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        /* Main Content Container with Smooth Slide & Scale Out */
        .landing-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.5s ease;
            will-change: transform, opacity;
        }

        body.is-exiting .landing-container {
            transform: translateX(-60px) scale(0.96);
            opacity: 0.2;
            filter: blur(2px);
        }

        /* Glassmorphism Hero Card */
        .glass-hero {
            background: var(--surface-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 40px rgba(59, 130, 246, 0.15);
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: heroFadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes heroFadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Animated Badge */
        .floating-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.15rem;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.35);
            border-radius: 50rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #93c5fd;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
            animation: pulseBadge 3s ease-in-out infinite;
        }

        @keyframes pulseBadge {
            0%, 100% {
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
                border-color: rgba(59, 130, 246, 0.35);
            }
            50% {
                box-shadow: 0 0 25px rgba(59, 130, 246, 0.45);
                border-color: rgba(96, 165, 250, 0.6);
            }
        }

        /* Gradient Typography */
        .hero-title {
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.18;
            font-size: clamp(2rem, 5.5vw, 3.6rem);
            margin: 1.25rem 0 1rem;
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-subtitle {
            color: #94a3b8;
            font-size: clamp(0.95rem, 2.5vw, 1.15rem);
            line-height: 1.7;
            max-width: 680px;
            margin: 0 auto 2.25rem;
        }

        /* Interactive Floating Floating Visual Books Grid */
        .feature-chips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            width: 100%;
            margin-bottom: 2.5rem;
        }

        .feature-chip {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1.15rem 1.25rem;
            text-align: left;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-chip::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), transparent);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .feature-chip:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(96, 165, 250, 0.4);
            box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.5), 0 0 20px rgba(59, 130, 246, 0.2);
        }

        .feature-chip:hover::before {
            opacity: 1;
        }

        .feature-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.85rem;
        }

        /* Action Buttons with High-Performance Glow */
        .btn-glow-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            border: none;
            padding: 0.95rem 2.25rem;
            border-radius: 50rem;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.2px;
            box-shadow: 0 10px 25px -4px rgba(37, 99, 235, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-glow-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 16px 35px -6px rgba(37, 99, 235, 0.7), 0 0 30px rgba(96, 165, 250, 0.5);
            color: #ffffff !important;
        }

        .btn-glow-primary:active {
            transform: translateY(0) scale(0.99);
        }

        /* Click Ripple Effect */
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: rippleAnimation 0.6s linear;
            pointer-events: none;
        }

        @keyframes rippleAnimation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .btn-glass-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #f1f5f9 !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.95rem 2rem;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 1.02rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            backdrop-filter: blur(10px);
            cursor: pointer;
        }

        .btn-glass-secondary:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Floating 3D Book Graphic Animation */
        .floating-book-scene {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 1.25rem;
            animation: bookFloat 4.5s ease-in-out infinite;
        }

        @keyframes bookFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-12px) rotate(4deg);
            }
        }

        .book-icon-glow {
            width: 100%;
            height: 100%;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(124, 58, 237, 0.25));
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #60a5fa;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.35);
        }

        /* Mobile specific enhancements */
        @media (max-width: 768px) {
            .landing-container {
                padding: 1.5rem 1rem 1.5rem;
            }

            .glass-hero {
                padding: 2.25rem 1.25rem;
                border-radius: 22px;
            }

            .feature-chips-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                margin-bottom: 2rem;
            }

            .cta-buttons-wrapper {
                flex-direction: column;
                width: 100%;
                gap: 0.85rem !important;
            }

            .btn-glow-primary,
            .btn-glass-secondary {
                width: 100%;
                padding: 0.85rem 1.5rem;
                font-size: 0.98rem;
            }
        }

        /* Subtle modern footer */
        .landing-footer {
            position: relative;
            z-index: 2;
            padding: 1.5rem 1rem;
            text-align: center;
            font-size: 0.8rem;
            color: #64748b;
        }

        .landing-footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .landing-footer a:hover {
            color: #60a5fa;
        }
    </style>
</head>
<body>

    <!-- ================= CINEMATIC WELCOME INTRO OVERLAY ================= -->
    <div id="introOverlay">
        <div class="intro-glow-circle"></div>
        <div class="intro-content">
            <div class="intro-icon-box">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <span class="intro-badge">
                    <i class="bi bi-stars me-1"></i> Digital Library 2026
                </span>
            </div>
            <h1 class="intro-title">
                Welcome to <br>
                <span class="gradient-text">Perpus Online</span>
            </h1>
            <p class="intro-subtext">
                Membuka jendela ilmu & naskah sastra nusantara untuk seluruh siswa...
            </p>
            <div class="intro-progress-bar">
                <div class="intro-progress-fill"></div>
            </div>
        </div>
    </div>

    <!-- ================= SMOOTH PAGE EXIT TRANSITION SLIDE CURTAIN ================= -->
    <div id="pageExitPortal">
        <div class="slide-curtain">
            <div class="curtain-content">
                <div class="portal-ring">
                    <i class="bi bi-journal-richtext portal-icon"></i>
                </div>
                <div class="portal-text">Membuka Perpustakaan Digital...</div>
                <div class="portal-subtext">Menyiapkan 75+ koleksi buku & E-Reader</div>
            </div>
        </div>
    </div>

    <!-- Dynamic Aurora Background Glow -->
    <div class="bg-aurora">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- Floating Interactive Stardust Canvas -->
    <canvas id="particleCanvas"></canvas>

    <!-- Main Landing Container -->
    <main class="landing-container text-center">
        <div class="glass-hero">
            <!-- Floating Animated Book Icon -->
            <div class="floating-book-scene">
                <div class="book-icon-glow">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            </div>

            <!-- Official Badge -->
            <div class="mb-3">
                <span class="floating-badge">
                    <span class="spinner-grow spinner-grow-sm text-primary" style="width: 8px; height: 8px;" role="status"></span>
                    <span>Perpustakaan Online Resmi SMP &bull; SMA &bull; SMK</span>
                </span>
            </div>

            <!-- Main Heading with Smooth Gradient -->
            <h1 class="hero-title">
                Selamat Datang di <br class="d-none d-sm-inline">
                <span class="gradient-text">PustakaSekolah</span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-subtitle">
                Akses <strong>75+ buku teks resmi Kurikulum Merdeka</strong> dan <strong>20 novel sastra nusantara legendaris</strong>. Nikmati pengalaman membaca digital dengan E-Reader modern langsung di layar HP atau komputer Anda.
            </p>

            <!-- 3 Highlights Cards (Ringan, Elegan & Responsif) -->
            <div class="feature-chips-grid">
                <div class="feature-chip">
                    <div class="feature-icon-wrapper" style="background: rgba(37, 99, 235, 0.15); color: #60a5fa;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <div class="fw-bold text-white mb-1" style="font-size: 0.95rem;">Optimal di Layar HP</div>
                    <div class="small text-muted" style="font-size: 0.8rem; line-height: 1.5;">Desain pas lebar layar ponsel, ringan, dan hemat kuota data.</div>
                </div>

                <div class="feature-chip">
                    <div class="feature-icon-wrapper" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div class="fw-bold text-white mb-1" style="font-size: 0.95rem;">E-Reader 0 Lag</div>
                    <div class="small text-muted" style="font-size: 0.8rem; line-height: 1.5;">Rendering instan per halaman dengan fitur Narator Suara Otomatis.</div>
                </div>

                <div class="feature-chip">
                    <div class="feature-icon-wrapper" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="fw-bold text-white mb-1" style="font-size: 0.95rem;">100% Gratis & Lengkap</div>
                    <div class="small text-muted" style="font-size: 0.8rem; line-height: 1.5;">Koleksi buku Kurikulum Merdeka resmi & novel sastra penuh.</div>
                </div>
            </div>

            <!-- Call to Actions Buttons with Fluid Page Transitions -->
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 cta-buttons-wrapper">
                <a href="{{ route('home') }}" class="btn-glow-primary transition-trigger" data-dest="{{ route('home') }}">
                    <i class="bi bi-book-half"></i>
                    <span>Mulai Membaca Sekarang</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
                
                <a href="{{ route('catalog.index') }}" class="btn-glass-secondary transition-trigger" data-dest="{{ route('catalog.index') }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Jelajahi Koleksi (75+)</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <p class="mb-1">
                <strong>PustakaSekolah</strong> &copy; 2026 &bull; Platform Literasi & E-Reader Siswa Terbuka
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('catalog.index') }}" class="transition-trigger" data-dest="{{ route('catalog.index') }}">Koleksi Buku</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="transition-trigger" data-dest="{{ route('login') }}">Masuk</a>
                <span>&bull;</span>
                <a href="{{ route('register') }}" class="transition-trigger" data-dest="{{ route('register') }}">Daftar Gratis</a>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts: Intro Animation, Fluid Page Transitions & Particles -->
    <script>
        // 1. Cinematic "Welcome to Perpus Online" Intro Handler
        document.addEventListener('DOMContentLoaded', function() {
            const intro = document.getElementById('introOverlay');
            if (intro) {
                // Biarkan animasi intro berputar anggun selama 1.8 detik lalu transisi keluar dengan smooth
                setTimeout(function() {
                    intro.classList.add('fade-out');
                    setTimeout(function() {
                        intro.style.display = 'none';
                    }, 800);
                }, 1800);
            }
        });

        // 2. Smooth Fluid Slide Page Transition on Button Click (Bebas Kaku)
        document.querySelectorAll('.transition-trigger').forEach(function(el) {
            el.addEventListener('click', function(e) {
                const destination = this.getAttribute('data-dest') || this.getAttribute('href');
                if (!destination || destination.startsWith('#') || destination.startsWith('javascript:')) return;

                e.preventDefault();

                // Efek visual klik ripple
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                const rect = this.getBoundingClientRect();
                ripple.style.width = ripple.style.height = Math.max(rect.width, rect.height) + 'px';
                ripple.style.left = (e.clientX - rect.left - rect.width / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - rect.height / 2) + 'px';
                this.appendChild(ripple);

                // Aktifkan slide transisi halus
                document.body.classList.add('is-exiting');
                const portal = document.getElementById('pageExitPortal');
                if (portal) {
                    portal.classList.add('active');
                }

                // Arahkan ke halaman tujuan setelah tirai geser menutupi layar dengan mulus (520ms)
                setTimeout(function() {
                    window.location.href = destination;
                }, 520);
            });
        });

        // 3. Stardust 60FPS Particles Canvas
        (function() {
            const canvas = document.getElementById('particleCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let particles = [];
            let w, h;

            function resize() {
                w = canvas.width = window.innerWidth;
                h = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            const particleCount = window.innerWidth < 768 ? 25 : 55;

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * w,
                    y: Math.random() * h,
                    radius: Math.random() * 1.8 + 0.6,
                    vx: (Math.random() - 0.5) * 0.4,
                    vy: (Math.random() - 0.5) * 0.4 - 0.15,
                    alpha: Math.random() * 0.6 + 0.2,
                    color: Math.random() > 0.4 ? 'rgba(96, 165, 250,' : 'rgba(167, 139, 250,'
                });
            }

            function animate() {
                ctx.clearRect(0, 0, w, h);
                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0) p.x = w;
                    if (p.x > w) p.x = 0;
                    if (p.y < 0) p.y = h;
                    if (p.y > h) p.y = 0;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color + p.alpha + ')';
                    ctx.fill();
                }
                requestAnimationFrame(animate);
            }
            animate();
        })();
    </script>
</body>
</html>
