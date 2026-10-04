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

    .reader-mode-btn.btn-kentang-active {
        background: #059669 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.4);
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
        background: rgba(17, 24, 39, 0.85);
        color: #ffffff;
        padding: 1.25rem 2rem;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        z-index: 20;
        border: 1px solid #374151;
        pointer-events: none;
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
        @if($book->pdf_url)
            <a href="{{ $book->pdf_url }}" target="_blank" rel="noopener noreferrer" 
               class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm"
               title="Buka langsung di aplikasi Drive/PDF bawaan HP tanpa membebani browser">
                <i class="bi bi-phone"></i>
                <span>Buka di Aplikasi HP (Drive / PDF)</span>
            </a>
        @endif
        
        <button type="button" id="toggleKentangBtn" onclick="toggleKentangMode()" 
                class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1">
            <i class="bi bi-cpu"></i>
            <span id="kentangBtnText">Mode Kentang: MATI</span>
        </button>
    </div>
</div>

<!-- Alert Notifikasi Ramah Device Kentang -->
<div class="alert alert-dark border border-secondary border-opacity-25 py-2 px-3 rounded-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2 text-white small" style="background-color: #1e293b;">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-pill px-2 py-1"><i class="bi bi-lightning-charge-fill me-1"></i>RAM Super Ringan</span>
        <span>
            <strong>Device Kentang Siap!</strong> PDF di bawah dimuat menggunakan <strong>Kanvas 1 Halaman</strong> (hanya memuat halaman aktif ke memori sehingga HP tidak akan lag atau crash).
        </span>
    </div>
    <div class="text-white-50 small d-none d-md-block">
        <i class="bi bi-check2-circle text-success me-1"></i>Tekan tanda panah ◄ ► pada keyboard atau tombol untuk ganti halaman.
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
            <button class="reader-mode-btn btn-kentang-active" id="nav-mode-canvas" onclick="switchMode('canvas')">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <span>PDF Ringan (Kanvas)</span>
            </button>
            <button class="reader-mode-btn" id="nav-mode-lite" onclick="switchMode('lite')">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Rangkuman & Sinopsis</span>
            </button>
            <button class="reader-mode-btn" id="nav-mode-iframe" onclick="switchMode('iframe')">
                <i class="bi bi-display"></i>
                <span class="d-none d-sm-inline">Mode Iframe</span>
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
                    <span>dari <strong id="pageCountText" class="text-info">-</strong></span>
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
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-semibold small" onclick="fitToWidth()" title="Pas Lebar Layar HP">
                    <i class="bi bi-arrows-expand me-1"></i> <span class="d-none d-sm-inline">Pas Lebar Layar</span>
                </button>
                <button type="button" class="btn btn-sm btn-dark text-white rounded-pill px-2 py-1" onclick="zoomChange(0.15)" title="Perbesar">
                    <i class="bi bi-zoom-in"></i>
                </button>
                <span class="badge bg-secondary-subtle text-light rounded-pill px-2 py-1 small ms-1" id="zoomPercentText">100%</span>
            </div>

            <!-- RAM Optimization Pill -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 rounded-pill px-2 py-1 small">
                    <i class="bi bi-cpu-fill me-1"></i> Kanvas 1 Halaman (RAM Hemat)
                </span>
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
            </div>

            <!-- Single Canvas Element -->
            <canvas id="pdfCanvas"></canvas>

            <!-- Error Notification if PDF failed to fetch -->
            <div class="alert alert-warning border-0 p-4 text-center d-none mt-3" id="canvasErrorAlert" style="max-width: 500px;">
                <i class="bi bi-exclamation-triangle-fill fs-2 text-warning mb-2 d-block"></i>
                <h6 class="fw-bold">Gagal Memuat Kanvas PDF</h6>
                <p class="small text-muted mb-3">
                    Koneksi terhambat atau format dokumen membutuhkan penampil alternatif. Silakan coba Penampil Google Docs atau Rangkuman & Sinopsis.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="switchMode('iframe'); setIframeEngine('gdocs');">
                        Gunakan Google Docs Viewer
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="switchMode('lite')">
                        Rangkuman & Sinopsis (0 MB)
                    </button>
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
                        <span>Rangkuman Pembahasan & Poin Penting Tiap Bab</span>
                    </h5>
                    <span class="badge bg-secondary rounded-pill small">{{ count($chapters) }} Bab</span>
                </div>

                @if(count($chapters) > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($chapters as $chap)
                            <div class="lite-card p-3 p-md-4 rounded-3 border-start border-4 border-info">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 rounded-pill px-3 py-1 fw-bold">
                                        <i class="bi bi-bookmark-fill me-1"></i> {{ $chap['label'] }}
                                    </span>
                                </div>

                                <h6 class="fw-bold text-white mb-3 fs-6">
                                    {{ $chap['title'] }}
                                </h6>

                                @if($chap['membahas'])
                                    <div class="p-3 rounded-3 mb-2" style="background: rgba(14, 165, 233, 0.08); border-left: 3px solid #0ea5e9;">
                                        <div class="fw-bold text-info small mb-1 d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-book-half"></i> APA YANG DIBAHAS DI BAB INI:
                                        </div>
                                        <p class="mb-0 small" style="line-height: 1.65;">
                                            {{ $chap['membahas'] }}
                                        </p>
                                    </div>
                                @endif

                                @if($chap['poin'])
                                    <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.08); border-left: 3px solid #10b981;">
                                        <div class="fw-bold text-success small mb-1 d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-lightbulb-fill"></i> POIN-POIN PENTING MATERI:
                                        </div>
                                        <p class="mb-0 small" style="line-height: 1.65;">
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

            @if($book->pdf_url)
                <a href="{{ $book->pdf_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-light rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Full Tab Asli
                </a>
            @endif
        </div>

        <div style="flex: 1; display: flex; flex-direction: column; min-height: 580px; background: #000;">
            @php
                $pdfSource = $book->pdf_url ?: 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Sejarah%20BS%20press.pdf';
            @endphp
            <iframe 
                data-src="{{ $pdfSource }}#toolbar=1&navpanes=0&scrollbar=1" 
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
<!-- Mozilla PDF.js CDN for Ultra-Lightweight 1-Page Canvas Rendering -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Initialize PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const bookId = "{{ $book->id }}";
    const pdfUrl = "{{ $pdfSource }}";
    const gdocsUrl = "https://docs.google.com/viewer?url={{ urlencode($pdfSource) }}&embedded=true";

    // PDF Canvas State
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let zoomScale = 1.0;
    let fitMode = 'width'; // 'width' or 'manual'
    let isIframeLoaded = false;
    let currentFontSize = 16;

    const canvas = document.getElementById('pdfCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;
    const canvasLoader = document.getElementById('canvasLoader');
    const canvasLoaderText = document.getElementById('canvasLoaderText');
    const pageNumInput = document.getElementById('pageNumberInput');
    const pageCountText = document.getElementById('pageCountText');
    const zoomPercentText = document.getElementById('zoomPercentText');
    const errorAlert = document.getElementById('canvasErrorAlert');

    /**
     * Render specified page onto the single canvas
     */
    function renderPage(num) {
        if (!pdfDoc || !canvas || !ctx) return;
        pageRendering = true;
        if (canvasLoader) canvasLoader.classList.remove('d-none');
        if (canvasLoaderText) canvasLoaderText.textContent = 'Memuat halaman ' + num + '...';

        pdfDoc.getPage(num).then(function (page) {
            const wrapper = document.getElementById('canvasWrapper');
            const availableWidth = wrapper ? (wrapper.clientWidth - 40) : 750;

            let viewport = page.getViewport({ scale: 1.0 });

            if (fitMode === 'width' && availableWidth > 120) {
                zoomScale = Math.min(2.5, Math.max(0.6, availableWidth / viewport.width));
            }

            viewport = page.getViewport({ scale: zoomScale });

            // Sharp DPR without overworking GPU
            const dpr = Math.min(window.devicePixelRatio || 1, 1.75);
            canvas.height = viewport.height * dpr;
            canvas.width = viewport.width * dpr;
            canvas.style.width = viewport.width + 'px';
            canvas.style.height = viewport.height + 'px';

            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);

            renderTask.promise.then(function () {
                pageRendering = false;
                if (canvasLoader) canvasLoader.classList.add('d-none');
                if (zoomPercentText) zoomPercentText.textContent = Math.round(zoomScale * 100) + '%';
                if (pageNumInput) pageNumInput.value = num;

                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            }).catch(function (err) {
                pageRendering = false;
                if (canvasLoader) canvasLoader.classList.add('d-none');
                console.warn('Render page error:', err);
            });
        }).catch(function (err) {
            pageRendering = false;
            if (canvasLoader) canvasLoader.classList.add('d-none');
            console.warn('Get page error:', err);
        });
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
     * Load PDF with range-request enabled PDF.js
     */
    function initPdfCanvas() {
        if (pdfDoc) return;
        if (canvasLoader) canvasLoader.classList.remove('d-none');
        if (canvasLoaderText) canvasLoaderText.textContent = 'Menghubungkan dokumen PDF...';

        const loadingTask = pdfjsLib.getDocument({
            url: pdfUrl,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
            disableRange: false,
            disableStream: false
        });

        loadingTask.promise.then(function (pdf) {
            pdfDoc = pdf;
            if (pageCountText) pageCountText.textContent = pdf.numPages;
            if (pageNumInput) pageNumInput.max = pdf.numPages;
            renderPage(pageNum);
        }).catch(function (error) {
            console.error('PDF.js loading error:', error);
            if (canvasLoader) canvasLoader.classList.add('d-none');
            if (errorAlert) errorAlert.classList.remove('d-none');
        });
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
            el.classList.remove('active', 'btn-kentang-active');
        });

        if (mode === 'canvas') {
            document.getElementById('pane-canvas').classList.add('active');
            document.getElementById('nav-mode-canvas').classList.add('active', 'btn-kentang-active');
            if (!pdfDoc) initPdfCanvas();
        } else if (mode === 'lite') {
            document.getElementById('pane-lite').classList.add('active');
            document.getElementById('nav-mode-lite').classList.add('active', 'btn-kentang-active');
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

        // Start loading lightweight Canvas Engine
        initPdfCanvas();
    });
</script>
@endsection
