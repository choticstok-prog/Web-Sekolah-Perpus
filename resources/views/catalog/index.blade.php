@extends('layouts.app')

@section('title', 'Koleksi Buku & Novel Digital - Perpustakaan Online PustakaSekolah')

@section('content')
<!-- Header Katalog (Bersih, Rapi & Tidak Ramai) -->
<div class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm position-relative overflow-hidden" 
     style="background: #0f172a; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.08);">
    
    <div class="row align-items-center position-relative">
        <div class="col-lg-9">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge px-3 py-1.5 rounded-pill fw-semibold" 
                      style="background-color: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 0.8rem;">
                    <i class="bi bi-mortarboard-fill me-1"></i> Perpustakaan Digital
                </span>
                <span class="badge px-3 py-1.5 rounded-pill fw-medium" 
                      style="background-color: rgba(255, 255, 255, 0.08); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.8rem;">
                    {{ $stats['total_books'] ?? $books->total() }} Koleksi Tersedia
                </span>
            </div>

            <h1 class="display-6 fw-bold mb-2 text-white">Koleksi Buku & Novel Digital</h1>
            <p class="lead opacity-90 mb-4 fs-6" style="max-width: 660px; line-height: 1.6; color: #cbd5e1;">
                Buku pelajaran resmi Kurikulum Merdeka SMP (Kelas 7–9), SMA & SMK (Kelas 10–12), serta 20 novel sastra karya penulis legendaris Indonesia. Semua dapat dibaca langsung dari peramban.
            </p>

            <!-- Search Bar (Harmonis & Elegan) -->
            <form action="{{ route('catalog.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2" style="max-width: 640px;">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                @if(request('kelas'))
                    <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                @endif
                @if(request('tahun'))
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                @endif
                @if(request('baca_online'))
                    <input type="hidden" name="baca_online" value="{{ request('baca_online') }}">
                @endif
                <div class="hero-search-box flex-grow-1">
                    <span class="input-group-text">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           class="form-control" 
                           placeholder="Cari judul, pengarang, 20 novel, atau tahun terbit (2024, 2009...)...">
                    @if($search)
                        <a href="{{ route('catalog.index', ['kategori' => request('kategori'), 'kelas' => request('kelas'), 'tahun' => request('tahun'), 'baca_online' => request('baca_online')]) }}" 
                           class="btn btn-link text-muted pe-3 d-flex align-items-center text-decoration-none" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill text-secondary"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary fw-semibold px-4 py-2 text-white rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-1 text-nowrap">
                        <span>Cari Buku</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-3 d-none d-lg-block text-end">
            <div class="p-3 rounded-4 d-inline-block text-center" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); min-width: 170px;">
                <div class="fs-4 fw-bold text-white">{{ $stats['total_books'] ?? $books->total() }}</div>
                <div class="small" style="color: #94a3b8;">Total Judul</div>
                <hr class="my-2 border-secondary border-opacity-25">
                <div class="fs-6 fw-semibold text-primary">100% Online</div>
                <div class="small" style="color: #94a3b8;">Siap Baca</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar: Jenjang, Mode Baca Online & Kategori -->
<div class="card border-0 shadow-sm p-3 mb-4 rounded-3 bg-white">
    <!-- Level / Jenjang & Filter Cepat -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <span class="small fw-semibold text-muted me-1"><i class="bi bi-funnel me-1"></i>Jenjang:</span>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => ''])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ !request('kelas') && request('kategori') != 'novel-fiksi' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Semua
            </a>

            <a href="{{ route('catalog.index', array_merge(request()->except('kategori', 'page'), ['kategori' => 'novel-fiksi'])) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('kategori') == 'novel-fiksi' ? 'btn-primary fw-semibold' : 'btn-light border text-muted' }}">
                📖 20 Novel Fiksi
            </a>
            
            <span class="text-muted opacity-50 mx-1">|</span>

            <span class="small text-muted me-1">SMP:</span>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '7'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '7' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 7
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '8'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '8' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 8
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '9'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '9' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 9
            </a>

            <span class="text-muted opacity-50 mx-1">|</span>

            <span class="small text-muted me-1">SMA/SMK:</span>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '10'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '10' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 10
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '11'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '11' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 11
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('kelas', 'page'), ['kelas' => '12'])) }}" 
               class="btn btn-sm rounded-pill px-2.5 {{ request('kelas') == '12' ? 'btn-primary' : 'btn-light border text-muted' }}">
                Kelas 12
            </a>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Filter Tahun Terbit Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm rounded-pill px-3 dropdown-toggle d-inline-flex align-items-center gap-1 {{ request('tahun') ? 'btn-primary fw-semibold shadow-sm' : 'btn-light border text-muted' }}" 
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-calendar-event"></i>
                    <span>{{ request('tahun') ? 'Tahun: ' . request('tahun') : 'Tahun Terbit' }}</span>
                </button>
                <ul class="dropdown-menu shadow-sm border-0 rounded-3 mt-1 py-1" style="max-height: 280px; overflow-y: auto; font-size: 0.85rem;">
                    <li>
                        <a class="dropdown-item py-1.5 {{ !request('tahun') ? 'active fw-semibold' : '' }}" 
                           href="{{ route('catalog.index', request()->except('tahun', 'page')) }}">
                            Semua Tahun
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    @foreach($availableYears as $yr)
                        <li>
                            <a class="dropdown-item py-1.5 {{ request('tahun') == $yr ? 'active fw-semibold' : '' }}" 
                               href="{{ route('catalog.index', array_merge(request()->except('page'), ['tahun' => $yr])) }}">
                                Tahun {{ $yr }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if(request('baca_online'))
                <a href="{{ route('catalog.index', request()->except('baca_online', 'page')) }}" 
                   class="btn btn-sm btn-primary rounded-pill px-3 fw-medium shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-check2"></i>
                    <span>Baca Online (Aktif)</span>
                    <span class="ms-1">&times;</span>
                </a>
            @else
                <a href="{{ route('catalog.index', array_merge(request()->all(), ['baca_online' => 1])) }}" 
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium d-flex align-items-center gap-1">
                    <i class="bi bi-book-half"></i>
                    <span>Hanya Baca Online</span>
                </a>
            @endif

            @if(request()->hasAny(['kategori', 'kelas', 'search', 'baca_online', 'tahun']))
                <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            @endif
        </div>
    </div>

    <!-- Category Filter Pills (Serasi & Elegan) -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="small fw-semibold text-muted text-nowrap me-1"><i class="bi bi-tags me-1"></i>Kategori:</span>
        <a href="{{ route('catalog.index', array_merge(request()->except('kategori', 'page'), ['kategori' => ''])) }}" 
           class="btn btn-sm rounded-pill px-3 py-1 text-nowrap {{ !request('kategori') ? 'btn-primary' : 'btn-outline-secondary' }}">
            Semua
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
    <div class="alert alert-light border shadow-sm rounded-3 py-2 px-3 d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="small text-muted">
            <i class="bi bi-info-circle me-1 text-primary"></i>
            Menampilkan buku:
            @if($selectedCategory)
                Kategori <strong class="text-dark">{{ $selectedCategory->name }}</strong>
            @endif
            @if(request('kelas'))
                <span class="badge bg-primary text-white rounded-pill ms-1">Kelas {{ request('kelas') }}</span>
            @endif
            @if(request('baca_online'))
                <span class="badge bg-secondary text-white rounded-pill ms-1"><i class="bi bi-book-half me-1"></i> Siap Baca Online</span>
            @endif
            <span class="ms-2">({{ $books->total() }} buku ditemukan)</span>
        </div>
        <a href="{{ route('catalog.index', ['search' => $search]) }}" class="text-primary fw-semibold small text-decoration-none">
            Reset Filter &times;
        </a>
    </div>
@endif

<!-- Books Grid (Rapi, Teratur & Tidak Nabrak) -->
@if($books->count() > 0)
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
        @foreach($books as $book)
            <div class="col">
                <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden position-relative">
                    <!-- Cover Image Container -->
                    <div class="position-relative overflow-hidden" style="height: 240px; background-color: #0f172a;">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}" 
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="{{ $book->title }}"
                                 loading="lazy">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                                <i class="bi bi-book fs-1 mb-2 text-secondary"></i>
                                <span class="small fw-semibold text-center px-3">{{ $book->title }}</span>
                            </div>
                        @endif

                        <!-- Subject Tag (Satu Badge Elegan di Sudut) -->
                        <span class="position-absolute top-0 start-0 m-2 badge book-category-badge rounded-pill px-2.5 py-1 small shadow-sm">
                            <span class="category-name-text">{{ $book->category->name }}</span>
                        </span>
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
                        <div class="bg-light p-2.5 rounded-2 mb-3 flex-grow-1 border-start border-3 border-primary">
                            <div class="text-primary fw-semibold small d-flex align-items-center gap-1 mb-1" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                                <i class="bi bi-file-earmark-text"></i> RINGKASAN:
                            </div>
                            <p class="card-text text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45; font-size: 0.78rem;">
                                {{ $book->description ?? 'Tidak ada sinopsis tersedia untuk buku ini.' }}
                            </p>
                        </div>
                        
                        <!-- Metadata Row (City & Source) -->
                        <div class="d-flex align-items-center justify-content-between small text-muted mb-3 pt-1 border-top">
                            <span class="text-truncate">
                                <i class="bi bi-geo-alt me-1 text-secondary"></i>{{ $book->city ?? 'Indonesia' }}
                            </span>
                            @if($book->source_url)
                                <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" 
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none fw-medium" 
                                    title="Sumber Buku Resmi">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Sumber
                                </a>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($book->pdf_url)
                                <a href="{{ route('catalog.read', $book) }}" 
                                   class="btn btn-sm btn-primary py-2 rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm fw-semibold">
                                    <i class="bi bi-book-half"></i>
                                    <span>Baca Online Sekarang</span>
                                </a>
                            @endif

                            <a href="{{ route('catalog.show', $book) }}" class="btn btn-sm btn-outline-primary fw-medium rounded-pill w-100 py-1.5">
                                <i class="bi bi-info-circle me-1"></i> Informasi Buku
                            </a>
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
    <div class="card border-0 shadow-sm p-5 text-center my-4">
        <i class="bi bi-search text-muted mb-3" style="font-size: 3rem;"></i>
        <h5 class="fw-bold">Tidak Ada Buku Ditemukan</h5>
        <p class="text-muted small mb-3">
            Buku dengan kriteria yang dicari belum ditemukan. Silakan periksa kembali kata kunci pencarian.
        </p>
        <div>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Lihat Semua Buku
            </a>
        </div>
    </div>
@endif
@endsection
