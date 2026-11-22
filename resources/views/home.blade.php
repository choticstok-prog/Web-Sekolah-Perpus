@extends('layouts.app')

@section('title', 'Selamat Datang di PustakaSekolah - Perpustakaan Online SMP, SMA & SMK')

@section('content')
<!-- Hero Welcome Banner (Tenang, Elegan & Tidak Ramai) -->
<div class="card border-0 text-white p-4 p-md-5 mb-5 shadow-sm position-relative overflow-hidden" 
     style="background: #0f172a; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.08);">

    <div class="row align-items-center position-relative">
        <div class="col-lg-8">
            <!-- Badge Resmi Sederhana -->
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge px-3 py-1.5 rounded-pill fw-semibold" 
                      style="background-color: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 0.8rem;">
                    <i class="bi bi-mortarboard-fill me-1.5"></i> Perpustakaan Online SMP, SMA & SMK
                </span>
                <span class="badge px-3 py-1.5 rounded-pill fw-medium" 
                      style="background-color: rgba(255, 255, 255, 0.08); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.8rem;">
                    <i class="bi bi-book-half me-1"></i> E-Reader Siap Akses
                </span>
            </div>

            <h1 class="display-6 fw-bold mb-3 lh-sm text-white">
                Pustaka Digital Sekolah & Koleksi Sastra
            </h1>
            <p class="lead opacity-90 mb-4 fs-6" style="max-width: 620px; line-height: 1.6; color: #cbd5e1;">
                Akses buku pelajaran resmi Kurikulum Merdeka SMP (Kelas 7–9), SMA & SMK (Kelas 10–12), serta <strong>20 novel sastra nusantara pilihan</strong>. Baca langsung di peramban secara digital, nyaman, dan gratis.
            </p>

            <!-- Search Bar (Harmonis: Input Bersih & Tombol Biru Primer) -->
            <form action="{{ route('catalog.index') }}" method="GET" class="mb-3" style="max-width: 600px;">
                <div class="hero-search-box">
                    <span class="input-group-text">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari buku pelajaran, novel, atau tahun terbit (2024, 2009...)...">
                    <button type="submit" class="btn btn-primary fw-semibold px-4 py-2 rounded-pill d-flex align-items-center gap-1 shadow-sm text-nowrap">
                        <span>Cari Buku</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Filter Chips (Warna Netral & Tenang) -->
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="small fw-medium" style="color: #94a3b8;"><i class="bi bi-compass me-1"></i>Pintas:</span>
                <a href="{{ route('catalog.index', ['kategori' => 'novel-fiksi']) }}" 
                   class="hero-search-tag" 
                   style="background-color: rgba(59, 130, 246, 0.2) !important; color: #93c5fd !important; border-color: rgba(59, 130, 246, 0.4) !important;">
                    📖 20 Novel Fiksi
                </a>
                <a href="{{ route('catalog.index', ['kelas' => '7']) }}" class="hero-search-tag">Kelas 7-9 (SMP)</a>
                <a href="{{ route('catalog.index', ['kelas' => '10']) }}" class="hero-search-tag">Kelas 10-12 (SMA)</a>
                <a href="{{ route('catalog.index') }}" class="hero-search-tag">Semua Buku</a>
            </div>

            <!-- Action Buttons (Biru Primer + Outline Terang) -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill"></i>
                    <span>Jelajahi Koleksi Buku</span>
                </a>
                <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-book-half"></i>
                    <span>Mulai Baca Online</span>
                </a>
            </div>
        </div>

        <!-- Visual Box Kanan (Minimalis & Elegan) -->
        <div class="col-lg-4 d-none d-lg-block text-center">
            <div class="p-4 rounded-4 text-center d-inline-block" 
                 style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); width: 260px;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 72px; height: 72px; background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="bi bi-book-half fs-2"></i>
                </div>
                <div class="text-white fw-bold fs-6">E-Reader Pustaka</div>
                <div class="small mt-1" style="color: #94a3b8;">74+ Buku Teks & Sastra</div>
                <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 text-start small" style="color: #cbd5e1;">
                    <div class="d-flex align-items-center gap-2 mb-1.5">
                        <i class="bi bi-check2 text-primary"></i> <span>Akses online langsung</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-1.5">
                        <i class="bi bi-check2 text-primary"></i> <span>Format teks & PDF</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check2 text-primary"></i> <span>Gratis seluruh siswa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Counter Cards (Serasi & Bersih: Palet Warna Terpadu) -->
<div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
    <div class="col">
        <div class="stat-card shadow-sm h-100 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_books'] ?? 74 }}+</h3>
                    <small class="text-muted fw-medium">Buku & Novel</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm h-100 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_categories'] ?? 13 }}</h3>
                    <small class="text-muted fw-medium">Kategori & Mapel</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm h-100 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_online'] ?? 74 }}</h3>
                    <small class="text-muted fw-medium">Siap Baca Online</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm h-100 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">24 Jam</h3>
                    <small class="text-muted fw-medium">Akses Digital</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Books Showcase (Buku Populer) -->
<div class="mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Buku Populer</h3>
            <p class="text-muted small mb-0">Rekomendasi buku teks dan novel sastra yang paling sering dibaca.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold small">
            Lihat Semua Koleksi ({{ $stats['total_books'] ?? 74 }}+) <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($featuredBooks as $b)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm card-hover overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 200px; background-color: #0f172a;">
                        @if($b->cover_image)
                            <img src="{{ $b->cover_image }}" 
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="{{ $b->title }}"
                                 loading="lazy">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-light">
                                <i class="bi bi-book fs-1 mb-2"></i>
                                <span class="small fw-semibold">{{ $b->title }}</span>
                            </div>
                        @endif

                        <span class="position-absolute top-0 start-0 m-2 badge book-category-badge rounded-pill px-2.5 py-1 small shadow-sm">
                            <span class="category-name-text">{{ $b->category->name }}</span>
                        </span>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $b->title }}">
                            <a href="{{ route('catalog.show', $b) }}" class="text-decoration-none text-dark">
                                {{ $b->title }}
                            </a>
                        </h6>
                        <small class="text-muted mb-3 d-flex justify-content-between">
                            <span class="text-truncate">{{ $b->author }}</span>
                            <span>{{ $b->year }}</span>
                        </small>

                        <div class="mt-auto pt-2 border-top">
                            @if($b->pdf_url)
                                <a href="{{ route('catalog.read', $b) }}" class="btn btn-sm btn-primary rounded-pill py-2 w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="bi bi-book-half"></i>
                                    <span>Baca Online Sekarang</span>
                                </a>
                            @else
                                <a href="{{ route('catalog.show', $b) }}" class="btn btn-sm btn-outline-primary rounded-pill py-2 w-100">
                                    <i class="bi bi-info-circle me-1"></i> Informasi Buku
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Keunggulan Layanan (Serasi dengan Ikon Terpadu) -->
<div class="mb-5">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark mb-1">Kemudahan Membaca di PustakaSekolah</h4>
        <p class="text-muted small mx-auto" style="max-width: 500px;">
            Fasilitas membaca buku digital yang praktis, cepat, dan ramah untuk seluruh siswa.
        </p>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 text-center">
                <div class="mx-auto rounded-circle mb-3 d-flex align-items-center justify-content-center" 
                     style="width: 52px; height: 52px; background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-cpu fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Ringan & Hemat Kuota</h6>
                <p class="text-muted small mb-0">
                    E-Reader efisien yang lancar dibuka di ponsel maupun komputer tanpa aplikasi tambahan.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 text-center">
                <div class="mx-auto rounded-circle mb-3 d-flex align-items-center justify-content-center" 
                     style="width: 52px; height: 52px; background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-mortarboard fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Koleksi Lengkap & Terverifikasi</h6>
                <p class="text-muted small mb-0">
                    Buku teks Kurikulum Merdeka SMP & SMA/SMK, ditambah 20 novel sastra Indonesia pilihan.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 text-center">
                <div class="mx-auto rounded-circle mb-3 d-flex align-items-center justify-content-center" 
                     style="width: 52px; height: 52px; background-color: rgba(37, 99, 235, 0.08); color: #2563eb;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Akses Bebas 24 Jam</h6>
                <p class="text-muted small mb-0">
                    Membaca kapan pun dan di mana pun langsung dari peramban secara gratis tanpa antrean fisik.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Banner Bottom (Tenang & Cohesive) -->
<div class="card border-0 text-white p-4 p-md-5 text-center shadow-sm position-relative overflow-hidden mb-4" 
     style="background: #0f172a; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.08);">
    <h3 class="fw-bold mb-2 text-white">Mulai Membaca Buku Favoritmu Sekarang</h3>
    <p class="opacity-90 mb-4 small mx-auto" style="max-width: 500px; color: #cbd5e1;">
        Pilih buku pelajaran atau novel fiksi dan nikmati kemudahan membaca digital langsung dari peramban.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="{{ route('catalog.index') }}" class="btn btn-primary fw-semibold px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-grid-fill me-1"></i> Buka Katalog Buku
        </a>
        <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" class="btn btn-outline-light fw-semibold px-4 py-2 rounded-pill">
            <i class="bi bi-book-half me-1"></i> Baca Online Gratis
        </a>
    </div>
</div>
@endsection
