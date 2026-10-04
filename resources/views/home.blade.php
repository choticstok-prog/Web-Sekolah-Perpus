@extends('layouts.app')

@section('title', 'Selamat Datang di PustakaSekolah - Perpustakaan Digital SMA & SMK')

@section('content')
<!-- Hero Welcome Banner -->
<div class="card border-0 text-white p-4 p-md-5 mb-5 shadow position-relative overflow-hidden" 
     style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #2563eb 100%); border-radius: 24px;">
    <!-- Ambient Light Glow Decoration -->
    <div class="position-absolute rounded-circle" 
         style="width: 380px; height: 380px; background: radial-gradient(circle, rgba(16, 185, 129, 0.28) 0%, rgba(37, 99, 235, 0) 70%); top: -100px; right: 8%; pointer-events: none;"></div>

    <div class="row align-items-center position-relative" style="z-index: 1;">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge px-3 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #ffffff !important; color: #1d4ed8 !important;">
                    <i class="bi bi-mortarboard-fill me-1"></i> Perpustakaan Digital SMA & SMK
                </span>
                <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 px-3 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <span class="live-dot" style="background-color: #34d399;"></span> E-Reader Aktif & Gratis
                </span>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-cpu-fill me-1"></i> Ramah Device Kentang
                </span>
            </div>

            <h1 class="display-5 fw-bold mb-3 lh-sm">
                Selamat Datang di <span class="text-warning">PustakaSekolah</span>
            </h1>
            <p class="lead opacity-90 mb-4 fs-6" style="max-width: 650px; line-height: 1.7;">
                Pusat peminjaman buku teks pelajaran resmi SMA dan SMK (Kelas X, XI, XII) serta platform baca online interaktif. Didesain cepat dan sangat ringan agar nyaman diakses di semua perangkat, termasuk HP berspesifikasi minimal.
            </p>

            <!-- Search Bar directly on Home -->
            <form action="{{ route('catalog.index') }}" method="GET" class="mb-3" style="max-width: 620px;">
                <div class="hero-search-box">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari buku pelajaran, Sejarah, Matematika, Biologi, kelas...">
                    <button type="submit" class="btn btn-warning fw-bold px-4 py-2 rounded-pill text-dark d-flex align-items-center gap-1 shadow-sm text-nowrap">
                        <span>Cari Buku</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Filter Chips / Pencarian Cepat Interaktif -->
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="text-white opacity-75 small fw-semibold"><i class="bi bi-lightning-fill text-warning me-1"></i>Pencarian Cepat:</span>
                <a href="{{ route('catalog.index', ['kategori' => 'sejarah']) }}" class="hero-search-tag">Sejarah</a>
                <a href="{{ route('catalog.index', ['kategori' => 'matematika']) }}" class="hero-search-tag">Matematika</a>
                <a href="{{ route('catalog.index', ['kategori' => 'biologi']) }}" class="hero-search-tag">Biologi</a>
                <a href="{{ route('catalog.index', ['kelas' => '10']) }}" class="hero-search-tag">Kelas 10</a>
                <a href="{{ route('catalog.index', ['kelas' => '11']) }}" class="hero-search-tag">Kelas 11</a>
                <a href="{{ route('catalog.index', ['kelas' => '12']) }}" class="hero-search-tag">Kelas 12</a>
            </div>

            <!-- Quick Action Buttons -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill"></i>
                    <span>Jelajahi Semua Buku</span>
                </a>
                <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" class="btn btn-reader px-4 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-book-half"></i>
                    <span>Mulai Baca Online</span>
                </a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-person me-1"></i> Masuk Anggota
                    </a>
                @endguest
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block text-center position-relative">
            <div class="animate-float">
                <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-20 backdrop-blur d-inline-block shadow-lg">
                    <i class="bi bi-book text-warning" style="font-size: 7rem; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.3));"></i>
                    <div class="mt-2 text-white fw-bold fs-5">Pustaka Digital Siswa</div>
                    <div class="text-white-50 small">Kurikulum Merdeka & K13</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Counter Cards -->
<div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
    <div class="col">
        <div class="stat-card shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_books'] ?? 27 }}+</h3>
                    <small class="text-muted">Buku Pelajaran Resmi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_categories'] ?? 9 }}</h3>
                    <small class="text-muted">Mata Pelajaran SMA/SMK</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_online'] ?? 27 }}</h3>
                    <small class="text-muted">Siap Baca Online (100%)</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_stock'] ?? 100 }}+</h3>
                    <small class="text-muted">Total Stok Buku Fisik</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section: Kenapa PustakaSekolah? -->
<div class="mb-5">
    <div class="text-center mb-4">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold mb-2">
            <i class="bi bi-stars me-1"></i> Keunggulan Layanan
        </span>
        <h2 class="fw-bold text-dark">Belajar Lebih Mudah & Menyenangkan</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Dukungan fasilitas perpustakaan lengkap untuk mempermudah kegiatan belajar mengajar siswa dan guru.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm p-3 card-hover text-center">
                <div class="mx-auto p-3 rounded-circle bg-success-subtle text-success mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-cpu fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Ramah Device Kentang</h6>
                <p class="text-muted small mb-0">
                    E-Reader kanvas 1 halaman yang sangat hemat RAM dan kuota. Buka materi PDF tanpa macet di HP apapun.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm p-3 card-hover text-center">
                <div class="mx-auto p-3 rounded-circle bg-primary-subtle text-primary mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-mortarboard fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Koleksi Terverifikasi SIBI</h6>
                <p class="text-muted small mb-0">
                    Buku terbitan Pusat Perbukuan Kemdikbudristek lengkap dengan sinopsis materi dan capaian kurikulum.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm p-3 card-hover text-center">
                <div class="mx-auto p-3 rounded-circle bg-warning-subtle text-warning mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-bookmark-check fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Pinjam Buku Cepat</h6>
                <p class="text-muted small mb-0">
                    Ajukan peminjaman buku fisik lewat web, pilih durasi pinjam, dan ambil di perpustakaan tanpa ribet.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm p-3 card-hover text-center">
                <div class="mx-auto p-3 rounded-circle bg-info-subtle text-info mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-journal-text fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Catatan Siswa Privat</h6>
                <p class="text-muted small mb-0">
                    Fitur buku catatan langsung di samping halaman baca, otomatis tersimpan tanpa takut hilang saat belajar.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Books Showcase -->
<div class="mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
        <div>
            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold mb-1">
                <i class="bi bi-fire me-1"></i> Koleksi Pilihan
            </span>
            <h3 class="fw-bold text-dark mb-0">Buku Pelajaran Populer</h3>
            <p class="text-muted small mb-0">Rekomendasi buku teks yang sering dipelajari siswa kelas 10, 11, dan 12.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
            Lihat Semua Koleksi (27+) <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($featuredBooks as $b)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm card-hover card-shine overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 220px; background-color: #0f172a;">
                        @if($b->cover_image)
                            <img src="{{ $b->cover_image }}" 
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="{{ $b->title }}"
                                 style="transition: transform 0.3s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-light">
                                <i class="bi bi-book fs-1 mb-2"></i>
                                <span class="small fw-semibold">{{ $b->title }}</span>
                            </div>
                        @endif

                        <span class="position-absolute top-0 start-0 m-2 badge book-category-badge rounded-pill px-2.5 py-1 small shadow-sm">
                            <i class="bi bi-bookmark-fill text-warning me-1"></i><span class="category-name-text">{{ $b->category->name }}</span>
                        </span>

                        @if($b->pdf_url)
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-success text-white rounded-pill px-2 py-1 small shadow-sm d-flex align-items-center gap-1">
                                <span class="live-dot" style="width: 6px; height: 6px; background-color: #ffffff;"></span>
                                <span>Bisa Baca Online</span>
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $b->title }}">
                            <a href="{{ route('catalog.show', $b) }}" class="text-decoration-none text-dark">
                                {{ $b->title }}
                            </a>
                        </h6>
                        <small class="text-muted mb-2 d-flex justify-content-between">
                            <span class="text-truncate">{{ $b->author }}</span>
                            <span>{{ $b->year }}</span>
                        </small>

                        <div class="d-grid gap-2 mt-auto pt-2 border-top">
                            @if($b->pdf_url)
                                <a href="{{ route('catalog.read', $b) }}" class="btn btn-sm btn-reader rounded-pill py-2 d-flex align-items-center justify-content-center gap-1 shadow-sm">
                                    <i class="bi bi-book-half"></i>
                                    <span>Baca Online Sekarang</span>
                                </a>
                            @endif
                            <a href="{{ route('catalog.show', $b) }}" class="btn btn-sm btn-outline-primary rounded-pill py-1">
                                <i class="bi bi-info-circle me-1"></i> Detail & Pinjam Fisik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- 3 Steps Guide Section -->
<div class="card border-0 bg-light p-4 p-md-5 mb-5 rounded-4 shadow-sm">
    <div class="text-center mb-4">
        <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold mb-2">Panduan Penggunaan</span>
        <h3 class="fw-bold text-dark">3 Langkah Mudah Memanfaatkan PustakaSekolah</h3>
        <p class="text-muted small">Mulai membaca buku pelajaran dan meminjam buku hanya dalam beberapa detik.</p>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                <div class="badge bg-primary text-white rounded-circle p-3 fs-4 mb-3" style="width: 54px; height: 54px; display: inline-flex; align-items: center; justify-content: center;">
                    1
                </div>
                <h6 class="fw-bold text-dark mb-2">Cari Buku Pelajaran</h6>
                <p class="text-muted small mb-0">
                    Cari buku berdasarkan mata pelajaran atau kelas Anda (Kelas 10, 11, atau 12) melalui katalog digital.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                <div class="badge bg-success text-white rounded-circle p-3 fs-4 mb-3" style="width: 54px; height: 54px; display: inline-flex; align-items: center; justify-content: center;">
                    2
                </div>
                <h6 class="fw-bold text-dark mb-2">Baca Online atau Pinjam</h6>
                <p class="text-muted small mb-0">
                    Buka E-Reader langsung di browser atau ajukan peminjaman buku fisik untuk dibawa pulang.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                <div class="badge bg-warning text-dark rounded-circle p-3 fs-4 mb-3" style="width: 54px; height: 54px; display: inline-flex; align-items: center; justify-content: center;">
                    3
                </div>
                <h6 class="fw-bold text-dark mb-2">Belajar Kapan Saja</h6>
                <p class="text-muted small mb-0">
                    Pelajari materi bab demi bab, buat catatan belajar, dan tingkatkan pemahaman akademik Anda.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Banner Bottom -->
<div class="card border-0 text-white p-4 p-md-5 text-center shadow position-relative overflow-hidden" 
     style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 20px;">
    <h3 class="fw-bold mb-2">Siap Mulai Belajar Hari Ini?</h3>
    <p class="lead opacity-90 mb-4 fs-6 mx-auto" style="max-width: 600px;">
        Semua buku pelajaran SMA & SMK sudah tersedia dalam format digital yang ringan dan siap dibaca kapan pun tanpa biaya.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('catalog.index') }}" class="btn btn-light text-success fw-bold px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-grid-fill me-1"></i> Buka Katalog Lengkap
        </a>
        <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" class="btn btn-dark text-white fw-bold px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-book-half me-1"></i> Mulai Baca E-Reader
        </a>
    </div>
</div>
@endsection
