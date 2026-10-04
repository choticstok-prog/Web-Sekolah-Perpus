@extends('layouts.app')

@section('title', 'Kelola Transaksi Peminjaman - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-arrow-left-right text-warning me-2"></i>Kelola Transaksi Peminjaman</h3>
        <p class="text-muted small mb-0">Verifikasi permohonan pinjam buku, cek tenggat jatuh tempo, denda, dan konfirmasi pengembalian.</p>
    </div>
</div>

<!-- Status Tabs & Counters -->
<div class="d-flex align-items-center gap-2 overflow-x-auto pb-3 mb-3">
    <a href="{{ route('admin.borrowings.index', ['search' => $search]) }}" 
       class="btn btn-sm rounded-pill px-3 py-2 text-nowrap {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
        Semua ({{ $counts['total'] }})
    </a>
    <a href="{{ route('admin.borrowings.index', ['status' => 'menunggu', 'search' => $search]) }}" 
       class="btn btn-sm rounded-pill px-3 py-2 text-nowrap {{ request('status') == 'menunggu' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">
        <i class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan ({{ $counts['menunggu'] }})
    </a>
    <a href="{{ route('admin.borrowings.index', ['status' => 'dipinjam', 'search' => $search]) }}" 
       class="btn btn-sm rounded-pill px-3 py-2 text-nowrap {{ request('status') == 'dipinjam' ? 'btn-info text-dark fw-bold' : 'btn-outline-secondary' }}">
        <i class="bi bi-book me-1"></i> Sedang Dipinjam ({{ $counts['dipinjam'] }})
    </a>
    <a href="{{ route('admin.borrowings.index', ['status' => 'dikembalikan', 'search' => $search]) }}" 
       class="btn btn-sm rounded-pill px-3 py-2 text-nowrap {{ request('status') == 'dikembalikan' ? 'btn-success text-white fw-bold' : 'btn-outline-secondary' }}">
        <i class="bi bi-check2-circle me-1"></i> Telah Dikembalikan ({{ $counts['dikembalikan'] }})
    </a>
    <a href="{{ route('admin.borrowings.index', ['status' => 'ditolak', 'search' => $search]) }}" 
       class="btn btn-sm rounded-pill px-3 py-2 text-nowrap {{ request('status') == 'ditolak' ? 'btn-danger text-white fw-bold' : 'btn-outline-secondary' }}">
        <i class="bi bi-x-circle me-1"></i> Ditolak ({{ $counts['ditolak'] }})
    </a>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
    <form action="{{ route('admin.borrowings.index') }}" method="GET" class="row g-2 align-items-center">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control border-start-0" 
                       placeholder="Cari kode transaksi, nama anggota peminjam, atau judul buku...">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Cari Data</button>
            @if($search)
                <a href="{{ route('admin.borrowings.index', ['status' => request('status')]) }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th class="ps-3">Kode Pinjam</th>
                    <th>Peminjam (Anggota)</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam & Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Denda / Catatan</th>
                    <th class="text-end pe-3" style="min-width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                    @php
                        $isOverdue = $b->isOverdue();
                        $fine = $b->calculateOverdueFine();
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="fw-bold font-monospace small text-dark">{{ $b->borrow_code }}</span>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $b->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $b->user->name }}</div>
                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $b->user->email }}</small>
                            @if($b->user->phone)
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-telephone me-1"></i>{{ $b->user->phone }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate small" style="max-width: 200px;" title="{{ $b->book->title }}">
                                {{ $b->book->title }}
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $b->book->category->name }}</span>
                        </td>
                        <td>
                            <div class="small">Pinjam: <strong>{{ $b->borrow_date->format('d/m/Y') }}</strong></div>
                            <div class="small {{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                Tempo: {{ $b->due_date->format('d/m/Y') }}
                            </div>
                            @if($isOverdue)
                                <span class="badge badge-terlambat" style="font-size: 0.7rem;">Terlambat</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status == 'menunggu')
                                <span class="badge badge-status badge-menunggu">Menunggu</span>
                            @elseif($b->status == 'dipinjam')
                                <span class="badge badge-status badge-dipinjam">Sedang Dipinjam</span>
                            @elseif($b->status == 'dikembalikan')
                                <span class="badge badge-status badge-dikembalikan">Dikembalikan</span>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    Tgl: {{ $b->return_date ? $b->return_date->format('d/m/Y') : '-' }}
                                </div>
                            @elseif($b->status == 'ditolak')
                                <span class="badge badge-status badge-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status == 'dipinjam' && $isOverdue)
                                <div class="text-danger fw-bold small">Denda: Rp {{ number_format($fine, 0, ',', '.') }}</div>
                            @elseif($b->status == 'dikembalikan' && $b->fine_amount > 0)
                                <div class="text-danger small">Denda: Rp {{ number_format($b->fine_amount, 0, ',', '.') }}</div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif

                            @if($b->notes)
                                <div class="small text-muted" style="font-size: 0.75rem;" title="{{ $b->notes }}">
                                    <em>Ket: {{ Str::limit($b->notes, 25) }}</em>
                                </div>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            @if($b->status == 'menunggu')
                                <div class="d-flex justify-content-end gap-1">
                                    <form action="{{ route('admin.borrowings.approve', $b) }}" method="POST" onsubmit="return confirm('Setujui peminjaman buku ini? Stok buku akan berkurang 1.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success px-2 py-1 rounded" title="Setujui Peminjaman">
                                            <i class="bi bi-check-lg me-1"></i> Setujui
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 rounded" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal"
                                            data-action="{{ route('admin.borrowings.reject', $b) }}"
                                            data-code="{{ $b->borrow_code }}"
                                            title="Tolak Peminjaman">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            @elseif($b->status == 'dipinjam')
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#returnModal"
                                        data-action="{{ route('admin.borrowings.returnConfirm', $b) }}"
                                        data-code="{{ $b->borrow_code }}"
                                        data-book="{{ $b->book->title }}"
                                        data-user="{{ $b->user->name }}"
                                        data-fine="{{ $fine }}">
                                    <i class="bi bi-arrow-return-left me-1"></i> Kembalikan
                                </button>
                            @else
                                <span class="badge bg-light text-muted border">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                            Tidak ada transaksi peminjaman yang sesuai kriteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $borrowings->links('pagination::bootstrap-5') }}
</div>

<!-- Modal Tolak Peminjaman -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-1"></i> Tolak Permohonan Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small mb-3">Tolak peminjaman dengan kode <strong id="rejectCode"></strong>?</p>
                    <div class="mb-3">
                        <label for="admin_notes_reject" class="form-label small fw-semibold">Alasan Penolakan</label>
                        <textarea class="form-control" id="admin_notes_reject" name="admin_notes" rows="3" 
                                  placeholder="Contoh: Stok sedang tidak tersedia fisik atau anggota masih memiliki tanggungan buku."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill">Tolak Peminjaman</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian Buku -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="returnForm" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-check2-circle me-1"></i> Konfirmasi Pengembalian Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-light p-3 rounded-3 mb-3 small">
                        <div>Kode Transaksi: <strong id="returnCode"></strong></div>
                        <div>Peminjam: <strong id="returnUser"></strong></div>
                        <div>Judul Buku: <strong id="returnBook"></strong></div>
                    </div>

                    <div class="mb-3">
                        <label for="fine_amount" class="form-label small fw-semibold">Jumlah Denda Keterlambatan (Rp)</label>
                        <input type="number" class="form-control" id="fine_amount" name="fine_amount" min="0" value="0">
                        <div class="form-text small">Dihitung otomatis: Rp 1.000 / hari jika melewati batas tanggal jatuh tempo.</div>
                    </div>

                    <div class="mb-3">
                        <label for="admin_notes_return" class="form-label small fw-semibold">Catatan Kondisi Buku (Opsional)</label>
                        <textarea class="form-control" id="admin_notes_return" name="admin_notes" rows="2" 
                                  placeholder="Contoh: Buku kembali dalam kondisi baik & lengkap."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill">Konfirmasi Buku Diterima</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Setup Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const code = button.getAttribute('data-code');

            document.getElementById('rejectForm').action = action;
            document.getElementById('rejectCode').textContent = code;
        });
    }

    // Setup Return Modal
    const returnModal = document.getElementById('returnModal');
    if (returnModal) {
        returnModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const code = button.getAttribute('data-code');
            const book = button.getAttribute('data-book');
            const user = button.getAttribute('data-user');
            const fine = button.getAttribute('data-fine') || 0;

            document.getElementById('returnForm').action = action;
            document.getElementById('returnCode').textContent = code;
            document.getElementById('returnBook').textContent = book;
            document.getElementById('returnUser').textContent = user;
            document.getElementById('fine_amount').value = fine;
        });
    }
</script>
@endsection
