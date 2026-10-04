@extends('layouts.app')

@section('title', 'Dashboard Petugas Perpustakaan - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Pengelola Perpustakaan Sekolah
        </h3>
        <p class="text-muted small mb-0">Ringkasan operasional peminjaman buku pelajaran SMA & SMK, katalog digital, dan data anggota perpustakaan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
            <i class="bi bi-plus-lg me-1.5"></i> Tambah Buku
        </a>
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center">
            <i class="bi bi-arrow-left-right me-1.5"></i> Kelola Peminjaman
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
                <span>Lihat Data Buku</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Peminjaman Aktif</span>
                    <div class="stat-number text-info">{{ $stats['active_borrowings'] }}</div>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-journal-arrow-up"></i>
                </div>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'dipinjam']) }}" class="stat-link stat-link-info">
                <span>Lihat Peminjaman</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Menunggu Verifikasi</span>
                    <div class="stat-number text-warning">{{ $stats['pending_borrowings'] }}</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'menunggu']) }}" class="stat-link stat-link-warning">
                <span>Lihat Pengajuan</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="stat-label">Total Anggota</span>
                    <div class="stat-number text-success">{{ $stats['total_members'] }}</div>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <a href="{{ route('admin.members.index') }}" class="stat-link stat-link-success">
                <span>Lihat Data Anggota</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Recent Borrowings -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Aktivitas Peminjaman Terbaru
                </h6>
                <a href="{{ route('admin.borrowings.index') }}" class="small text-decoration-none fw-semibold">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode / Peminjam</th>
                            <th>Judul Buku</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBorrowings as $rb)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold font-monospace small">{{ $rb->borrow_code }}</div>
                                    <div class="small text-muted">{{ $rb->user->name }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-truncate small" style="max-width: 220px;" title="{{ $rb->book->title }}">
                                        {{ $rb->book->title }}
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.7rem;">
                                        {{ $rb->book->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">{{ $rb->due_date->format('d/m/Y') }}</div>
                                    @if($rb->isOverdue())
                                        <span class="badge badge-terlambat" style="font-size: 0.65rem;">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rb->status == 'menunggu')
                                        <span class="badge badge-status badge-menunggu">Menunggu</span>
                                    @elseif($rb->status == 'dipinjam')
                                        <span class="badge badge-status badge-dipinjam">Dipinjam</span>
                                    @elseif($rb->status == 'dikembalikan')
                                        <span class="badge badge-status badge-dikembalikan">Kembali</span>
                                    @elseif($rb->status == 'ditolak')
                                        <span class="badge badge-status badge-ditolak">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($rb->status == 'menunggu')
                                        <form action="{{ route('admin.borrowings.approve', $rb) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success py-1 px-2.5 rounded-pill shadow-sm fw-semibold" title="Setujui Peminjaman">
                                                <i class="bi bi-check-lg me-1"></i> Setujui
                                            </button>
                                        </form>
                                    @elseif($rb->status == 'dipinjam')
                                        <a href="{{ route('admin.borrowings.index', ['search' => $rb->borrow_code]) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-pill fw-semibold">
                                            Kelola
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    <i class="bi bi-inbox text-muted fs-3 d-block mb-1"></i>
                                    Belum ada aktivitas peminjaman buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Alerts & Recent Members -->
    <div class="col-lg-4">
        <!-- Low Stock Alert -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle text-warning me-1"></i> Stok Buku Menipis (&le; 1)
                </h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($lowStockBooks as $ls)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <div class="text-truncate pe-2">
                            <div class="fw-semibold small text-truncate">{{ $ls->title }}</div>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $ls->category->name }}</span>
                        </div>
                        <span class="badge {{ $ls->available_stock == 0 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
                            Sisa: {{ $ls->available_stock }}
                        </span>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted small">
                        <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                        Semua stok buku mencukupi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Members -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people text-success me-1"></i> Anggota Baru
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
