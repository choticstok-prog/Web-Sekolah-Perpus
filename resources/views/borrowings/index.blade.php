@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Buku Siswa - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Peminjaman Buku Saya</h3>
        <p class="text-muted small mb-0">Pantau buku paket atau modul sejarah yang sedang dipinjam, status persetujuan petugas, dan batas pengembalian.</p>
    </div>
    <a href="{{ route('catalog.index') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-circle me-1"></i> Pinjam Buku Lain
    </a>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Peminjaman</div>
                    <div class="fs-4 fw-bold text-dark">{{ $stats['total'] }}</div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Sedang Dipinjam</div>
                    <div class="fs-4 fw-bold text-primary">{{ $stats['dipinjam'] }}</div>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Menunggu Respon</div>
                    <div class="fs-4 fw-bold text-warning">{{ $stats['menunggu'] }}</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Telah Dikembalikan</div>
                    <div class="fs-4 fw-bold text-success">{{ $stats['dikembalikan'] }}</div>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check2-all"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4 gap-2">
    <li class="nav-item">
        <a class="nav-link rounded-pill {{ !request('status') ? 'active' : 'bg-white border' }}" 
           href="{{ route('borrowings.index') }}">
            Semua
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link rounded-pill {{ request('status') == 'menunggu' ? 'active' : 'bg-white border' }}" 
           href="{{ route('borrowings.index', ['status' => 'menunggu']) }}">
            Menunggu Persetujuan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link rounded-pill {{ request('status') == 'dipinjam' ? 'active' : 'bg-white border' }}" 
           href="{{ route('borrowings.index', ['status' => 'dipinjam']) }}">
            Sedang Dipinjam
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link rounded-pill {{ request('status') == 'dikembalikan' ? 'active' : 'bg-white border' }}" 
           href="{{ route('borrowings.index', ['status' => 'dikembalikan']) }}">
            Sudah Dikembalikan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link rounded-pill {{ request('status') == 'ditolak' ? 'active' : 'bg-white border' }}" 
           href="{{ route('borrowings.index', ['status' => 'ditolak']) }}">
            Ditolak
        </a>
    </li>
</ul>

<!-- Borrowing List -->
@if($borrowings->count() > 0)
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Pinjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Denda / Keterangan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $b)
                        @php
                            $isOverdue = $b->isOverdue();
                            $fine = $b->calculateOverdueFine();
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark font-monospace small">{{ $b->borrow_code }}</span>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $b->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 45px; height: 60px; background: #e2e8f0; border-radius: 6px; overflow: hidden; flex-shrink: 0;">
                                        @if($b->book->cover_image)
                                            <img src="{{ $b->book->cover_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $b->book->title }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('catalog.show', $b->book) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $b->book->title }}
                                        </a>
                                        <div class="text-muted small">
                                            <span class="badge bg-light text-dark border">{{ $b->book->category->name }}</span>
                                            <span class="ms-1">{{ $b->book->author }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold">{{ $b->borrow_date->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="small fw-semibold {{ $isOverdue ? 'text-danger' : '' }}">
                                    {{ $b->due_date->format('d M Y') }}
                                </div>
                                @if($isOverdue)
                                    <span class="badge badge-terlambat">Lewat Jatuh Tempo</span>
                                @endif
                            </td>
                            <td>
                                @if($b->status == 'menunggu')
                                    <span class="badge badge-status badge-menunggu">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                    </span>
                                @elseif($b->status == 'dipinjam')
                                    <span class="badge badge-status badge-dipinjam">
                                        <i class="bi bi-book me-1"></i> Dipinjam
                                    </span>
                                @elseif($b->status == 'dikembalikan')
                                    <span class="badge badge-status badge-dikembalikan">
                                        <i class="bi bi-check2-circle me-1"></i> Dikembalikan
                                    </span>
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                        Kembali: {{ $b->return_date ? $b->return_date->format('d M Y') : '-' }}
                                    </div>
                                @elseif($b->status == 'ditolak')
                                    <span class="badge badge-status badge-ditolak">
                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($b->status == 'dipinjam' && $isOverdue)
                                    <div class="text-danger fw-bold small">
                                        Denda: Rp {{ number_format($fine, 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">(Rp 1.000 / hari terlambat)</small>
                                @elseif($b->status == 'dikembalikan' && $b->fine_amount > 0)
                                    <div class="text-danger small">
                                        Denda Dibayar: Rp {{ number_format($b->fine_amount, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif

                                @if($b->admin_notes)
                                    <div class="small text-secondary mt-1" style="font-size: 0.75rem;" title="{{ $b->admin_notes }}">
                                        <em>Catatan: {{ Str::limit($b->admin_notes, 30) }}</em>
                                    </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($b->status == 'menunggu')
                                    <form action="{{ route('borrowings.cancel', $b) }}" method="POST" onsubmit="return confirm('Batalkan permohonan peminjaman buku ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="bi bi-trash"></i> Batalkan
                                        </button>
                                    </form>
                                @elseif($b->status == 'dipinjam')
                                    <span class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i> Kembalikan ke petugas
                                    </span>
                                @else
                                    <a href="{{ route('catalog.show', $b->book) }}" class="btn btn-sm btn-light border rounded-pill">
                                        Pinjam Lagi
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $borrowings->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="card border-0 shadow-sm p-5 text-center my-4">
        <i class="bi bi-inbox text-muted mb-3" style="font-size: 3.5rem;"></i>
        <h5 class="fw-bold">Belum Ada Riwayat Peminjaman</h5>
        <p class="text-muted small mb-3">
            @if(request('status'))
                Tidak ada peminjaman dengan status "{{ request('status') }}".
            @else
                Anda belum pernah meminjam buku. Temukan buku yang ingin dibaca di katalog kami!
            @endif
        </p>
        <div>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-search me-1"></i> Jelajahi Katalog Buku
            </a>
        </div>
    </div>
@endif
@endsection
