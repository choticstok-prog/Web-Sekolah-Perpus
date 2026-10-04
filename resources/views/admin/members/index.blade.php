@extends('layouts.app')

@section('title', 'Data Anggota & Siswa - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-people text-secondary me-2"></i>Data Anggota Perpustakaan</h3>
        <p class="text-muted small mb-0">Daftar pengguna terdaftar yang berhak meminjam koleksi buku.</p>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
    <form action="{{ route('admin.members.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control border-start-0" 
                       placeholder="Cari nama anggota, email, atau nomor telepon...">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Cari Anggota</button>
            @if($search)
                <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Anggota</th>
                    <th>Kontak & Alamat</th>
                    <th>Total Peminjaman</th>
                    <th>Sedang Dipinjam</th>
                    <th>Tgl Bergabung</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($m->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $m->name }}</div>
                                    <small class="text-muted">{{ $m->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-telephone text-muted me-1"></i>{{ $m->phone ?? '-' }}</div>
                            <small class="text-muted text-truncate d-block" style="max-width: 250px;"><i class="bi bi-geo-alt me-1"></i>{{ $m->address ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $m->total_borrowings }} kali
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $m->active_borrowings > 0 ? 'bg-info text-dark' : 'bg-secondary-subtle text-secondary' }} px-2 py-1 rounded-pill">
                                {{ $m->active_borrowings }} buku aktif
                            </span>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $m->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.members.show', $m) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-clock-history me-1"></i> Riwayat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada anggota perpustakaan yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $members->links('pagination::bootstrap-5') }}
</div>
@endsection
