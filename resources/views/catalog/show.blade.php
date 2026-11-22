@extends('layouts.app')

@section('title', $book->title . ' - Perpustakaan Online PustakaSekolah')

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Perpustakaan Online</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['kategori' => $book->category->slug]) }}" class="text-decoration-none">{{ $book->category->name }}</a></li>
        <li class="breadcrumb-item active text-truncate" style="max-width: 300px;">{{ $book->title }}</li>
    </ol>
</nav>

@if($book->pdf_url)
    <!-- Call-To-Action Banner: Fitur Baca Online (Tenang, Elegan & Serasi) -->
    <div class="card border-0 mb-4 text-white p-3 p-md-4 shadow-sm" 
         style="background: #0f172a; border-radius: 18px; border: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle text-primary d-flex align-items-center justify-content-center shadow-sm" 
                     style="width: 54px; height: 54px; min-width: 54px; background: rgba(59, 130, 246, 0.15);">
                    <i class="bi bi-book-half fs-2"></i>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="badge px-3 py-1 rounded-pill fw-semibold" 
                              style="background-color: rgba(59, 130, 246, 0.18); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.35); font-size: 0.78rem;">
                            <i class="bi bi-book-half me-1"></i> E-Reader Tersedia
                        </span>
                        <span class="badge px-2.5 py-1 rounded-pill fw-medium" 
                              style="background-color: rgba(255, 255, 255, 0.08); color: #cbd5e1; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.78rem;">
                            <i class="bi bi-check2-circle me-1 text-primary"></i> Akses Digital Gratis
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1 text-white">Baca Buku Ini di Perpustakaan Online</h5>
                    <p class="mb-0 small" style="color: #94a3b8;">Tersedia langsung di peramban. Lengkap dengan pembaca kanvas ringan dan naskah cerita/bab tanpa antrean.</p>
                </div>
            </div>
            @auth
                <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary fw-semibold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-nowrap">
                    <i class="bi bi-eye-fill"></i>
                    <span>Buka E-Reader Sekarang</span>
                </a>
            @else
                <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary fw-semibold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-nowrap">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Daftar untuk Buka E-Reader</span>
                </a>
            @endauth
        </div>
    </div>
@endif

<div class="row g-4 mb-5">
    <!-- Left Column: Book Cover -->
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm overflow-hidden p-2 text-center sticky-top" style="top: 90px;">
            <div style="height: 340px; background-color: #0f172a; border-radius: 12px; overflow: hidden;" class="position-relative">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $book->title }}">
                @else
                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="bi bi-book fs-1 mb-2"></i>
                        <span class="small fw-semibold px-2">{{ $book->title }}</span>
                    </div>
                @endif

                @if($book->pdf_url)
                    <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white border border-light border-opacity-25 rounded-pill px-2.5 py-1 small shadow-sm d-flex align-items-center gap-1.5">
                        <i class="bi bi-book-half text-primary"></i> E-Reader Siap
                    </span>
                @endif
            </div>

            <div class="mt-3 text-center">
                @if($book->pdf_url)
                    @auth
                        <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary w-100 rounded-pill py-2.5 mb-2 shadow-sm d-flex align-items-center justify-content-center gap-2 fw-semibold">
                            <i class="bi bi-book-half"></i>
                            <span>Baca Buku Ini Online</span>
                        </a>
                    @else
                        <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary w-100 rounded-pill py-2.5 mb-2 shadow-sm d-flex align-items-center justify-content-center gap-2 fw-semibold">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Daftar untuk Baca Online</span>
                        </a>
                        <small class="text-primary d-block mt-1 mb-2 fw-semibold" style="font-size: 0.8rem;">
                            <i class="bi bi-info-circle me-1"></i>Daftar gratis untuk akses E-Reader
                        </small>
                    @endauth
                @endif
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-check-circle text-primary me-1"></i>Akses digital gratis & mandiri
                </small>
            </div>
        </div>
    </div>

    <!-- Center Column: Book Information -->
    <div class="col-md-8 col-lg-5">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="mb-2 d-flex align-items-center justify-content-between">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">
                    {{ $book->category->name }}
                </span>
                <span class="badge bg-light text-muted border rounded-pill">
                    SMA / SMK
                </span>
            </div>
            
            <h3 class="fw-bold text-dark mb-2">{{ $book->title }}</h3>
            <p class="text-muted fs-6 mb-3">Penulis: <strong class="text-dark">{{ $book->author }}</strong></p>

            <hr class="my-3">

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="text-muted small">Penulis / Pengarang</div>
                    <div class="fw-semibold text-dark">{{ $book->author }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Tahun Terbit</div>
                    <div class="fw-semibold text-dark">{{ $book->year }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Penerbit</div>
                    <div class="fw-semibold text-dark">{{ $book->publisher }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Kota Terbit</div>
                    <div class="fw-semibold text-dark">{{ $book->city ?? 'Jakarta' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Nomor ISBN</div>
                    <div class="fw-semibold text-secondary">{{ $book->isbn ?? '-' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Status Ketersediaan</div>
                    <div class="fw-semibold text-primary">
                        <i class="bi bi-check-circle-fill me-1"></i> Akses Online 100% Aktif
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-3 border-top">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text-fill"></i> Rangkuman & Sinopsis Materi Buku:
                    </h6>
                    <span class="badge bg-light text-secondary border">Kurikulum Resmi SMA / SMK</span>
                </div>
                <div class="bg-light p-3 rounded-3 border-start border-4 border-primary text-secondary small lh-lg" style="white-space: pre-line;">
{{ $book->description ?? 'Belum ada ringkasan atau sinopsis untuk buku ini.' }}
                </div>

                @if($book->source_url)
                    <div class="mt-3 p-3 bg-light rounded-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 border">
                        <div class="small text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-primary fs-4"></i>
                            <div>
                                <div class="fw-bold">Sumber Resmi SIBI Kemendikbudristek</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Portal resmi Sistem Informasi Perbukuan Indonesia (Buku Teks Terverifikasi)</div>
                            </div>
                        </div>
                        <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center justify-content-center gap-1 text-nowrap shadow-sm">
                            <span>Buka Buku di SIBI</span> <i class="bi bi-box-arrow-up-right small ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Online Access & Admin Panel -->
    <div class="col-lg-4">
        <!-- Card 1: Akses Baca Perpustakaan Online -->
        @if($book->pdf_url)
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 small fw-semibold">
                        <i class="bi bi-book-half me-1"></i> E-Reader Aktif
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">100% Gratis</span>
                </div>
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-book-half text-primary"></i> Akses Baca Online
                </h5>
                <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.55;">
                    Buku materi pelajaran dan karya sastra ini dapat langsung dibaca secara utuh di peramban tanpa antrean dan hemat memori.
                </p>

                @auth
                    <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary w-100 py-2.5 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2 fw-semibold fs-6 mb-3">
                        <i class="bi bi-book-half"></i>
                        <span>Mulai Baca Online Sekarang</span>
                    </a>
                @else
                    <a href="{{ route('catalog.read', $book) }}" class="btn btn-primary w-100 py-2.5 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2 fw-semibold fs-6 mb-3">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Daftar untuk Baca Online</span>
                    </a>
                @endauth

                <div class="p-3 rounded-3 bg-light border small">
                    <div class="fw-bold text-dark mb-2" style="font-size: 0.78rem; letter-spacing: 0.3px;">
                        <i class="bi bi-check2-circle text-primary me-1"></i> KEUNGGULAN E-READER:
                    </div>
                    <ul class="list-unstyled mb-0 small text-secondary d-flex flex-column gap-1.5">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2 text-primary" style="font-size: 0.95rem;"></i>
                            <span>Mode kanvas hemat kuota & RAM</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2 text-primary" style="font-size: 0.95rem;"></i>
                            <span>Catatan belajar mandiri tersimpan privat</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2 text-primary" style="font-size: 0.95rem;"></i>
                            <span>Akses 24 jam di semua jenis HP, tablet & PC</span>
                        </li>
                    </ul>
                </div>
            </div>
        @endif

        <!-- Card 2: Panel Administrator -->
        @auth
            @if(auth()->user()->isAdmin())
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary"></i> Kelola Data Buku
                    </h5>
                    <div class="alert alert-primary-subtle border-0 small rounded-3 mb-3">
                        <i class="bi bi-person-badge text-primary me-1"></i> Anda login sebagai <strong>Administrator Perpustakaan Online</strong>.
                    </div>
                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning text-dark w-100 fw-bold py-2 mb-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Buku Ini
                    </a>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-primary w-100 py-2 rounded-pill">
                        <i class="bi bi-journal-bookmark me-1"></i> Kelola Semua Koleksi Buku
                    </a>
                </div>
            @endif
        @endauth
    </div>
</div>

<!-- Related Books Section -->
@if($relatedBooks->count() > 0)
    <div class="mt-5 pt-3 border-top">
        <h5 class="fw-bold mb-4">Buku Lainnya dalam Kategori {{ $book->category->name }}</h5>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            @foreach($relatedBooks as $rel)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm card-hover overflow-hidden">
                        <div style="height: 150px; background-color: #0f172a;">
                            @if($rel->cover_image)
                                <img src="{{ $rel->cover_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $rel->title }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-book fs-3"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold text-dark small mb-1 text-truncate">
                                <a href="{{ route('catalog.show', $rel) }}" class="text-decoration-none text-dark">
                                    {{ $rel->title }}
                                </a>
                            </h6>
                            <small class="text-muted mb-2">{{ $rel->author }}</small>
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center gap-1">
                                <span class="badge bg-light text-muted border rounded-pill" style="font-size: 0.7rem;">
                                    <i class="bi bi-book-half me-1"></i> E-Reader
                                </span>
                                <div class="d-flex gap-1">
                                    @if($rel->pdf_url)
                                        <a href="{{ route('catalog.read', $rel) }}" class="btn btn-sm btn-primary py-0 px-2 rounded-pill d-flex align-items-center gap-1" style="font-size: 0.75rem;" title="Baca Online">
                                            <i class="bi bi-book-half"></i> Baca
                                        </a>
                                    @endif
                                    <a href="{{ route('catalog.show', $rel) }}" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill" style="font-size: 0.75rem;">
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
