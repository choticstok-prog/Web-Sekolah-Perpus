@extends('layouts.app')

@section('title', 'Riwayat Siswa/Anggota: ' . $member->name . ' - PustakaSekolah')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Anggota
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Member Profile Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 text-center">
            <div class="rounded-circle bg-primary-subtle text-primary fw-bold mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $member->name }}</h5>
            <span class="badge bg-primary rounded-pill px-3 py-1 mb-3">Anggota Perpustakaan</span>

            <div class="text-start border-top pt-3 small">
                <div class="mb-2">
                    <span class="text-muted">Email:</span><br>
                    <strong class="text-dark">{{ $member->email }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">No. Telepon / WhatsApp:</span><br>
                    <strong class="text-dark">{{ $member->phone ?? '-' }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Alamat:</span><br>
                    <strong class="text-dark">{{ $member->address ?? '-' }}</strong>
                </div>
                <div>
                    <span class="text-muted">Bergabung Sejak:</span><br>
                    <strong class="text-dark">{{ $member->created_at->format('d F Y') }}</strong>
                </div>
            </div>

            <div class="row g-2 mt-3 pt-3 border-top text-center">
                <div class="col-6">
                    <div class="fs-5 fw-bold text-dark">{{ $member->total_borrowings }}</div>
                    <small class="text-muted">Total Pinjam</small>
                </div>
                <div class="col-6">
                    <div class="fs-5 fw-bold text-primary">{{ $member->active_borrowings }}</div>
                    <small class="text-muted">Sedang Aktif</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Borrowings History -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent py-3 border-bottom">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Seluruh Riwayat Peminjaman Buku
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Pinjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $b)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace small text-dark">{{ $b->borrow_code }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-truncate" style="max-width: 180px;" title="{{ $b->book->title }}">
                                        {{ $b->book->title }}
                                    </div>
                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $b->book->category->name }}</span>
                                </td>
                                <td>
                                    <div class="small">{{ $b->borrow_date->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="small {{ $b->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                        {{ $b->due_date->format('d/m/Y') }}
                                    </div>
                                    @if($b->isOverdue())
                                        <span class="badge badge-terlambat" style="font-size: 0.65rem;">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->status == 'menunggu')
                                        <span class="badge badge-status badge-menunggu">Menunggu</span>
                                    @elseif($b->status == 'dipinjam')
                                        <span class="badge badge-status badge-dipinjam">Dipinjam</span>
                                    @elseif($b->status == 'dikembalikan')
                                        <span class="badge badge-status badge-dikembalikan">Kembali</span>
                                    @elseif($b->status == 'ditolak')
                                        <span class="badge badge-status badge-ditolak">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->fine_amount > 0)
                                        <span class="text-danger fw-bold small">Rp {{ number_format($b->fine_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">
                                    Anggota ini belum memiliki riwayat peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $borrowings->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
