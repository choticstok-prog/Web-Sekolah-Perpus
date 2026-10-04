@extends('layouts.app')

@section('title', 'Katalog & E-Reader Buku Pelajaran SMA/SMK (Kelas 10-12) - PustakaSekolah')

@section('content')
<!-- Hero Section -->
<div class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm position-relative overflow-hidden" 
     style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 20px;">
    <!-- Background Glow Decoration -->
    <div class="position-absolute rounded-circle" 
         style="width: 320px; height: 320px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(37, 99, 235, 0) 70%); top: -80px; right: 10%; pointer-events: none;"></div>
    
    <div class="row align-items-center position-relative" style="z-index: 1;">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge px-3 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #ffffff !important; color: #1d4ed8 !important;">
                    <i class="bi bi-mortarboard-fill me-1"></i> Perpustakaan Resmi SMA / SMK
                </span>
                <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 px-3 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <span class="live-dot" style="background-color: #34d399;"></span> Fitur Baca Online Tersedia
                </span>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-cpu-fill"></i> Mode Ramah Device Kentang
                </span>
            </div>

            <h1 class="display-6 fw-bold mb-2">Pustaka & E-Reader Buku Pelajaran SMA & SMK</h1>
            <p class="lead opacity-90 mb-4 fs-6" style="max-width: 680px; line-height: 1.6;">
                Akses lengkap buku teks resmi Kurikulum Merdeka & K13: Sejarah, Matematika, Bahasa Indonesia, Bahasa Inggris, PPKn, PKWU, PJOK, Seni Budaya, dan PAI. Lengkap dengan sinopsis per bab, rujukan SIBI Kemdikbud, dan pembaca online interaktif langsung di browser.
            </p>
            
            <!-- Quick Live Stats Counter -->
            <div class="row g-2 mb-4 text-start" style="max-width: 620px;">
                <div class="col-4">
                    <div class="p-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-20 backdrop-blur">
                        <div class="fs-5 fw-bold text-white">{{ $stats['total_books'] ?? $books->total() }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Buku Pelajaran</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-20 backdrop-blur">
                        <div class="fs-5 fw-bold text-white">{{ $stats['total_categories'] ?? 9 }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Mata Pelajaran</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded-3 bg-success bg-opacity-20 border border-success border-opacity-40 backdrop-blur">
                        <div class="fs-5 fw-bold text-emerald-300 text-white d-flex align-items-center gap-1">
                            <i class="bi bi-book-half text-warning"></i> {{ $stats['total_online'] ?? 27 }}
                        </div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Bisa Baca Online</div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('catalog.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                @if(request('kelas'))
                    <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                @endif
                @if(request('baca_online'))
                    <input type="hidden" name="baca_online" value="{{ request('baca_online') }}">
                @endif
                <div class="hero-search-box flex-grow-1">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           class="form-control" 
                           placeholder="Cari buku pelajaran SMA/SMK, mapel, kelas 10-12, penulis, sinopsis...">
                    @if($search)
                        <a href="{{ route('catalog.index', ['kategori' => request('kategori'), 'kelas' => request('kelas'), 'baca_online' => request('baca_online')]) }}" 
                           class="btn btn-link text-muted pe-3 d-flex align-items-center text-decoration-none" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill text-secondary"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-dark rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-1 text-nowrap">
                        <span>Cari Buku</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4 d-none d-lg-block text-center position-relative">
            <div class="animate-float">
                <i class="bi bi-book-half text-white-50" style="font-size: 9rem; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.3));"></i>
            </div>
            <div class="badge bg-white text-dark shadow px-3 py-2 rounded-pill position-absolute bottom-0 start-50 translate-middle-x fw-semibold" style="font-size: 0.75rem;">
                <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Full E-Reader & Sinopsis Bab
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar: Kelas, Mode Baca Online, Kategori -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3 bg-white">
    <!-- Level / Kelas & Quick Toggles -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="small fw-bold text-muted me-1"><i class="bi bi-funnel me-1"></i>Jenjang Kelas:</span>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => ''])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ !request('kelas') ? 'btn-primary' : 'btn-light border' }}">
                Semua Kelas
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '10'])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('kelas') == '10' ? 'btn-primary' : 'btn-light border' }}">
                Kelas X (10)
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '11'])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('kelas') == '11' ? 'btn-primary' : 'btn-light border' }}">
                Kelas XI (11)
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '12'])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('kelas') == '12' ? 'btn-primary' : 'btn-light border' }}">
                Kelas XII (12)
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(request('baca_online'))
                <a href="{{ route('catalog.index', request()->except('baca_online', 'page')) }}" 
                   class="btn btn-sm btn-success rounded-pill px-3 fw-semibold shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-check2-circle"></i>
                    <span>Hanya Baca Online (Aktif)</span>
                    <span class="ms-1">&times;</span>
                </a>
            @else
                <a href="{{ route('catalog.index', array_merge(request()->all(), ['baca_online' => 1])) }}" 
                   class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold d-flex align-items-center gap-1">
                    <span class="live-dot me-1"></span>
                    <i class="bi bi-book-half"></i>
                    <span>Tampilkan Baca Online Saja</span>
                </a>
            @endif

            @if(request()->hasAny(['kategori', 'kelas', 'search', 'baca_online']))
                <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
            @endif
        </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="small fw-bold text-muted text-nowrap me-1"><i class="bi bi-bookmarks me-1"></i>Mapel:</span>
        <a href="{{ route('catalog.index', array_merge(request()->except('kategori', 'page'), ['kategori' => ''])) }}" 
           class="btn btn-sm rounded-pill px-3 py-1 text-nowrap {{ !request('kategori') ? 'btn-primary' : 'btn-outline-secondary' }}">
            Semua Mapel
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $cat->slug])) }}" 
               class="btn btn-sm rounded-pill px-3 py-1 text-nowrap d-inline-flex align-items-center gap-1.5 {{ request('kategori') == $cat->slug ? 'btn-primary fw-semibold' : 'btn-outline-secondary' }}">
                <span>{{ $cat->name }}</span>
                <span class="category-count-badge">{{ $cat->books_count }}</span>
            </a>
        @endforeach
    </div>
</div>

@if($selectedCategory || request('kelas') || request('baca_online'))
    <div class="alert alert-info border-0 shadow-sm rounded-3 py-2 px-3 d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="small">
            <i class="bi bi-info-circle-fill me-1"></i>
            Menampilkan buku:
            @if($selectedCategory)
                Kategori <strong>{{ $selectedCategory->name }}</strong>
            @endif
            @if(request('kelas'))
                <span class="badge bg-primary text-white rounded-pill ms-1">Kelas {{ request('kelas') }}</span>
            @endif
            @if(request('baca_online'))
                <span class="badge bg-success text-white rounded-pill ms-1"><i class="bi bi-book-half me-1"></i> Siap Baca Online</span>
            @endif
            <span class="text-muted ms-2">({{ $books->total() }} buku ditemukan)</span>
        </div>
        <a href="{{ route('catalog.index', ['search' => $search]) }}" class="text-info fw-bold small text-decoration-none">
            Reset Filter &times;
        </a>
    </div>
@endif

<!-- Books Grid -->
@if($books->count() > 0)
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
        @foreach($books as $book)
            <div class="col">
                <div class="card h-100 card-hover card-shine border-0 shadow-sm overflow-hidden animate-fade-in-up position-relative">
                    <!-- Cover Image Container -->
                    <div class="position-relative overflow-hidden" style="height: 240px; background-color: #0f172a;">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}" 
                                 class="w-100 h-100 object-fit-cover transition" 
                                 style="transition: transform 0.4s ease;"
                                 alt="{{ $book->title }}"
                                 onmouseover="this.style.transform='scale(1.04)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                                <i class="bi bi-book fs-1 mb-2 text-secondary"></i>
                                <span class="small fw-semibold text-center px-3">{{ $book->title }}</span>
                            </div>
                        @endif

                        <!-- Subject Tag (Badge Putih dengan Teks Hitam Pekat Jelas) -->
                        <span class="position-absolute top-0 start-0 m-2 badge book-category-badge rounded-pill px-2.5 py-1 small shadow-sm">
                            <i class="bi bi-bookmark-fill text-warning me-1"></i><span class="category-name-text">{{ $book->category->name }}</span>
                        </span>

                        <!-- Stock Status -->
                        @if($book->isAvailable())
                            <span class="position-absolute top-0 end-0 m-2 badge bg-success bg-opacity-90 rounded-pill px-2 py-1 small shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Fisik: {{ $book->available_stock }}
                            </span>
                        @else
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger bg-opacity-90 rounded-pill px-2 py-1 small shadow-sm">
                                <i class="bi bi-x-circle me-1"></i> Fisik Habis
                            </span>
                        @endif

                        <!-- Baca Online Indicator Pill -->
                        @if($book->pdf_url)
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-success bg-opacity-95 text-white rounded-pill px-2 py-1 small shadow-sm d-flex align-items-center gap-1 backdrop-blur">
                                <span class="live-dot" style="width: 6px; height: 6px; background-color: #ffffff;"></span>
                                <span>E-Reader Siap</span>
                            </span>
                        @endif
                    </div>
                    
                    <!-- Book Details Body -->
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $book->title }}">
                            <a href="{{ route('catalog.show', $book) }}" class="text-decoration-none text-dark">
                                {{ $book->title }}
                            </a>
                        </h6>
                        <div class="text-muted small mb-2 d-flex align-items-center justify-content-between">
                            <span class="text-truncate me-2"><i class="bi bi-person me-1"></i>{{ $book->author }}</span>
                            <span class="badge bg-light text-secondary border rounded-pill">{{ $book->year }}</span>
                        </div>
                        
                        <!-- Synopsis Preview Box -->
                        <div class="bg-light p-2 rounded-2 mb-3 flex-grow-1 border-start border-3 border-primary">
                            <div class="text-primary fw-bold small d-flex align-items-center gap-1 mb-1" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                                <i class="bi bi-file-earmark-text"></i> RINGKASAN MATERI:
                            </div>
                            <p class="card-text text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45; font-size: 0.78rem;">
                                {{ $book->description ?? 'Tidak ada sinopsis tersedia untuk buku ini.' }}
                            </p>
                        </div>
                        
                        <!-- Metadata Row (City & Source) -->
                        <div class="d-flex align-items-center justify-content-between small text-muted mb-3 pt-1 border-top">
                            <span class="text-truncate">
                                <i class="bi bi-geo-alt me-1 text-secondary"></i>{{ $book->city ?? 'Jakarta' }}
                            </span>
                            @if($book->source_url)
                                <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" 
                                   class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none fw-semibold" 
                                   title="Buka Buku di Sumber Resmi SIBI Kemdikbud">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> SIBI
                                </a>
                            @endif
                        </div>

                        <!-- Action Buttons: Detail & Pinjam + Baca Online -->
                        <div class="d-grid gap-2">
                            @if($book->pdf_url)
                                <a href="{{ route('catalog.read', $book) }}" 
                                   class="btn btn-sm btn-reader py-2 rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="bi bi-book-half"></i>
                                    <span>Baca Online Sekarang</span>
                                </a>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('catalog.show', $book) }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill w-100 py-1">
                                    <i class="bi bi-info-circle me-1"></i> Detail & Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="card border-0 shadow-sm p-5 text-center my-4 animate-fade-in-up">
        <i class="bi bi-search text-muted mb-3" style="font-size: 3.5rem;"></i>
        <h5 class="fw-bold">Tidak Ada Buku Ditemukan</h5>
        <p class="text-muted small mb-3">
            Buku yang Anda cari dengan kriteria yang dipilih belum ditemukan. Silakan periksa kembali kata kunci pencarian atau reset filter.
        </p>
        <div>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Lihat Semua Koleksi Buku
            </a>
        </div>
    </div>
@endif
@endsection
