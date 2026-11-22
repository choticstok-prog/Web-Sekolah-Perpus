@extends('layouts.app')

@section('title', 'Dashboard Pengelola Perpustakaan Online - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Pengelola Perpustakaan Sekolah
        </h3>
        <p class="text-muted small mb-0">Ringkasan operasional perpustakaan online, katalog e-reader digital, kategori mata pelajaran & sastra, dan data anggota.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.books.export') }}" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center" title="Unduh Langsung Laporan Inventaris Data Buku (PDF)">
            <i class="bi bi-file-earmark-pdf-fill me-1.5"></i> Ekspor Laporan (PDF)
        </a>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
            <i class="bi bi-plus-lg me-1.5"></i> Tambah Buku Baru
        </a>
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center">
            <i class="bi bi-journal-bookmark me-1.5"></i> Kelola Data Buku
        </a>
        <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center">
            <i class="bi bi-book-half me-1.5"></i> Buka Perpus Online
        </a>
    </div>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Total Judul Buku</span>
                    <div class="stat-number text-primary">{{ $stats['total_books'] }}</div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
            <a href="{{ route('admin.books.index') }}" class="stat-link stat-link-primary">
                <span>Kelola Data Buku</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Siap Baca Online</span>
                    <div class="stat-number text-success">{{ $stats['total_online'] }}</div>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-laptop"></i>
                </div>
            </div>
            <a href="{{ route('catalog.index', ['baca_online' => 1]) }}" class="stat-link stat-link-success">
                <span>Koleksi E-Reader</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Kategori & Jenjang</span>
                    <div class="stat-number text-warning">{{ $stats['total_categories'] }}</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-tags-fill"></i>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="stat-link stat-link-warning">
                <span>Kelola Kategori</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Total Anggota</span>
                    <div class="stat-number text-info">{{ $stats['total_members'] }}</div>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <a href="{{ route('admin.members.index') }}" class="stat-link stat-link-info">
                <span>Lihat Data Anggota</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Recent Books & Quick Edit -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-journal-text me-1 text-primary"></i> Koleksi Buku & E-Reader Terbaru (Siap Edit & Kelola)
                </h6>
                <a href="{{ route('admin.books.index') }}" class="small text-decoration-none fw-semibold">
                    Kelola Semua Buku &rarr;
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Buku & Penulis</th>
                            <th>Kategori</th>
                            <th>Status E-Reader</th>
                            <th>Penerbit / Tahun</th>
                            <th class="text-end pe-3">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBooks as $rb)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2.5">
                                        @if($rb->cover_image)
                                            <img src="{{ $rb->cover_image }}" alt="Cover" class="rounded shadow-sm flex-shrink-0" style="width: 38px; height: 52px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-secondary-subtle d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 38px; height: 52px;">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        @endif
                                        <div class="text-truncate" style="max-width: 240px;">
                                            <a href="{{ route('admin.books.edit', $rb) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate small" title="{{ $rb->title }}">
                                                {{ $rb->title }}
                                            </a>
                                            <div class="text-muted small" style="font-size: 0.75rem;">
                                                {{ $rb->author ?: 'Kemendikbudristek' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        {{ $rb->category ? $rb->category->name : 'Umum' }}
                                    </span>
                                </td>
                                <td>
                                    @if($rb->pdf_url)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small d-inline-flex align-items-center gap-1">
                                            <span class="live-dot" style="width: 5px; height: 5px;"></span> E-Reader Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 small">
                                            Tanpa File PDF
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-truncate" style="max-width: 140px;">{{ $rb->publisher ?: '-' }}</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">{{ $rb->year ?: '-' }}</div>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <a href="{{ route('admin.books.edit', $rb) }}" class="btn btn-sm btn-outline-warning py-1 px-2.5 rounded-pill fw-semibold" title="Edit Data Buku">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </a>
                                        @if($rb->pdf_url)
                                            <a href="{{ route('catalog.read', $rb) }}" target="_blank" class="btn btn-sm btn-outline-success py-1 px-2 rounded-pill" title="Baca Buku Online (E-Reader)">
                                                <i class="bi bi-book-half"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    <i class="bi bi-inbox text-muted fs-3 d-block mb-1"></i>
                                    Belum ada data buku di perpustakaan online.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Online Library Distribution & Recent Members -->
    <div class="col-lg-4">
        <!-- Distribution by Level -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-pie-chart-fill text-primary me-1"></i> Distribusi Koleksi Online
                </h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill small">74 Judul</span>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="fw-semibold text-dark"><i class="bi bi-mortarboard-fill text-primary me-1"></i> Buku Teks SMA / SMK</span>
                            <span class="badge bg-primary rounded-pill">{{ $stats['sma_books'] }} Judul</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 36.5%;" aria-valuenow="27" aria-valuemin="0" aria-valuemax="74"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Kelas 10, 11, dan 12 Kurikulum Nasional</small>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="fw-semibold text-dark"><i class="bi bi-book-fill text-info me-1"></i> Buku Teks SMP / MTs</span>
                            <span class="badge bg-info text-dark rounded-pill">{{ $stats['smp_books'] }} Judul</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 36.5%;" aria-valuenow="27" aria-valuemin="0" aria-valuemax="74"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Kelas 7, 8, dan 9 Kurikulum Merdeka</small>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span class="fw-semibold text-dark"><i class="bi bi-journal-richtext text-warning me-1"></i> Novel & Sastra Fiksi</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $stats['novel_books'] }} Judul</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 27%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="74"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Koleksi Mahakarya Sastra Nusantara</small>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('admin.books.create') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill py-2 fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Koleksi Buku Digital
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Members -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people text-success me-1"></i> Siswa / Anggota Baru
                </h6>
                <a href="{{ route('admin.members.index') }}" class="small text-decoration-none">Semua &rarr;</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentMembers as $rm)
                    <div class="list-group-item d-flex align-items-center gap-3 py-2 px-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                            {{ strtoupper(substr($rm->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-semibold small text-truncate">{{ $rm->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $rm->email }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted small">
                        <i class="bi bi-people text-muted fs-3 d-block mb-1"></i>
                        Belum ada anggota terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
