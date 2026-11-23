@extends('layouts.app')

@section('title', 'Baca Online: ' . $book->title . ' - E-Reader Ringan PustakaSekolah')

@section('styles')
<style>
    /* Reader Layout */
    .reader-container {
        display: flex;
        flex-direction: column;
        min-height: 720px;
        background: #090d16;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #334155;
        transition: all 0.2s ease;
    }

    .reader-container.is-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        border-radius: 0;
        margin: 0;
        min-height: 100vh;
    }

    .reader-header {
        background: #1e293b;
        color: #f8fafc;
        padding: 0.65rem 1rem;
        border-bottom: 1px solid #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Reading Modes Navigation */
    .reader-mode-nav {
        display: flex;
        gap: 0.25rem;
        background: #0f172a;
        padding: 0.25rem;
        border-radius: 10px;
        border: 1px solid #334155;
    }

    .reader-mode-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        padding: 0.38rem 0.85rem;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
    }

    .reader-mode-btn:hover {
        color: #f8fafc;
    }

    .reader-mode-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.4);
    }

    /* Content Panes */
    .reader-content-pane {
        display: none;
        flex: 1;
        overflow-y: auto;
    }

    .reader-content-pane.active {
        display: flex;
        flex-direction: column;
    }

    /* Single-Page Canvas Engine (Ultra-Lightweight for Potato Devices) */
    .canvas-toolbar {
        background: #111827;
        color: #f3f4f6;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #1f2937;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .canvas-viewport-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 1rem;
        background: #030712;
        min-height: 580px;
        position: relative;
    }

    #pdfCanvas {
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        border-radius: 4px;
        max-width: 100%;
        display: block;
    }

    .canvas-loading-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(17, 24, 39, 0.92);
        color: #ffffff;
        padding: 1.25rem 2rem;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        z-index: 20;
        border: 1px solid #374151;
        pointer-events: auto;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    /* Page Navigation Input */
    .page-input {
        width: 52px;
        background: #1f2937;
        color: #ffffff;
        border: 1px solid #374151;
        border-radius: 6px;
        text-align: center;
        padding: 0.2rem 0.3rem;
        font-size: 0.85rem;
        font-weight: bold;
    }

    /* Mode Teks Ringan */
    .lite-reader-wrapper {
        padding: 1.5rem;
        max-width: 860px;
        margin: 0 auto;
        width: 100%;
        line-height: 1.75;
    }

    /* Themes for text mode */
    .theme-dark {
        background-color: #0b1120;
        color: #e2e8f0;
    }
    .theme-dark .lite-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        color: #f1f5f9;
    }
    .theme-dark .lite-text-muted {
        color: #94a3b8;
    }

    .theme-sepia {
        background-color: #fbf0d9 !important;
        color: #433422 !important;
    }
    .theme-sepia .lite-card {
        background-color: #f4e4c1 !important;
        border: 1px solid #e2cb9f !important;
        color: #3b2d1c !important;
    }
    .theme-sepia .lite-text-muted {
        color: #796041 !important;
    }

    .theme-light {
        background-color: #ffffff !important;
        color: #1e293b !important;
    }
    .theme-light .lite-card {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        color: #0f172a !important;
    }
    .theme-light .lite-text-muted {
        color: #64748b !important;
    }

    /* Notes Area */
    .notes-textarea {
        background: #1e293b;
        color: #f1f5f9;
        border: 1px solid #334155;
        border-radius: 10px;
        resize: vertical;
        width: 100%;
        padding: 0.85rem;
        font-size: 0.9rem;
        line-height: 1.6;
        min-height: 220px;
    }
    .notes-textarea:focus {
        outline: none;
        border-color: #38bdf8;
    }

    /* Kentang Mode Overrides */
    body.is-kentang-mode * {
        animation: none !important;
        transition: none !important;
        box-shadow: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }

    /* ================= TTS (AUDIO BACA OTOMATIS) STYLES ================= */
    .tts-player-bar {
        background: linear-gradient(90deg, #1e1b4b 0%, #0f172a 50%, #1e293b 100%);
        border-bottom: 2px solid #ec4899 !important;
        position: relative;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
    }
    .tts-wave-container {
        height: 18px;
        display: inline-flex;
        align-items: center;
        gap: 2.5px;
    }
    .tts-bar {
        width: 3.5px;
        border-radius: 3px;
        display: inline-block;
        background: linear-gradient(180deg, #f472b6 0%, #38bdf8 100%);
        animation: ttsWave 0.85s ease-in-out infinite alternate;
    }
    .tts-bar-1 { height: 8px; animation-delay: 0.1s; }
    .tts-bar-2 { height: 18px; animation-delay: 0.25s; }
    .tts-bar-3 { height: 13px; animation-delay: 0.15s; }
    .tts-bar-4 { height: 7px; animation-delay: 0.35s; }

    @keyframes ttsWave {
        0% { transform: scaleY(0.25); opacity: 0.45; }
        100% { transform: scaleY(1.15); opacity: 1; }
    }

    /* Paragraph Active Glowing Effect */
    .tts-paragraph-active {
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(37, 99, 235, 0.14) 100%) !important;
        border-left: 5px solid #ec4899 !important;
        padding: 0.85rem 1.15rem !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 30px rgba(236, 72, 153, 0.25), 0 0 20px rgba(56, 189, 248, 0.18) !important;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
        color: #f8fafc !important;
        position: relative;
        animation: ttsPulseGlow 3s infinite ease-in-out;
    }

    @keyframes ttsPulseGlow {
        0%, 100% {
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.2), 0 0 15px rgba(56, 189, 248, 0.15);
        }
        50% {
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.35), 0 0 25px rgba(56, 189, 248, 0.3);
        }
    }

    /* Sentence Spotlight Effect (Efek Membaca Kalimat) */
    .tts-sentence-active {
        background: linear-gradient(90deg, rgba(251, 191, 36, 0.32) 0%, rgba(245, 158, 11, 0.22) 100%) !important;
        color: #ffffff !important;
        padding: 2px 6px !important;
        border-radius: 6px !important;
        box-shadow: 0 0 14px rgba(251, 191, 36, 0.5) !important;
        text-shadow: 0 0 8px rgba(251, 191, 36, 0.3) !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
        display: inline !important;
    }

    /* Active Indicator Badge */
    .tts-live-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #f472b6;
        background: rgba(236, 72, 153, 0.15);
        border: 1px solid rgba(236, 72, 153, 0.35);
        padding: 2px 8px;
        border-radius: 50rem;
        margin-bottom: 6px;
        animation: fadeIn 0.3s ease;
    }

    /* Mobile Enhancements for E-Reader */
    @media (max-width: 768px) {
        .reader-header {
            padding: 0.6rem 0.75rem !important;
            gap: 0.5rem !important;
        }

        .reader-mode-nav {
            width: 100% !important;
            justify-content: flex-start !important;
            overflow-x: auto !important;
            padding-bottom: 0.25rem !important;
            scrollbar-width: none;
        }

        .reader-mode-nav::-webkit-scrollbar {
            display: none;
        }

        .reader-mode-btn {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap !important;
        }

        .canvas-toolbar {
            padding: 0.5rem 0.65rem !important;
            gap: 0.5rem !important;
            justify-content: space-between !important;
        }

        .canvas-toolbar .btn {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
        }

        .canvas-viewport-container {
            padding: 0.5rem 0.25rem !important;
            min-height: 480px !important;
        }

        .tts-player-bar {
            padding: 0.5rem 0.75rem !important;
            gap: 0.5rem !important;
        }

        .lite-reader-wrapper {
            padding: 1rem 0.65rem !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Header Toolbar -->
<div class="mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('catalog.show', $book) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
        </a>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 small">
            {{ $book->category->name }}
        </span>
    </div>

    <!-- Potato Mode Toggle & Native Phone Link -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('catalog.pdf', $book) }}" target="_blank" rel="noopener noreferrer" 
           class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm"
           title="Buka / Unduh berkas PDF buku di tab baru atau pembaca PDF HP">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <span>Buka / Unduh PDF</span>
        </a>
        @if($book->source_url)
            <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" 
               class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1"
               title="Rujukan Resmi SIBI Kemdikbudristek">
                <i class="bi bi-patch-check"></i>
                <span class="d-none d-md-inline">Sumber SIBI</span>
            </a>
        @endif
        
        <button type="button" id="toggleKentangBtn" onclick="toggleKentangMode()" 
                class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1">
            <i class="bi bi-cpu"></i>
            <span id="kentangBtnText">Mode Hemat RAM: MATI</span>
        </button>
    </div>
</div>

<!-- Alert Notifikasi Ramah Akses Digital -->
<div class="alert alert-dark border border-secondary border-opacity-25 py-2 px-3 rounded-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2 text-white small" style="background-color: #1e293b;">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-pill px-2.5 py-1"><i class="bi bi-lightning-charge-fill me-1"></i>Ringan & Cepat</span>
        <span>
            <strong>Akses Ringan:</strong> PDF di bawah dimuat menggunakan <strong>Kanvas 1 Halaman</strong> (hanya memuat halaman aktif ke memori sehingga peramban tetap lancar).
        </span>
    </div>
    <div class="text-white-50 small d-none d-md-block">
        <i class="bi bi-check2-circle text-primary me-1"></i>Tekan tanda panah ◄ ► pada keyboard atau tombol navigasi untuk ganti halaman.
    </div>
</div>

<!-- Main E-Reader Box -->
<div class="reader-container" id="readerContainer">
    <!-- Top Header Navigation -->
    <div class="reader-header">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <img src="{{ $book->cover_image }}" alt="Cover" class="rounded d-none d-sm-block" style="width: 26px; height: 36px; object-fit: cover;">
            <div class="text-truncate">
                <strong class="text-white small text-truncate d-block" style="max-width: 320px;">{{ $book->title }}</strong>
                <span class="text-white-50" style="font-size: 0.72rem;">{{ $book->author }} ({{ $book->year }})</span>
            </div>
        </div>

        <!-- Mode Nav Pills -->
        <div class="reader-mode-nav">
            <button class="reader-mode-btn active" id="nav-mode-canvas" onclick="switchMode('canvas')">
                <i class="bi bi-book-half"></i>
                <span>Buku Asli Lengkap (PDF)</span>
            </button>
            <button class="reader-mode-btn" id="nav-mode-lite" onclick="switchMode('lite')">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>{{ isset($novelStory) && $novelStory ? 'Naskah Cerita Penuh' : 'Silabus & Poin Bab' }}</span>
            </button>
            <button class="reader-mode-btn" id="nav-mode-iframe" onclick="switchMode('iframe')">
                <i class="bi bi-display"></i>
                <span class="d-none d-sm-inline">Viewer Cadangan</span>
            </button>
            <button class="reader-mode-btn" id="nav-mode-notes" onclick="switchMode('notes')">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-sm-inline">Catatan Siswa</span>
            </button>
        </div>

        <!-- Fullscreen Button -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-dark text-white rounded-pill px-3 py-1" onclick="toggleFullscreen()" title="Layar Penuh" style="font-size: 0.75rem;">
                <i class="bi bi-arrows-fullscreen me-1"></i>
                <span class="d-none d-sm-inline">Layar Penuh</span>
            </button>
        </div>
    </div>

    <!-- Hidden HTML5 Audio Element for Web Audio API 500% Volume Booster Pipeline -->
    <audio id="ttsAudio" preload="auto" crossorigin="anonymous" style="display: none;"></audio>

    <!-- ================= AUDIO NARATOR OTOMATIS (SUARA CEWE LEMBUT & ANGGUN + 500% VOLUME BOOSTER) ================= -->
    <div id="ttsPlayerBar" class="tts-player-bar px-3 py-2 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom" 
         style="background: linear-gradient(90deg, #1e1b4b 0%, #0f172a 50%, #1e293b 100%); border-color: #3b82f6 !important;">
        
        <!-- Info & Status -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" 
                  style="background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); color: #ffffff; font-size: 0.8rem;">
                <i class="bi bi-mic-fill"></i>
                <span>Audio Baca Otomatis</span>
            </span>
            <span class="small text-white d-flex align-items-center gap-1.5">
                <i class="bi bi-person-heart" style="color: #f472b6;"></i>
                <span class="d-none d-sm-inline" style="color: #cbd5e1; font-size: 0.8rem;">Narator: <strong id="ttsNarratorLabel" style="color: #f472b6;">Suara Cewek Lembut</strong></span>
            </span>
            <div id="ttsWaveVisualizer" class="tts-wave-container d-none align-items-center gap-1 ms-1">
                <span class="tts-bar tts-bar-1"></span>
                <span class="tts-bar tts-bar-2"></span>
                <span class="tts-bar tts-bar-3"></span>
                <span class="tts-bar tts-bar-4"></span>
            </div>
            <span id="ttsStatusText" class="small text-truncate d-none d-md-inline" style="color: #93c5fd; font-size: 0.78rem; max-width: 280px;">
                Siap membacakan naskah buku
            </span>
        </div>

        <!-- Controls: Prev, Play/Pause, Stop, Next, Model Suara, Speed & Volume Booster 500% -->
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <button type="button" id="ttsPrevBtn" class="btn btn-sm btn-dark text-white rounded-pill px-2.5 py-1" onclick="ttsPrevParagraph()" title="Paragraf Sebelumnya" style="font-size: 0.78rem;">
                <i class="bi bi-skip-backward-fill"></i>
            </button>
            
            <button type="button" id="ttsPlayBtn" class="btn btn-sm rounded-pill px-3 py-1 fw-bold text-white shadow-sm d-inline-flex align-items-center gap-1.5" 
                    onclick="ttsTogglePlay()" style="background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); font-size: 0.82rem;">
                <i class="bi bi-play-circle-fill fs-6" id="ttsPlayIcon"></i>
                <span id="ttsPlayBtnText">Mulai Baca Otomatis</span>
            </button>

            <button type="button" id="ttsStopBtn" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 d-none" onclick="ttsStop()" title="Berhenti Membaca" style="font-size: 0.78rem;">
                <i class="bi bi-stop-circle-fill"></i>
            </button>

            <button type="button" id="ttsNextBtn" class="btn btn-sm btn-dark text-white rounded-pill px-2.5 py-1" onclick="ttsNextParagraph()" title="Paragraf Berikutnya" style="font-size: 0.78rem;">
                <i class="bi bi-skip-forward-fill"></i>
            </button>

            <!-- Pilihan Model Suara Cewek Lembut -->
            <div class="d-flex align-items-center gap-1 ms-1" title="Pilih Model Karakter Suara Wanita Lembut">
                <span class="small text-muted d-none d-xl-inline" style="font-size: 0.72rem;">Model:</span>
                <select id="ttsVoiceModelSelect" class="form-select form-select-sm rounded-pill py-0 px-2 text-white bg-dark border-secondary" style="font-size: 0.75rem; width: auto; max-width: 185px; height: 26px;" onchange="ttsChangeVoiceModel(this.value)">
                    <option value="auto" selected>🌸 Cewek Lembut (Otomatis)</option>
                    <option value="gadis">🌸 Gadis (Paling Lembut & Anggun)</option>
                    <option value="google">🌷 Google ID (Jernih & Santun)</option>
                    <option value="siti">🌺 Siti (Feminin Ramah)</option>
                    <option value="damayanti">✨ Damayanti / Apple (Halus)</option>
                    <option value="booster">⚡ Audio Booster 500% (Server)</option>
                </select>
            </div>

            <!-- Speed selector -->
            <div class="d-flex align-items-center gap-1 ms-1">
                <span class="small text-muted d-none d-lg-inline" style="font-size: 0.72rem;">Tempo:</span>
                <select id="ttsRateSelect" class="form-select form-select-sm rounded-pill py-0 px-2 text-white bg-dark border-secondary" style="font-size: 0.75rem; width: auto; height: 26px;" onchange="ttsChangeRate(this.value)">
                    <option value="0.80">0.80x (Sangat Lembut)</option>
                    <option value="0.88" selected>0.88x (Lembut & Anggun)</option>
                    <option value="1.00">1.00x (Normal)</option>
                    <option value="1.15">1.15x (Cepat)</option>
                </select>
            </div>

            <!-- Volume Booster (Hingga 500%) -->
            <div class="d-flex align-items-center gap-1.5 ms-1 p-1 px-2 rounded-pill shadow-sm" 
                 style="background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(255, 255, 255, 0.15);" 
                 title="Penguat Volume Audio (Web Audio Booster hingga 500%)">
                <i class="bi bi-volume-up-fill" id="ttsVolumeIcon" style="color: #60a5fa; font-size: 0.85rem;"></i>
                <input type="range" class="form-range m-0" id="ttsVolumeSlider" min="20" max="500" step="10" value="100" 
                       style="width: 76px; height: 5px; accent-color: #3b82f6; cursor: pointer;" 
                       oninput="ttsSetVolume(this.value)">
                <span id="ttsVolumeBadge" class="badge rounded-pill fw-bold" style="font-size: 0.72rem; min-width: 44px; background: #334155; color: #f1f5f9;">
                    100%
                </span>
                
                <!-- Quick Preset Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-dark text-white rounded-circle p-0 d-flex align-items-center justify-content-center" 
                            type="button" data-bs-toggle="dropdown" style="width: 20px; height: 20px; font-size: 0.65rem;" title="Pilihan Preset Volume">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-0 shadow small py-1" style="min-width: 145px; font-size: 0.75rem;">
                        <li class="dropdown-header py-1 text-uppercase fw-bold text-muted" style="font-size: 0.65rem;">Preset Volume:</li>
                        <li><button class="dropdown-item py-1" type="button" onclick="ttsQuickVolume(50)">🔈 50% (Pelan)</button></li>
                        <li><button class="dropdown-item py-1" type="button" onclick="ttsQuickVolume(100)">🔊 100% (Normal)</button></li>
                        <li><button class="dropdown-item py-1" type="button" onclick="ttsQuickVolume(200)">⚡ 200% (2x Keras)</button></li>
                        <li><button class="dropdown-item py-1" type="button" onclick="ttsQuickVolume(350)">💥 350% (3.5x Ekstra)</button></li>
                        <li><button class="dropdown-item py-1 text-warning fw-bold" type="button" onclick="ttsQuickVolume(500)">🔥 500% (Maksimal Booster)</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- PANE 1: ULTRA-LIGHTWEIGHT SINGLE-PAGE CANVAS ENGINE (DEFAULT & OPTIMAL FOR POTATO DEVICES) -->
    <div class="reader-content-pane active" id="pane-canvas">
        <!-- Canvas Navigation Toolbar -->
        <div class="canvas-toolbar">
            <!-- Navigation Buttons & Page Counter -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold" id="prevPageBtn" onclick="prevPage()">
                    <i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">Sebelumnya</span>
                </button>
                
                <div class="d-flex align-items-center gap-1 small text-white">
                    <span>Hal</span>
                    <input type="number" id="pageNumberInput" class="page-input" value="1" min="1" max="1" onchange="jumpToPage(this.value)">
                    <span>dari <strong id="pageCountText" class="text-info">-</strong> Halaman Lengkap</span>
                </div>

                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold" id="nextPageBtn" onclick="nextPage()">
                    <span class="d-none d-sm-inline">Selanjutnya</span> <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <!-- Zoom & Fit Controls -->
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-sm btn-dark text-white rounded-pill px-2 py-1" onclick="zoomChange(-0.15)" title="Perkecil">
                    <i class="bi bi-zoom-out"></i>
                </button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold small" onclick="fitToWidth()" title="Pas Lebar Layar HP">
                    <i class="bi bi-arrows-expand me-1"></i> <span class="d-none d-sm-inline">Pas Lebar Layar</span>
                </button>
                <button type="button" class="btn btn-sm btn-dark text-white rounded-pill px-2 py-1" onclick="zoomChange(0.15)" title="Perbesar">
                    <i class="bi bi-zoom-in"></i>
                </button>
                <span class="badge bg-secondary-subtle text-light rounded-pill px-2 py-1 small ms-1" id="zoomPercentText">100%</span>
            </div>

            <!-- Document Source Badge -->
            <div class="d-none d-md-flex align-items-center gap-1">
                @if(isset($novelStory) && $novelStory)
                    <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-50 rounded-pill px-2 py-1 small">
                        <i class="bi bi-patch-check-fill me-1"></i> Edisi Lengkap Perpustakaan
                    </span>
                @else
                    <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-40 rounded-pill px-2 py-1 small">
                        <i class="bi bi-patch-check-fill me-1"></i> Buku Resmi Kemendikbudristek (Lengkap)
                    </span>
                @endif
            </div>
        </div>

        <!-- Canvas Viewport -->
        <div class="canvas-viewport-container" id="canvasWrapper">
            <!-- Loading Indicator -->
            <div class="canvas-loading-spinner" id="canvasLoader">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat halaman...</span>
                </div>
                <div class="small fw-semibold" id="canvasLoaderText">Menyiapkan halaman PDF ringan...</div>
                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-0.5 mt-1" style="font-size: 0.75rem;" onclick="switchMode('lite')">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Buka Naskah Teks (0 Detik)
                </button>
            </div>

            <!-- Single Canvas Element -->
            <canvas id="pdfCanvas"></canvas>

            <!-- Embedded Interactive PDF Viewer Frame -->
            <iframe id="canvasFallbackIframe" class="w-100 h-100 border-0 d-none" style="min-height: 650px;" allow="fullscreen"></iframe>

            <!-- Error Notification if PDF failed to fetch -->
            <div class="alert alert-warning border-0 p-4 text-center d-none mt-3" id="canvasErrorAlert" style="max-width: 520px; border-radius: 14px;">
                <i class="bi bi-exclamation-triangle-fill fs-2 text-warning mb-2 d-block"></i>
                <h6 class="fw-bold">Penampil Kanvas Perlu Alternatif</h6>
                <p class="small text-muted mb-3">
                    Peramban Anda dapat menampilkan buku ini secara instan melalui <strong>Rangkuman & Teks Lengkap (0 Lag)</strong> atau unduh dokumen PDF langsung.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold" onclick="switchMode('lite')">
                        <i class="bi bi-journal-bookmark-fill me-1"></i> Buka Rangkuman & Teks (Instan)
                    </button>
                    <a href="{{ route('catalog.pdf', $book) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka PDF Langsung
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- PANE 2: RANGKUMAN & SINOPSIS BAB LENGKAP -->
    <div class="reader-content-pane theme-dark" id="pane-lite">
        <div class="py-2 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1) !important;">
            <div class="d-flex align-items-center gap-1">
                <span class="small lite-text-muted me-1" style="font-size: 0.75rem;">Tema:</span>
                <button type="button" class="btn btn-sm btn-dark py-0 px-2 rounded-pill" onclick="setReadingTheme('dark')">🌙 Gelap</button>
                <button type="button" class="btn btn-sm py-0 px-2 rounded-pill" style="background: #fbf0d9; color: #433422; border: 1px solid #e2cb9f;" onclick="setReadingTheme('sepia')">📜 Sepia</button>
                <button type="button" class="btn btn-sm btn-light py-0 px-2 rounded-pill" onclick="setReadingTheme('light')">☀️ Terang</button>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="small lite-text-muted me-1" style="font-size: 0.75rem;">Huruf:</span>
                <button type="button" class="btn btn-sm btn-secondary py-0 px-2 rounded-pill fw-bold" onclick="adjustFontSize(-2)">A-</button>
                <button type="button" class="btn btn-sm btn-secondary py-0 px-2 rounded-pill fw-bold" onclick="adjustFontSize(0)">A</button>
                <button type="button" class="btn btn-sm btn-secondary py-0 px-2 rounded-pill fw-bold" onclick="adjustFontSize(2)">A+</button>
            </div>
        </div>

        @php
            $desc = $book->description ?? '';
            $sinopsis = '';
            if (preg_match('/Sinopsis Buku:\s*(.*?)(?=Rangkuman Pembahasan|$)/su', $desc, $sMatch)) {
                $sinopsis = trim($sMatch[1]);
            } else {
                $parts = explode('Rangkuman', $desc, 2);
                $sinopsis = trim($parts[0]);
            }

            $chapters = [];
            if (preg_match_all('/(Bab \d+|Chapter \d+):\s*([^\n\r]+)(?:\r?\n• Membahas:\s*([^\n\r]+))?(?:\r?\n• Poin Penting:\s*([^\n\r]+))?/u', $desc, $cMatches, PREG_SET_ORDER)) {
                foreach ($cMatches as $m) {
                    $chapters[] = [
                        'label' => trim($m[1]),
                        'title' => trim($m[2]),
                        'membahas' => trim($m[3] ?? ''),
                        'poin' => trim($m[4] ?? ''),
                    ];
                }
            }
        @endphp

        <div class="lite-reader-wrapper" id="liteReaderContent" style="font-size: 16px;">
            @if(isset($novelStory) && $novelStory)
                <!-- NOVEL COMPLETE STORY READING PANE -->
                <div class="lite-card p-3 p-md-4 rounded-3 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $book->cover_image }}" alt="Cover" class="rounded shadow-sm" style="width: 60px; height: 86px; object-fit: cover;">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span class="badge bg-primary rounded-pill px-2 py-1 small">
                                    <i class="bi bi-book-fill me-1"></i> Karya Sastra Fiksi Pilihan
                                </span>
                                <span class="badge bg-success rounded-pill px-2 py-1 small">
                                    <i class="bi bi-journal-check me-1"></i> {{ count($novelStory['chapters']) }} Bab Naskah Lengkap
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $book->title }}</h4>
                            <div class="small lite-text-muted">
                                Penulis: <strong>{{ $book->author }}</strong> &bull; Penerbit: {{ $book->publisher }} ({{ $book->year }})
                            </div>
                        </div>
                    </div>

                    @if(!empty($novelStory['prologue']))
                        <div class="p-3 rounded-3 mb-3" style="background: rgba(37, 99, 235, 0.1); border-left: 4px solid #2563eb;">
                            <div class="fw-bold text-primary small mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-chat-quote-fill"></i> PROLOG & PENGANTAR CERITA:
                            </div>
                            <p class="mb-0 small" style="line-height: 1.75; font-style: italic;">
                                {{ $novelStory['prologue'] }}
                            </p>
                        </div>
                    @endif

                    @if(!empty($novelStory['characters']))
                        <div class="p-3 rounded-3" style="background: rgba(245, 158, 11, 0.08); border-left: 4px solid #f59e0b;">
                            <div class="fw-bold text-warning small mb-2 d-flex align-items-center gap-1">
                                <i class="bi bi-people-fill"></i> TOKOH UTAMA & KARAKTER:
                            </div>
                            <div class="row g-2">
                                @foreach($novelStory['characters'] as $charName => $charDesc)
                                    <div class="col-md-6">
                                        <div class="small">
                                            <strong class="text-white">• {{ $charName }}:</strong>
                                            <span class="lite-text-muted">{{ $charDesc }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- NOVEL CHAPTERS -->
                <div class="d-flex flex-column gap-4 mb-4">
                    @foreach($novelStory['chapters'] as $ch)
                        <div class="lite-card p-3 p-md-4 rounded-3 border-start border-4 border-primary tts-chapter-card" id="chapter-{{ $loop->index }}">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary px-3 py-1 rounded-pill fw-bold">{{ $ch['number'] }}</span>
                                    <h5 class="fw-bold text-white mb-0">{{ $ch['title'] }}</h5>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 text-nowrap d-inline-flex align-items-center gap-1.5 shadow-sm" 
                                        onclick="ttsPlayFromChapter({{ $loop->index }})" style="font-size: 0.75rem;">
                                    <i class="bi bi-volume-up-fill" style="color: #f472b6;"></i>
                                    <span>Dengarkan Bab Ini</span>
                                </button>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                @foreach($ch['paragraphs'] as $para)
                                    <p class="mb-0 small tts-paragraph" style="line-height: 1.85; text-indent: 1.75rem;">
                                        {{ $para }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(!empty($novelStory['epilogue']))
                    <div class="lite-card p-3 p-md-4 rounded-3 mb-4 border-start border-4 border-success" style="background: rgba(16, 185, 129, 0.08);">
                        <div class="fw-bold text-success small mb-2 d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle-fill"></i> EPILOG & PESAN LITERASI:
                        </div>
                        <p class="mb-0 small" style="line-height: 1.75; font-style: italic;">
                            {{ $novelStory['epilogue'] }}
                        </p>
                    </div>
                @endif
            @else
                <!-- TEXTBOOK BANNER & SYLLABUS -->
                <div class="alert alert-info bg-opacity-10 border border-info border-opacity-25 text-light rounded-3 mb-4 d-flex align-items-center justify-content-between p-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                        <span class="small">
                            Tab ini menampilkan silabus dan poin penting materi bab. Untuk membaca <strong>buku fisik asli secara utuh dari halaman awal sampai akhir</strong>, silakan buka tab <strong>Buku Asli Lengkap (PDF)</strong> di atas.
                        </span>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 text-nowrap" onclick="switchMode('canvas')">
                        <i class="bi bi-book-half me-1"></i> Buka Buku Asli Lengkap
                    </button>
                </div>

                <!-- Header Ringkasan & Sinopsis -->
                <div class="lite-card p-3 p-md-4 rounded-3 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $book->cover_image }}" alt="Cover" class="rounded shadow-sm" style="width: 56px; height: 78px; object-fit: cover;">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span class="badge bg-success rounded-pill px-2 py-1 small">
                                    <i class="bi bi-mortarboard-fill me-1"></i> Kurikulum Resmi Kemendikbud
                                </span>
                                <span class="badge bg-primary rounded-pill px-2 py-1 small">
                                    <i class="bi bi-journal-bookmark me-1"></i> {{ count($chapters) }} Bab Pembahasan
                                </span>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $book->title }}</h4>
                            <div class="small lite-text-muted">
                                Penulis: <strong>{{ $book->author }}</strong> &bull; Penerbit: {{ $book->publisher }} ({{ $book->year }})
                            </div>
                        </div>
                    </div>

                    @if($sinopsis)
                        <div class="p-3 rounded-3 mb-2" style="background: rgba(37, 99, 235, 0.1); border-left: 4px solid #2563eb;">
                            <div class="fw-bold text-primary small mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-file-earmark-text-fill"></i> SINOPSIS UMUM BUKU:
                            </div>
                            <p class="mb-0 small" style="white-space: pre-line; line-height: 1.7;">
{{ $sinopsis }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Rangkuman Pembahasan & Poin Penting Tiap Bab -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2">
                            <i class="bi bi-bookmarks-fill text-warning"></i>
                            <span>Silabus Pembahasan & Poin Penting Tiap Bab</span>
                        </h5>
                        <span class="badge bg-secondary rounded-pill small">{{ count($chapters) }} Bab</span>
                    </div>

                    @if(count($chapters) > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($chapters as $chap)
                                <div class="lite-card p-3 p-md-4 rounded-3 border-start border-4 border-info tts-chapter-card" id="textbook-chapter-{{ $loop->index }}">
                                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 rounded-pill px-3 py-1 fw-bold">
                                            <i class="bi bi-bookmark-fill me-1"></i> {{ $chap['label'] }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 text-nowrap d-inline-flex align-items-center gap-1.5 shadow-sm" 
                                                onclick="ttsPlayFromChapter({{ $loop->index }})" style="font-size: 0.75rem;">
                                            <i class="bi bi-volume-up-fill" style="color: #f472b6;"></i>
                                            <span>Dengarkan Bab Ini</span>
                                        </button>
                                    </div>

                                    <h6 class="fw-bold text-white mb-3 fs-6 tts-paragraph">
                                        {{ $chap['title'] }}
                                    </h6>

                                    @if($chap['membahas'])
                                        <div class="p-3 rounded-3 mb-2" style="background: rgba(14, 165, 233, 0.08); border-left: 3px solid #0ea5e9;">
                                            <div class="fw-bold text-info small mb-1 d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                                <i class="bi bi-book-half"></i> APA YANG DIBAHAS DI BAB INI:
                                            </div>
                                            <p class="mb-0 small tts-paragraph" style="line-height: 1.65;">
                                                {{ $chap['membahas'] }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($chap['poin'])
                                        <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.08); border-left: 3px solid #10b981;">
                                            <div class="fw-bold text-success small mb-1 d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                                <i class="bi bi-lightbulb-fill"></i> POIN-POIN PENTING MATERI:
                                            </div>
                                            <p class="mb-0 small tts-paragraph" style="line-height: 1.65;">
                                                {{ $chap['poin'] }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="lite-card p-4 rounded-3 text-center">
                            <p class="mb-0 small" style="white-space: pre-line; line-height: 1.7;">
                                {{ $book->description }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            @if($book->source_url)
                <div class="lite-card p-3 rounded-3 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <div>
                        <div class="fw-bold small"><i class="bi bi-patch-check-fill text-primary me-1"></i> Rujukan SIBI Kemdikbudristek</div>
                        <div class="small lite-text-muted" style="font-size: 0.78rem;">Portal resmi Sistem Informasi Perbukuan Indonesia.</div>
                    </div>
                    <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap">
                        Buka Halaman SIBI <i class="bi bi-arrow-up-right small ms-1"></i>
                    </a>
                </div>
            @endif

            @if($relatedBooks->count() > 0)
                <div class="lite-card p-3 rounded-3">
                    <div class="fw-bold small mb-2"><i class="bi bi-bookmarks me-1"></i> Buku Terkait ({{ $book->category->name }}):</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($relatedBooks as $rb)
                            <a href="{{ route('catalog.read', $rb) }}" class="btn btn-sm btn-outline-secondary rounded-pill text-nowrap" style="font-size: 0.8rem;">
                                <i class="bi bi-book me-1"></i> {{ $rb->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- PANE 3: MODE IFRAME CADANGAN (Google Docs / Native Browser) -->
    <div class="reader-content-pane" id="pane-iframe">
        <div class="p-2 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: #1e293b; border-color: #334155 !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary rounded-pill small">Penampil Iframe</span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-dark text-white dropdown-toggle rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                        <i class="bi bi-gear me-1"></i> Ganti Mesin Iframe
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow small">
                        <li>
                            <button class="dropdown-item py-2" onclick="setIframeEngine('gdocs')">
                                <i class="bi bi-google text-primary me-2"></i> Google Docs Viewer (Kompresi Web)
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item py-2" onclick="setIframeEngine('native')">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Native Browser PDF
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('catalog.pdf', $book) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-light rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Full Tab PDF
            </a>
        </div>

        <div style="flex: 1; display: flex; flex-direction: column; min-height: 580px; background: #000;">
            @php
                $pdfStreamUrl = route('catalog.pdf', $book);
            @endphp
            <iframe 
                data-src="{{ $pdfStreamUrl }}#toolbar=1&navpanes=0&scrollbar=1" 
                class="w-100 h-100 border-0" 
                id="rawIframe"
                title="Buku {{ $book->title }}"
                allow="fullscreen"
                style="min-height: 580px;">
            </iframe>
        </div>
    </div>

    <!-- PANE 4: CATATAN SISWA -->
    <div class="reader-content-pane theme-dark p-3 p-md-4" id="pane-notes">
        <div class="lite-reader-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Catatan Siswa</h5>
                    <small class="lite-text-muted">Tersimpan otomatis di peramban tanpa membebani kuota atau server.</small>
                </div>
                <span id="saveStatus" class="badge bg-success-subtle text-success">Tersimpan</span>
            </div>

            <textarea class="notes-textarea mb-3" id="studentNotes" 
                      placeholder="Tulis ringkasan materi, rumus penting, atau tugas untuk buku '{{ $book->title }}' di sini..."></textarea>
            
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="copyNotes()">
                    <i class="bi bi-clipboard me-1"></i> Salin Catatan
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="clearNotes()">
                    <i class="bi bi-trash me-1"></i> Hapus Catatan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Mozilla PDF.js Local Engine (Kecepatan Tinggi & Bebas Blokir CORS Worker) -->
<script src="{{ asset('vendor/pdfjs/pdf.min.js') }}"></script>
<script>
    // Inisialisasi worker PDF.js lokal (Same-Origin)
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('vendor/pdfjs/pdf.worker.min.js') }}";
    }

    const bookId = "{{ $book->id }}";
    const pdfUrl = "{{ $effectivePdfUrl ?? route('catalog.pdf', $book) }}";
    const localStreamUrl = "{{ route('catalog.pdf', $book) }}";
    const gdocsUrl = "https://docs.google.com/viewer?url=" + encodeURIComponent("{{ route('catalog.pdf', $book) }}") + "&embedded=true";

    // PDF Canvas State & Performance Cache
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let currentRenderTask = null;
    let activePageProxy = null;
    let zoomScale = 1.0;
    let fitMode = 'width'; // 'width' or 'manual'
    let isIframeLoaded = false;
    let currentFontSize = 16;
    const pageProxyCache = new Map();

    const canvas = document.getElementById('pdfCanvas');
    const ctx = canvas ? canvas.getContext('2d', { alpha: false }) : null;
    const canvasLoader = document.getElementById('canvasLoader');
    const canvasLoaderText = document.getElementById('canvasLoaderText');
    const pageNumInput = document.getElementById('pageNumberInput');
    const pageCountText = document.getElementById('pageCountText');
    const zoomPercentText = document.getElementById('zoomPercentText');
    const errorAlert = document.getElementById('canvasErrorAlert');

    /**
     * Get or fetch page proxy with cache
     */
    async function getCachedPage(num) {
        if (!pdfDoc) return null;
        if (pageProxyCache.has(num)) {
            return pageProxyCache.get(num);
        }
        const page = await pdfDoc.getPage(num);
        // Keep small cache (max 5 pages) to prevent excessive RAM consumption
        if (pageProxyCache.size >= 5) {
            const firstKey = pageProxyCache.keys().next().value;
            const oldPage = pageProxyCache.get(firstKey);
            if (oldPage && typeof oldPage.cleanup === 'function') {
                oldPage.cleanup();
            }
            pageProxyCache.delete(firstKey);
        }
        pageProxyCache.set(num, page);
        return page;
    }

    /**
     * Pre-cache adjacent pages (Next / Prev) for zero-latency page flips
     */
    function prefetchAdjacentPages(current) {
        if (!pdfDoc) return;
        if (current + 1 <= pdfDoc.numPages && !pageProxyCache.has(current + 1)) {
            getCachedPage(current + 1).catch(() => {});
        }
        if (current - 1 >= 1 && !pageProxyCache.has(current - 1)) {
            getCachedPage(current - 1).catch(() => {});
        }
    }

    /**
     * Render specified page onto the single canvas with maximum speed & smoothness
     */
    async function renderPage(num) {
        if (!pdfDoc || !canvas || !ctx) return;

        // Cancel running render task if user flipped quickly
        if (currentRenderTask) {
            try {
                currentRenderTask.cancel();
            } catch (e) {}
            currentRenderTask = null;
        }

        pageRendering = true;
        if (canvasLoader) canvasLoader.classList.remove('d-none');
        if (canvasLoaderText) canvasLoaderText.textContent = 'Menampilkan hal ' + num + '...';

        try {
            const page = await getCachedPage(num);
            if (!page) throw new Error('Page not found');
            activePageProxy = page;

            const wrapper = document.getElementById('canvasWrapper');
            const availableWidth = wrapper ? (wrapper.clientWidth - 32) : 750;

            let viewport = page.getViewport({ scale: 1.0 });

            if (fitMode === 'width' && availableWidth > 120) {
                zoomScale = Math.min(2.5, Math.max(0.6, availableWidth / viewport.width));
            }

            viewport = page.getViewport({ scale: zoomScale });

            // Balanced DPR for lightning speed and crisp text (1.25 on high-DPI is buttery smooth)
            const dpr = Math.min(window.devicePixelRatio || 1, 1.35);
            canvas.height = Math.floor(viewport.height * dpr);
            canvas.width = Math.floor(viewport.width * dpr);
            canvas.style.width = Math.floor(viewport.width) + 'px';
            canvas.style.height = Math.floor(viewport.height) + 'px';

            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            // Clean background for fast rendering
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, viewport.width, viewport.height);

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
                background: 'rgb(255,255,255)',
            };

            currentRenderTask = page.render(renderContext);

            await currentRenderTask.promise;
            currentRenderTask = null;
            pageRendering = false;

            if (canvasLoader) canvasLoader.classList.add('d-none');
            if (zoomPercentText) zoomPercentText.textContent = Math.round(zoomScale * 100) + '%';
            if (pageNumInput) pageNumInput.value = num;

            // Prefetch next and previous pages in background
            prefetchAdjacentPages(num);

            if (pageNumPending !== null) {
                const nextNum = pageNumPending;
                pageNumPending = null;
                renderPage(nextNum);
            }
        } catch (err) {
            if (err && err.name === 'RenderingCancelledException') {
                return; // Normal cancellation on fast page flip
            }
            pageRendering = false;
            if (canvasLoader) canvasLoader.classList.add('d-none');
            console.warn('Render page error:', err);
        }
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function prevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    function nextPage() {
        if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }

    function jumpToPage(val) {
        const target = parseInt(val, 10);
        if (!pdfDoc || isNaN(target)) return;
        if (target >= 1 && target <= pdfDoc.numPages) {
            pageNum = target;
            queueRenderPage(pageNum);
        } else {
            if (pageNumInput) pageNumInput.value = pageNum;
        }
    }

    function fitToWidth() {
        fitMode = 'width';
        queueRenderPage(pageNum);
    }

    function zoomChange(delta) {
        fitMode = 'manual';
        zoomScale = Math.min(2.5, Math.max(0.5, zoomScale + delta));
        queueRenderPage(pageNum);
    }

    /**
     * Load PDF with high performance Mozilla PDF.js Engine using streaming & HTTP Range Requests
     */
    async function initPdfCanvas() {
        if (pdfDoc) return;
        if (canvasLoader) canvasLoader.classList.remove('d-none');
        if (canvasLoaderText) canvasLoaderText.textContent = 'Menghubungkan dokumen PDF...';

        if (typeof pdfjsLib === 'undefined') {
            console.error('PDF.js library is not loaded');
            activateFallbackViewer();
            return;
        }

        // Try direct range loading first (0.5s instant page 1 render for 20MB+ files)
        try {
            const loadingTask = pdfjsLib.getDocument({
                url: pdfUrl,
                cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                cMapPacked: true,
                disableRange: false,
                disableStream: false,
                disableAutoFetch: true, // Don't download all pages at once; fetch only active & requested pages
                rangeChunkSize: 65536,  // 64KB lightweight chunking for ultra-fast startup
            });

            loadingTask.onProgress = function(progress) {
                if (progress.total > 0) {
                    const pct = Math.round((progress.loaded / progress.total) * 100);
                    if (canvasLoaderText) canvasLoaderText.textContent = 'Memuat naskah buku... ' + pct + '%';
                }
            };

            const pdf = await loadingTask.promise;
            pdfDoc = pdf;
            if (pageCountText) pageCountText.textContent = pdf.numPages;
            if (pageNumInput) pageNumInput.max = pdf.numPages;
            renderPage(pageNum);
            return;
        } catch (rangeError) {
            console.warn('Direct range loading failed, trying fallback stream:', rangeError);
        }

        // Secondary attempt: if direct range loading was blocked (e.g. adblocker or CORS redirect), try fetch with local fallback
        try {
            if (canvasLoaderText) canvasLoaderText.textContent = 'Mengunduh naskah cadangan...';
            const targetFetchUrl = (pdfUrl !== localStreamUrl) ? pdfUrl : localStreamUrl;
            const response = await fetch(targetFetchUrl);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status + ' ' + response.statusText);
            }

            const contentLength = response.headers.get('content-length');
            const totalBytes = contentLength ? parseInt(contentLength, 10) : 0;
            let loadedBytes = 0;

            const reader = response.body.getReader();
            const chunks = [];

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;
                chunks.push(value);
                loadedBytes += value.length;

                if (totalBytes > 0) {
                    const pct = Math.round((loadedBytes / totalBytes) * 100);
                    if (canvasLoaderText) canvasLoaderText.textContent = 'Memuat naskah buku... ' + pct + '%';
                }
            }

            const allBytes = new Uint8Array(loadedBytes);
            let offset = 0;
            for (const chunk of chunks) {
                allBytes.set(chunk, offset);
                offset += chunk.length;
            }

            const loadingTask = pdfjsLib.getDocument({
                data: allBytes,
                cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                cMapPacked: true,
            });

            const pdf = await loadingTask.promise;
            pdfDoc = pdf;
            if (pageCountText) pageCountText.textContent = pdf.numPages;
            if (pageNumInput) pageNumInput.max = pdf.numPages;
            renderPage(pageNum);
        } catch (error) {
            console.error('All PDF canvas loaders failed:', error);
            activateFallbackViewer();
        }
    }

    function activateFallbackViewer() {
        if (canvasLoader) canvasLoader.classList.add('d-none');
        if (canvas) canvas.classList.add('d-none');
        const fallbackIframe = document.getElementById('canvasFallbackIframe');
        if (fallbackIframe) {
            fallbackIframe.classList.remove('d-none');
            fallbackIframe.style.display = 'block';
            fallbackIframe.style.minHeight = '700px';
            if (!fallbackIframe.src || fallbackIframe.src === 'about:blank') {
                fallbackIframe.src = pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1';
            }
        }
        // Don't show confusing error alert if iframe fallback is available
        if (errorAlert) errorAlert.classList.add('d-none');
    }

    // Keyboard navigation (Arrow keys / PageUp / PageDown)
    document.addEventListener('keydown', function (e) {
        const activePane = document.getElementById('pane-canvas');
        if (!activePane || !activePane.classList.contains('active')) return;
        if (['input', 'textarea'].includes(document.activeElement.tagName.toLowerCase())) return;

        if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
            e.preventDefault();
            prevPage();
        } else if (e.key === 'ArrowRight' || e.key === 'PageDown' || e.key === ' ') {
            e.preventDefault();
            nextPage();
        }
    });

    // Touch Swipe Navigation on Mobile
    let touchStartX = 0;
    let touchEndX = 0;
    const canvasWrapper = document.getElementById('canvasWrapper');
    if (canvasWrapper) {
        canvasWrapper.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        canvasWrapper.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
    }

    function handleSwipe() {
        const swipeDistance = touchEndX - touchStartX;
        if (Math.abs(swipeDistance) > 75) {
            if (swipeDistance < 0) {
                nextPage(); // swipe left -> next page
            } else {
                prevPage(); // swipe right -> prev page
            }
        }
    }

    // Window resize debounced re-fit
    let resizeTimer = null;
    window.addEventListener('resize', function () {
        if (fitMode === 'width' && pdfDoc) {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                renderPage(pageNum);
            }, 250);
        }
    });

    // 2. Mode Switcher
    function switchMode(mode) {
        document.querySelectorAll('.reader-content-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.reader-mode-btn').forEach(el => {
            el.classList.remove('active');
        });

        if (mode === 'canvas') {
            document.getElementById('pane-canvas').classList.add('active');
            document.getElementById('nav-mode-canvas').classList.add('active');
            if (!pdfDoc) initPdfCanvas();
        } else if (mode === 'lite') {
            document.getElementById('pane-lite').classList.add('active');
            document.getElementById('nav-mode-lite').classList.add('active');
        } else if (mode === 'iframe') {
            document.getElementById('pane-iframe').classList.add('active');
            document.getElementById('nav-mode-iframe').classList.add('active');
            loadRawIframe();
        } else if (mode === 'notes') {
            document.getElementById('pane-notes').classList.add('active');
            document.getElementById('nav-mode-notes').classList.add('active');
        }
    }

    // Iframe Engine Switcher
    function loadRawIframe() {
        const frame = document.getElementById('rawIframe');
        if (frame && !isIframeLoaded) {
            frame.src = frame.getAttribute('data-src');
            isIframeLoaded = true;
        }
    }

    function setIframeEngine(engine) {
        const frame = document.getElementById('rawIframe');
        if (!frame) return;
        isIframeLoaded = true;
        if (engine === 'gdocs') {
            frame.src = gdocsUrl;
        } else {
            frame.src = pdfUrl + "#toolbar=1&navpanes=0&scrollbar=1";
        }
    }

    // 3. Text Reading Themes & Font Size
    function setReadingTheme(theme) {
        const litePane = document.getElementById('pane-lite');
        if (!litePane) return;
        litePane.classList.remove('theme-dark', 'theme-sepia', 'theme-light');
        litePane.classList.add('theme-' + theme);
        localStorage.setItem('reader_theme', theme);
    }

    function adjustFontSize(delta) {
        const content = document.getElementById('liteReaderContent');
        if (!content) return;
        if (delta === 0) {
            currentFontSize = 16;
        } else {
            currentFontSize = Math.min(26, Math.max(13, currentFontSize + delta));
        }
        content.style.fontSize = currentFontSize + 'px';
        localStorage.setItem('reader_font_size', currentFontSize);
    }

    // 4. Potato Mode Global Toggle
    function toggleKentangMode() {
        const isKentang = document.body.classList.toggle('is-kentang-mode');
        const btnText = document.getElementById('kentangBtnText');
        const toggleBtn = document.getElementById('toggleKentangBtn');

        if (isKentang) {
            localStorage.setItem('mode_kentang', '1');
            if (btnText) btnText.textContent = 'Mode Kentang: AKTIF (Super Ringan)';
            if (toggleBtn) {
                toggleBtn.classList.remove('btn-outline-warning');
                toggleBtn.classList.add('btn-warning', 'text-dark');
            }
        } else {
            localStorage.setItem('mode_kentang', '0');
            if (btnText) btnText.textContent = 'Mode Kentang: MATI';
            if (toggleBtn) {
                toggleBtn.classList.add('btn-outline-warning');
                toggleBtn.classList.remove('btn-warning', 'text-dark');
            }
        }
    }

    // 5. Fullscreen Toggle
    function toggleFullscreen() {
        const container = document.getElementById('readerContainer');
        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            }
            container.classList.add('is-fullscreen');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            container.classList.remove('is-fullscreen');
        }
        setTimeout(function () {
            fitToWidth();
        }, 300);
    }

    document.addEventListener('fullscreenchange', function () {
        const container = document.getElementById('readerContainer');
        if (!document.fullscreenElement && container) {
            container.classList.remove('is-fullscreen');
            setTimeout(fitToWidth, 200);
        }
    });

    // 6. Catatan Siswa (LocalStorage)
    const notesKey = 'library_notes_book_' + bookId;
    const notesEl = document.getElementById('studentNotes');
    const saveStatus = document.getElementById('saveStatus');

    if (notesEl) {
        const savedNotes = localStorage.getItem(notesKey);
        if (savedNotes) notesEl.value = savedNotes;

        let timeout = null;
        notesEl.addEventListener('input', function () {
            saveStatus.textContent = 'Menyimpan...';
            saveStatus.className = 'badge bg-warning-subtle text-warning';

            clearTimeout(timeout);
            timeout = setTimeout(function () {
                localStorage.setItem(notesKey, notesEl.value);
                saveStatus.textContent = 'Tersimpan';
                saveStatus.className = 'badge bg-success-subtle text-success';
            }, 500);
        });
    }

    function copyNotes() {
        if (!notesEl) return;
        navigator.clipboard.writeText(notesEl.value).then(function() {
            alert('Catatan berhasil disalin ke clipboard!');
        });
    }

    function clearNotes() {
        if (!confirm('Apakah Anda yakin ingin menghapus catatan untuk buku ini?')) return;
        if (notesEl) {
            notesEl.value = '';
            localStorage.removeItem(notesKey);
            saveStatus.textContent = 'Dihapus';
            saveStatus.className = 'badge bg-secondary-subtle text-secondary';
        }
    }

    // =========================================================================
    // 7. FITUR BACA OTOMATIS (TEXT-TO-SPEECH - SUARA CEWE LEMBUT & 500% BOOSTER)
    // =========================================================================
    let ttsVoices = [];
    let ttsFemaleVoice = null;
    let ttsVoiceProfile = null;
    let ttsVoiceModel = localStorage.getItem('tts_voice_model') || 'auto';
    let ttsUtterance = null;
    let ttsParagraphEls = [];
    let ttsCurrentIdx = -1;
    let ttsCurrentSentences = [];
    let ttsSentenceIdx = -1;
    let ttsIsPlaying = false;
    let ttsIsPaused = false;
    let ttsRate = 0.88; // Default tempo lembut, tenang, dan sangat anggun
    let ttsPauseTimeout = null;
    let ttsSpeechKeepAliveTimer = null;

    // Koleksi Model Karakter Suara Wanita Lembut (Indonesian Female Neural Voices)
    const FEMALE_VOICE_PROFILES = [
        {
            key: 'gadis',
            name: 'Gadis Natural (Paling Lembut & Anggun)',
            badge: '🌸 Gadis Natural',
            pitch: 1.05,
            match: function(v) {
                const n = v.name.toLowerCase();
                const l = (v.lang || '').toLowerCase();
                return (l.startsWith('id') || n.includes('indonesia')) && 
                       (n.includes('gadis') || (n.includes('natural') && !n.includes('andika') && !n.includes('ardhi')));
            }
        },
        {
            key: 'google',
            name: 'Google Bahasa Indonesia (Jernih & Santun)',
            badge: '🌷 Google ID',
            pitch: 1.06,
            match: function(v) {
                const n = v.name.toLowerCase();
                const l = (v.lang || '').toLowerCase();
                return (n.includes('google') && (l.startsWith('id') || n.includes('indonesia')));
            }
        },
        {
            key: 'siti',
            name: 'Siti (Feminin Ramah & Menenangkan)',
            badge: '🌺 Siti Natural',
            pitch: 1.04,
            match: function(v) {
                const n = v.name.toLowerCase();
                const l = (v.lang || '').toLowerCase();
                return (l.startsWith('id') || n.includes('indonesia')) && n.includes('siti');
            }
        },
        {
            key: 'damayanti',
            name: 'Damayanti / Apple (Halus & Bersih)',
            badge: '✨ Damayanti',
            pitch: 1.05,
            match: function(v) {
                const n = v.name.toLowerCase();
                const l = (v.lang || '').toLowerCase();
                return (l.startsWith('id') || n.includes('indonesia')) && (n.includes('damayanti') || n.includes('siri'));
            }
        },
        {
            key: 'female_id',
            name: 'Suara Wanita Indonesia Standar',
            badge: '🎀 Wanita ID',
            pitch: 1.06,
            match: function(v) {
                const n = v.name.toLowerCase();
                const l = (v.lang || '').toLowerCase();
                return (l.startsWith('id') || n.includes('indonesia')) && 
                       (n.includes('female') || n.includes('wanita') || n.includes('perempuan'));
            }
        },
        {
            key: 'any_id',
            name: 'Bahasa Indonesia Sistem',
            badge: '🇮🇩 Bahasa ID',
            pitch: 1.08, // Sedikit diangkat agar vokal terasa lebih feminin dan lembut
            match: function(v) {
                const l = (v.lang || '').toLowerCase().replace('_', '-');
                const n = v.name.toLowerCase();
                return l.startsWith('id') || n.includes('indonesia');
            }
        }
    ];

    // Web Audio API Pipeline for 500% Volume Booster
    let ttsVolumeMultiplier = 1.0; // 0.2 (20%) up to 5.0 (500%)
    let ttsAudioCtx = null;
    let ttsGainNode = null;
    let ttsCompressorNode = null;
    let ttsAudioSourceNode = null;
    let ttsAudioEl = null;

    function initAudioBooster() {
        if (!ttsAudioEl) {
            ttsAudioEl = document.getElementById('ttsAudio');
        }
        if (!ttsAudioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                ttsAudioCtx = new AudioContextClass();
                ttsGainNode = ttsAudioCtx.createGain();
                ttsCompressorNode = ttsAudioCtx.createDynamicsCompressor();

                // Dynamic compressor prevents harsh clipping / distortion at 500% volume
                ttsCompressorNode.threshold.setValueAtTime(-18, ttsAudioCtx.currentTime);
                ttsCompressorNode.knee.setValueAtTime(30, ttsAudioCtx.currentTime);
                ttsCompressorNode.ratio.setValueAtTime(12, ttsAudioCtx.currentTime);
                ttsCompressorNode.attack.setValueAtTime(0.003, ttsAudioCtx.currentTime);
                ttsCompressorNode.release.setValueAtTime(0.25, ttsAudioCtx.currentTime);

                ttsGainNode.gain.setValueAtTime(ttsVolumeMultiplier, ttsAudioCtx.currentTime);

                ttsGainNode.connect(ttsCompressorNode);
                ttsCompressorNode.connect(ttsAudioCtx.destination);
            }
        }
        if (ttsAudioCtx && ttsAudioCtx.state === 'suspended') {
            ttsAudioCtx.resume();
        }
        if (ttsAudioEl && ttsAudioCtx && !ttsAudioSourceNode) {
            try {
                ttsAudioSourceNode = ttsAudioCtx.createMediaElementSource(ttsAudioEl);
                ttsAudioSourceNode.connect(ttsGainNode);
            } catch (e) {
                // Already connected
            }
        }
    }

    function ttsSetVolume(val) {
        const num = Math.max(10, Math.min(500, parseInt(val, 10) || 100));
        ttsVolumeMultiplier = num / 100; // 0.1 to 5.0 (500%)

        initAudioBooster();
        if (ttsGainNode && ttsAudioCtx) {
            ttsGainNode.gain.setValueAtTime(ttsVolumeMultiplier, ttsAudioCtx.currentTime);
        }
        if (ttsUtterance) {
            ttsUtterance.volume = Math.min(1.0, ttsVolumeMultiplier);
        }

        const slider = document.getElementById('ttsVolumeSlider');
        if (slider && parseInt(slider.value, 10) !== num) {
            slider.value = num;
        }

        const badge = document.getElementById('ttsVolumeBadge');
        const icon = document.getElementById('ttsVolumeIcon');

        if (badge) {
            badge.textContent = num + '%';
            if (num > 100) {
                badge.style.background = 'linear-gradient(135deg, #ef4444 0%, #f59e0b 100%)';
                badge.style.color = '#ffffff';
                badge.classList.add('shadow-sm');
                badge.title = 'Volume Booster Aktif (' + num + '%)';
            } else {
                badge.style.background = '#334155';
                badge.style.color = '#f1f5f9';
                badge.classList.remove('shadow-sm');
                badge.title = 'Volume Normal (' + num + '%)';
            }
        }

        if (icon) {
            if (num >= 350) {
                icon.className = 'bi bi-lightning-charge-fill text-warning';
                icon.title = 'Super Booster 500%';
            } else if (num > 100) {
                icon.className = 'bi bi-volume-up-fill text-danger';
                icon.title = 'Volume Booster Aktif';
            } else if (num > 0) {
                icon.className = 'bi bi-volume-up-fill text-info';
                icon.title = 'Volume Normal';
            } else {
                icon.className = 'bi bi-volume-mute-fill text-secondary';
                icon.title = 'Volume Senyap';
            }
        }

        localStorage.setItem('tts_volume_percent', num);
    }

    function ttsQuickVolume(preset) {
        ttsSetVolume(preset);
    }

    function ttsApplyVoiceSelection() {
        if (!('speechSynthesis' in window)) return;
        ttsVoices = window.speechSynthesis.getVoices() || [];
        if (ttsVoices.length === 0) return;

        ttsFemaleVoice = null;
        ttsVoiceProfile = null;

        // 1. Jika pengguna memilih spesifik dari dropdown
        if (ttsVoiceModel && ttsVoiceModel !== 'auto' && ttsVoiceModel !== 'booster') {
            const specificProfile = FEMALE_VOICE_PROFILES.find(p => p.key === ttsVoiceModel);
            if (specificProfile) {
                ttsFemaleVoice = ttsVoices.find(specificProfile.match);
                if (ttsFemaleVoice) ttsVoiceProfile = specificProfile;
            }
        }

        // 2. Jika mode 'auto' atau pilihan spesifik belum terpasang di browser
        if (!ttsFemaleVoice && ttsVoiceModel !== 'booster') {
            for (let i = 0; i < FEMALE_VOICE_PROFILES.length; i++) {
                const profile = FEMALE_VOICE_PROFILES[i];
                const matched = ttsVoices.find(profile.match);
                if (matched) {
                    ttsFemaleVoice = matched;
                    ttsVoiceProfile = profile;
                    break;
                }
            }
        }

        // 3. Fallback jika tidak ditemukan suara Indonesia sama sekali
        if (!ttsFemaleVoice && ttsVoiceModel !== 'booster') {
            ttsFemaleVoice = ttsVoices.find(v => {
                const n = v.name.toLowerCase();
                return n.includes('female') || n.includes('zira') || n.includes('natural') || n.includes('samantha') || n.includes('jenny');
            }) || ttsVoices[0];
            ttsVoiceProfile = {
                badge: '🌸 Cewek Lembut',
                name: 'Suara Wanita Lembut (Sistem)',
                pitch: 1.06
            };
        }

        // 4. Perbarui Label Narator & Status Bar
        const narratorLabel = document.getElementById('ttsNarratorLabel');
        const statusEl = document.getElementById('ttsStatusText');

        if (ttsVoiceModel === 'booster') {
            if (narratorLabel) narratorLabel.textContent = '⚡ Audio Server 500%';
            if (statusEl && !ttsIsPlaying) statusEl.textContent = 'Mode Aktif: Server Stream Booster (Volume hingga 500%)';
        } else if (ttsFemaleVoice) {
            const cleanName = ttsVoiceProfile ? ttsVoiceProfile.badge : ('🌸 ' + ttsFemaleVoice.name.replace(/Microsoft |Google |Online \(Natural\) - |Desktop /g, ''));
            if (narratorLabel) narratorLabel.textContent = cleanName;
            if (statusEl && !ttsIsPlaying) {
                statusEl.textContent = 'Narator: ' + (ttsVoiceProfile ? ttsVoiceProfile.name : ttsFemaleVoice.name);
            }
        }
    }

    function ttsChangeVoiceModel(val) {
        ttsVoiceModel = val || 'auto';
        localStorage.setItem('tts_voice_model', ttsVoiceModel);
        ttsApplyVoiceSelection();

        const statusEl = document.getElementById('ttsStatusText');
        if (statusEl) {
            if (ttsVoiceModel === 'booster') {
                statusEl.textContent = 'Beralih ke Server Audio Stream (Booster hingga 500%)';
            } else {
                const voiceName = ttsVoiceProfile ? ttsVoiceProfile.name : 'Suara Cewek Lembut';
                statusEl.textContent = 'Model Narator: ' + voiceName;
            }
        }

        // Jika audio sedang berjalan, ulangi kalimat ini dengan model suara baru
        if (ttsIsPlaying && !ttsIsPaused && ttsCurrentIdx >= 0) {
            ttsSpeakSentence(ttsCurrentIdx, ttsSentenceIdx);
        }
    }

    function ttsInitVoices() {
        if (!('speechSynthesis' in window)) {
            return;
        }

        ttsApplyVoiceSelection();
    }

    if ('speechSynthesis' in window) {
        window.speechSynthesis.onvoiceschanged = ttsInitVoices;
        ttsInitVoices();
    }

    function ttsCollectParagraphs() {
        ttsParagraphEls = Array.from(document.querySelectorAll('.tts-paragraph')).filter(el => {
            return el.textContent.trim().length > 0;
        });

        if (ttsParagraphEls.length === 0) {
            ttsParagraphEls = Array.from(document.querySelectorAll('#pane-lite p')).filter(el => el.textContent.trim().length > 0);
        }
    }

    function ttsSplitSentences(text) {
        const parts = text.match(/[^.!?;\n]+[.!?;\n]*/g);
        if (!parts) return [text.trim()];
        return parts.map(s => s.trim()).filter(s => s.length > 0);
    }

    function ttsCleanParagraphDOM(el) {
        if (el && el.dataset && el.dataset.ttsOriginalHtml) {
            el.innerHTML = el.dataset.ttsOriginalHtml;
            delete el.dataset.ttsOriginalHtml;
        }
        if (el) {
            el.classList.remove('tts-paragraph-active');
            const badge = el.querySelector('.tts-live-badge');
            if (badge) badge.remove();
        }
    }

    function ttsTogglePlay() {
        initAudioBooster();

        const canvasPane = document.getElementById('pane-canvas');
        if (canvasPane && canvasPane.classList.contains('active')) {
            switchMode('lite');
        }

        if (ttsIsPlaying && !ttsIsPaused) {
            // Jeda
            clearTimeout(ttsPauseTimeout);
            if (ttsAudioEl && !ttsAudioEl.paused) {
                ttsAudioEl.pause();
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.pause();
            }
            ttsIsPaused = true;
            ttsUpdateUIState('paused');
        } else if (ttsIsPlaying && ttsIsPaused) {
            // Lanjutkan
            if (ttsAudioEl && ttsAudioEl.src && ttsAudioEl.paused) {
                if (ttsAudioCtx && ttsAudioCtx.state === 'suspended') {
                    ttsAudioCtx.resume();
                }
                ttsAudioEl.play();
            } else if ('speechSynthesis' in window) {
                window.speechSynthesis.resume();
            }
            ttsIsPaused = false;
            ttsUpdateUIState('playing');
        } else {
            // Mulai baru
            ttsCollectParagraphs();
            if (ttsParagraphEls.length === 0) {
                switchMode('lite');
                setTimeout(() => {
                    ttsCollectParagraphs();
                    if (ttsParagraphEls.length > 0) {
                        ttsStartFrom(0);
                    } else {
                        alert('Tidak ada naskah teks yang dapat dibacakan pada halaman ini.');
                    }
                }, 300);
                return;
            }
            const startIndex = (ttsCurrentIdx >= 0 && ttsCurrentIdx < ttsParagraphEls.length) ? ttsCurrentIdx : 0;
            ttsStartFrom(startIndex);
        }
    }

    function ttsStartFrom(idx) {
        if (idx < 0 || idx >= ttsParagraphEls.length) return;
        ttsStop();
        ttsCurrentIdx = idx;
        ttsIsPlaying = true;
        ttsIsPaused = false;
        ttsUpdateUIState('playing');
        ttsSpeakParagraph(ttsCurrentIdx);
    }

    function ttsSpeakParagraph(pIdx) {
        clearTimeout(ttsPauseTimeout);
        if (ttsAudioEl) {
            ttsAudioEl.pause();
        }
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }

        if (pIdx < 0 || pIdx >= ttsParagraphEls.length) {
            ttsStop();
            const statusEl = document.getElementById('ttsStatusText');
            if (statusEl) statusEl.textContent = 'Selesai membacakan seluruh naskah buku.';
            return;
        }

        ttsParagraphEls.forEach(el => ttsCleanParagraphDOM(el));

        const targetEl = ttsParagraphEls[pIdx];
        if (!targetEl) return;

        if (!targetEl.dataset.ttsOriginalHtml) {
            targetEl.dataset.ttsOriginalHtml = targetEl.innerHTML;
        }

        targetEl.classList.add('tts-paragraph-active');

        const rawText = targetEl.textContent.trim();
        ttsCurrentSentences = ttsSplitSentences(rawText);

        let newContent = '';
        ttsCurrentSentences.forEach((sentence, sIdx) => {
            newContent += `<span class="tts-sentence" id="tts-s-${pIdx}-${sIdx}">${sentence}</span> `;
        });

        const badgeTitle = (ttsVoiceModel === 'booster') 
            ? '⚡ Audio Server Booster (500%)' 
            : (ttsVoiceProfile ? ttsVoiceProfile.badge + ' (Lembut & Anggun)' : '🌸 Suara Cewek Lembut & Anggun');
        targetEl.innerHTML = `<div class="tts-live-badge"><i class="bi bi-person-heart"></i> ${badgeTitle}</div>` + newContent;
        targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

        ttsSentenceIdx = 0;
        ttsSpeakSentence(pIdx, ttsSentenceIdx);
    }

    function ttsSpeakSentence(pIdx, sIdx) {
        if (!ttsIsPlaying || ttsIsPaused) return;

        if (sIdx >= ttsCurrentSentences.length) {
            const targetEl = ttsParagraphEls[pIdx];
            ttsCleanParagraphDOM(targetEl);

            ttsPauseTimeout = setTimeout(() => {
                if (ttsIsPlaying && !ttsIsPaused) {
                    ttsCurrentIdx++;
                    ttsSpeakParagraph(ttsCurrentIdx);
                }
            }, 450);
            return;
        }

        document.querySelectorAll('.tts-sentence-active').forEach(el => el.classList.remove('tts-sentence-active'));

        const sSpan = document.getElementById(`tts-s-${pIdx}-${sIdx}`);
        if (sSpan) {
            sSpan.classList.add('tts-sentence-active');
            sSpan.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        let sentenceText = ttsCurrentSentences[sIdx];
        sentenceText = sentenceText.replace(/[•\*\_\—\–\[\]\(\)]/g, ' ').replace(/\s+/g, ' ').trim();

        if (sentenceText.length === 0) {
            ttsSentenceIdx++;
            ttsSpeakSentence(pIdx, ttsSentenceIdx);
            return;
        }

        const snippet = sentenceText.length > 45 ? sentenceText.substring(0, 45) + '...' : sentenceText;
        const statusEl = document.getElementById('ttsStatusText');
        if (statusEl) {
            const boostText = ttsVolumeMultiplier > 1.0 ? ' [' + Math.round(ttsVolumeMultiplier * 100) + '% Boost]' : '';
            statusEl.textContent = 'Membaca: "' + snippet + '"' + boostText;
        }

        initAudioBooster();
        let handledNext = false;

        function nextSentenceWithBreath() {
            if (handledNext) return;
            handledNext = true;
            if (ttsSpeechKeepAliveTimer) {
                clearInterval(ttsSpeechKeepAliveTimer);
                ttsSpeechKeepAliveTimer = null;
            }
            if (ttsIsPlaying && !ttsIsPaused) {
                ttsPauseTimeout = setTimeout(() => {
                    if (ttsIsPlaying && !ttsIsPaused) {
                        ttsSentenceIdx++;
                        ttsSpeakSentence(pIdx, ttsSentenceIdx);
                    }
                }, 280); // Jeda nafas lembut 280ms agar pembacaan tidak bertubrukan dan tidak gagap
            }
        }

        // Penentuan Jalur Suara:
        // 1. Jalur Server Stream Booster: Jika mode dipilih 'booster' ATAU volume > 100% dan mode 'auto'
        const useServerBooster = (ttsVoiceModel === 'booster') || (ttsVoiceModel === 'auto' && ttsVolumeMultiplier > 1.0);

        if (useServerBooster && ttsAudioEl && ttsAudioCtx) {
            if (ttsAudioCtx.state === 'suspended') {
                ttsAudioCtx.resume();
            }
            if (ttsGainNode) {
                ttsGainNode.gain.setValueAtTime(ttsVolumeMultiplier, ttsAudioCtx.currentTime);
            }

            const streamUrl = "{{ route('catalog.ttsStream') }}?text=" + encodeURIComponent(sentenceText);
            ttsAudioEl.src = streamUrl;
            ttsAudioEl.playbackRate = ttsRate;

            ttsAudioEl.onended = function () {
                nextSentenceWithBreath();
            };

            ttsAudioEl.onerror = function () {
                speakWithSpeechSynthesis(sentenceText, nextSentenceWithBreath);
            };

            const playPromise = ttsAudioEl.play();
            if (playPromise !== undefined) {
                playPromise.catch(function () {
                    speakWithSpeechSynthesis(sentenceText, nextSentenceWithBreath);
                });
            }
        } else {
            // Jalur Utama: Suara Cewek Lembut Alami (SpeechSynthesis Neural AI Voice)
            speakWithSpeechSynthesis(sentenceText, nextSentenceWithBreath);
        }
    }

    function speakWithSpeechSynthesis(text, onComplete) {
        if (!('speechSynthesis' in window)) {
            if (ttsAudioEl) {
                const streamUrl = "{{ route('catalog.ttsStream') }}?text=" + encodeURIComponent(text);
                ttsAudioEl.src = streamUrl;
                ttsAudioEl.onended = onComplete;
                ttsAudioEl.onerror = onComplete;
                ttsAudioEl.play().catch(onComplete);
            } else if (onComplete) {
                onComplete();
            }
            return;
        }

        window.speechSynthesis.cancel();
        ttsUtterance = new SpeechSynthesisUtterance(text);

        if (ttsFemaleVoice) {
            ttsUtterance.voice = ttsFemaleVoice;
            ttsUtterance.lang = ttsFemaleVoice.lang || 'id-ID';
        } else {
            ttsUtterance.lang = 'id-ID';
        }

        // Pitch 1.05 - 1.07 memberikan sentuhan vokal cewek lembut alami, ramah, dan tidak kaku
        ttsUtterance.pitch = (ttsVoiceProfile && ttsVoiceProfile.pitch) ? ttsVoiceProfile.pitch : 1.05;
        ttsUtterance.rate = ttsRate;
        ttsUtterance.volume = Math.min(1.0, ttsVolumeMultiplier);

        ttsUtterance.onend = function () {
            if (ttsSpeechKeepAliveTimer) {
                clearInterval(ttsSpeechKeepAliveTimer);
                ttsSpeechKeepAliveTimer = null;
            }
            if (onComplete) onComplete();
        };

        ttsUtterance.onerror = function (e) {
            if (ttsSpeechKeepAliveTimer) {
                clearInterval(ttsSpeechKeepAliveTimer);
                ttsSpeechKeepAliveTimer = null;
            }
            console.warn('SpeechSynthesis event:', e);
            if (onComplete) onComplete();
        };

        // Chrome keep-alive safeguard (mencegah browser Chrome mem-pause speech di tengah jalan)
        if (ttsSpeechKeepAliveTimer) clearInterval(ttsSpeechKeepAliveTimer);
        ttsSpeechKeepAliveTimer = setInterval(function () {
            if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
                window.speechSynthesis.pause();
                window.speechSynthesis.resume();
            } else {
                clearInterval(ttsSpeechKeepAliveTimer);
                ttsSpeechKeepAliveTimer = null;
            }
        }, 10000);

        window.speechSynthesis.speak(ttsUtterance);
    }

    function ttsStop() {
        clearTimeout(ttsPauseTimeout);
        if (ttsSpeechKeepAliveTimer) {
            clearInterval(ttsSpeechKeepAliveTimer);
            ttsSpeechKeepAliveTimer = null;
        }
        if (ttsAudioEl) {
            ttsAudioEl.pause();
            ttsAudioEl.removeAttribute('src');
            ttsAudioEl.load();
        }
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        ttsIsPlaying = false;
        ttsIsPaused = false;
        ttsParagraphEls.forEach(el => ttsCleanParagraphDOM(el));
        ttsUpdateUIState('stopped');
    }

    function ttsNextParagraph() {
        if (ttsParagraphEls.length === 0) ttsCollectParagraphs();
        if (ttsCurrentIdx + 1 < ttsParagraphEls.length) {
            ttsCurrentIdx++;
            ttsIsPlaying = true;
            ttsIsPaused = false;
            ttsUpdateUIState('playing');
            ttsSpeakParagraph(ttsCurrentIdx);
        }
    }

    function ttsPrevParagraph() {
        if (ttsParagraphEls.length === 0) ttsCollectParagraphs();
        if (ttsCurrentIdx > 0) {
            ttsCurrentIdx--;
            ttsIsPlaying = true;
            ttsIsPaused = false;
            ttsUpdateUIState('playing');
            ttsSpeakParagraph(ttsCurrentIdx);
        }
    }

    function ttsChangeRate(val) {
        ttsRate = parseFloat(val) || 0.88;
        if (ttsAudioEl) {
            ttsAudioEl.playbackRate = ttsRate;
        }
        if (ttsIsPlaying && !ttsIsPaused && ttsCurrentIdx >= 0) {
            ttsSpeakSentence(ttsCurrentIdx, ttsSentenceIdx);
        }
    }

    function ttsPlayFromChapter(chapIdx) {
        switchMode('lite');
        setTimeout(() => {
            ttsCollectParagraphs();
            const card = document.getElementById('chapter-' + chapIdx) || document.getElementById('textbook-chapter-' + chapIdx);
            if (!card) return;
            const firstP = card.querySelector('.tts-paragraph');
            if (firstP) {
                const idx = ttsParagraphEls.indexOf(firstP);
                if (idx !== -1) {
                    ttsStartFrom(idx);
                } else {
                    ttsStartFrom(0);
                }
            }
        }, 250);
    }

    function ttsUpdateUIState(state) {
        const playBtnText = document.getElementById('ttsPlayBtnText');
        const playIcon = document.getElementById('ttsPlayIcon');
        const stopBtn = document.getElementById('ttsStopBtn');
        const wave = document.getElementById('ttsWaveVisualizer');

        if (state === 'playing') {
            if (playBtnText) playBtnText.textContent = 'Jeda';
            if (playIcon) playIcon.className = 'bi bi-pause-circle-fill fs-6';
            if (stopBtn) stopBtn.classList.remove('d-none');
            if (wave) {
                wave.classList.remove('d-none');
                wave.classList.add('d-inline-flex');
            }
        } else if (state === 'paused') {
            if (playBtnText) playBtnText.textContent = 'Lanjutkan';
            if (playIcon) playIcon.className = 'bi bi-play-circle-fill fs-6';
            if (stopBtn) stopBtn.classList.remove('d-none');
            if (wave) {
                wave.classList.remove('d-inline-flex');
                wave.classList.add('d-none');
            }
        } else {
            if (playBtnText) playBtnText.textContent = 'Mulai Baca Otomatis';
            if (playIcon) playIcon.className = 'bi bi-play-circle-fill fs-6';
            if (stopBtn) stopBtn.classList.add('d-none');
            if (wave) {
                wave.classList.remove('d-inline-flex');
                wave.classList.add('d-none');
            }
            const statusEl = document.getElementById('ttsStatusText');
            if (statusEl) statusEl.textContent = 'Siap membacakan naskah buku';
        }
    }

    // On DOM Ready: Initialize Canvas Engine & user settings
    document.addEventListener('DOMContentLoaded', function () {
        const savedTheme = localStorage.getItem('reader_theme');
        if (savedTheme) setReadingTheme(savedTheme);

        const savedFontSize = localStorage.getItem('reader_font_size');
        if (savedFontSize) {
            currentFontSize = parseInt(savedFontSize, 10);
            const content = document.getElementById('liteReaderContent');
            if (content) content.style.fontSize = currentFontSize + 'px';
        }

        const savedKentang = localStorage.getItem('mode_kentang');
        const isLowRam = (navigator.deviceMemory && navigator.deviceMemory <= 2) || (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2);
        
        if (savedKentang === '1' || (savedKentang === null && isLowRam)) {
            toggleKentangMode();
        }

        // Initialize TTS Volume Booster (Default 100% or saved setting up to 500%)
        const savedVol = localStorage.getItem('tts_volume_percent');
        if (savedVol) {
            ttsSetVolume(savedVol);
        } else {
            ttsSetVolume(100);
        }

        // Inisialisasi Model Suara Cewek Lembut
        const savedVoiceModel = localStorage.getItem('tts_voice_model');
        if (savedVoiceModel) {
            ttsVoiceModel = savedVoiceModel;
            const modelSelect = document.getElementById('ttsVoiceModelSelect');
            if (modelSelect) modelSelect.value = ttsVoiceModel;
        }
        ttsApplyVoiceSelection();

        // Start loading lightweight Canvas Engine
        initPdfCanvas();
    });
</script>
@endsection
