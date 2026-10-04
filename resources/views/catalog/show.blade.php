@extends('layouts.app')

@section('title', $book->title . ' - Perpustakaan Sekolah SMA')

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Katalog Perpustakaan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['kategori' => $book->category->slug]) }}" class="text-decoration-none">{{ $book->category->name }}</a></li>
        <li class="breadcrumb-item active text-truncate" style="max-width: 300px;">{{ $book->title }}</li>
    </ol>
</nav>

@if($book->pdf_url)
    <!-- Call-To-Action Banner: Fitur Baca Online -->
    <div class="card border-0 mb-4 text-white p-3 p-md-4 shadow-sm animate-fade-in-up" 
         style="background: linear-gradient(135deg, #065f46 0%, #059669 50%, #10b981 100%); border-radius: 16px;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-white bg-opacity-20 rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 58px; height: 58px; min-width: 58px;">
                    <i class="bi bi-book-half fs-2"></i>
                </div>
                <div>
                    <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-1 shadow-sm">
                        <span class="live-dot me-1"></span> Fitur Baca Online (E-Reader) Tersedia
                    </span>
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill mb-1 shadow-sm">
                        <i class="bi bi-cpu me-1"></i> Ramah Device Kentang
                    </span>
                    <h5 class="fw-bold mb-1 text-white">Baca Buku Teks Ini Online Sekarang (Gratis & Full PDF)</h5>
                    <p class="mb-0 text-white-50 small">Dokumen resmi terverifikasi Kemdikbudristek. Dilengkapi <strong>Mode Teks Ringan (0 MB & 0 Lag)</strong> untuk HP spek kentang, serta opsi pembaca PDF lengkap.</p>
                </div>
            </div>
            <a href="{{ route('catalog.read', $book) }}" class="btn btn-light text-success fw-bold px-4 py-2 rounded-pill shadow d-inline-flex align-items-center justify-content-center gap-2 text-nowrap">
                <i class="bi bi-eye-fill"></i>
                <span>Buka E-Reader Sekarang</span>
            </a>
        </div>
    </div>
@endif

<div class="row g-4 mb-5">
    <!-- Left Column: Book Cover -->
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm overflow-hidden p-2 text-center sticky-top" style="top: 90px;">
            <div style="height: 340px; background-color: #f1f5f9; border-radius: 10px; overflow: hidden;" class="position-relative">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $book->title }}">
                @else
                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="bi bi-book fs-1 mb-2"></i>
                        <span class="small fw-semibold px-2">{{ $book->title }}</span>
                    </div>
                @endif

                @if($book->pdf_url)
                    <span class="position-absolute bottom-0 start-0 m-2 badge bg-success bg-opacity-95 text-white rounded-pill px-2 py-1 small shadow-sm d-flex align-items-center gap-1">
                        <span class="live-dot" style="width: 6px; height: 6px; background-color: #ffffff;"></span> E-Reader Aktif
                    </span>
                @endif
            </div>

            <div class="mt-3 text-center">
                @if($book->pdf_url)
                    <a href="{{ route('catalog.read', $book) }}" class="btn btn-reader w-100 rounded-pill py-2 mb-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-book-half"></i>
                        <span>Baca Buku Ini Online</span>
                    </a>
                @endif

                @if($book->isAvailable())
                    <div class="badge bg-success-subtle text-success fs-6 py-2 px-3 rounded-pill w-100 mb-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Tersedia ({{ $book->available_stock }} dari {{ $book->stock }})
                    </div>
                @else
                    <div class="badge bg-danger-subtle text-danger fs-6 py-2 px-3 rounded-pill w-100 mb-2">
                        <i class="bi bi-x-circle-fill me-1"></i> Stok Sedang Habis (0/{{ $book->stock }})
                    </div>
                @endif
                <small class="text-muted">Total Stok Fisik: {{ $book->stock }} Eksemplar</small>
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
                    <div class="fw-semibold {{ $book->isAvailable() ? 'text-success' : 'text-danger' }}">
                        {{ $book->isAvailable() ? 'Siap Dipinjam Siswa' : 'Tidak Tersedia' }}
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
                    <div class="mt-3 p-3 bg-primary-subtle rounded-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 border border-primary-subtle">
                        <div class="small text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-primary fs-4"></i>
                            <div>
                                <div class="fw-bold">Sumber Resmi SIBI Kemendikbudristek</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Portal resmi Sistem Informasi Perbukuan Indonesia (Buku Teks Terverifikasi)</div>
                            </div>
                        </div>
                        <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-1 text-nowrap shadow-sm">
                            <span>Buka Buku di SIBI</span> <i class="bi bi-box-arrow-up-right small ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Borrowing Form / Action Card -->
    <div class="col-lg-4">
        @if($book->pdf_url)
            <div class="card border-0 shadow-sm p-3 mb-3 bg-success-subtle border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-success text-white rounded-pill px-2 py-1 small">
                        <span class="live-dot me-1" style="width: 6px; height: 6px; background-color: #ffffff;"></span> E-Reader Siap
                    </span>
                    <span class="text-success fw-bold small">100% Gratis</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">Baca Online Mandiri</h6>
                <p class="text-secondary small mb-3" style="font-size: 0.78rem;">
                    Buku ini dapat langsung dibaca secara utuh di peramban tanpa antri pinjam buku fisik.
                </p>
                <a href="{{ route('catalog.read', $book) }}" class="btn btn-sm btn-reader w-100 py-2 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-book-half"></i>
                    <span>Mulai Baca Online Sekarang</span>
                </a>
            </div>
        @endif

        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-bookmark-plus text-primary"></i> Pinjam Buku Fisik
            </h5>

            @guest
                <div class="alert alert-warning border-0 small rounded-3 mb-3">
                    <i class="bi bi-info-circle me-1"></i> Anda harus memiliki akun anggota untuk dapat mengajukan peminjaman buku perpustakaan.
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary w-100 py-2 mb-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 py-2">
                    <i class="bi bi-person-plus me-1"></i> Daftar Anggota Baru
                </a>
            @else
                @if(auth()->user()->isAdmin())
                    <div class="alert alert-info border-0 small rounded-3 mb-3">
                        <i class="bi bi-shield-lock me-1"></i> Anda login sebagai <strong>Administrator</strong>. Anda dapat mengedit buku ini atau melihat peminjamannya.
                    </div>
                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning text-dark w-100 fw-bold py-2 mb-2">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Buku Ini
                    </a>
                    <a href="{{ route('admin.borrowings.index', ['search' => $book->title]) }}" class="btn btn-outline-primary w-100 py-2">
                        <i class="bi bi-arrow-left-right me-1"></i> Riwayat Peminjaman Buku Ini
                    </a>
                @else
                    @if($book->isAvailable())
                        <form action="{{ route('borrowings.store', $book) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="borrow_date" class="form-label small fw-semibold">Tanggal Mulai Pinjam</label>
                                <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" 
                                       id="borrow_date" name="borrow_date" 
                                       value="{{ old('borrow_date', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('borrow_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="duration_days" class="form-label small fw-semibold">Durasi Peminjaman</label>
                                <select class="form-select @error('duration_days') is-invalid @enderror" 
                                        id="duration_days" name="duration_days" required onchange="updateDueDate()">
                                    <option value="3" {{ old('duration_days') == '3' ? 'selected' : '' }}>3 Hari</option>
                                    <option value="7" {{ old('duration_days', '7') == '7' ? 'selected' : '' }}>7 Hari (Standar)</option>
                                    <option value="14" {{ old('duration_days') == '14' ? 'selected' : '' }}>14 Hari (2 Minggu)</option>
                                </select>
                                @error('duration_days')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small mt-1 text-primary" id="dueDateInfo">
                                    <i class="bi bi-calendar-event me-1"></i> Wajib kembali paling lambat: <span id="dueDateText">-</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label small fw-semibold">Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" 
                                          placeholder="Tujuan pinjam atau pesan untuk pustakawan...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="bg-light p-3 rounded-3 small mb-3">
                                <div class="fw-semibold mb-1 text-dark"><i class="bi bi-shield-exclamation text-warning me-1"></i> Ketentuan Peminjaman Siswa:</div>
                                <ul class="mb-0 ps-3 text-muted" style="font-size: 0.8rem;">
                                    <li>Buku wajib dijaga dalam kondisi rapi, bersih, dan tidak dicoret.</li>
                                    <li>Tunjukkan kode peminjaman kepada Petugas Perpustakaan Sekolah.</li>
                                    <li>Keterlambatan pengembalian buku dikenakan denda Rp 1.000 / hari.</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Ajukan Peminjaman Sekarang
                            </button>
                        </form>
                    @else
                        <div class="alert alert-danger border-0 small rounded-3 mb-3">
                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Stok buku saat ini sedang habis dipinjam oleh anggota lain. Silakan periksa kembali nanti.
                        </div>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 py-2">
                            <i class="bi bi-arrow-left me-1"></i> Cari Buku Lainnya
                        </a>
                    @endif
                @endif
            @endguest
        </div>
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
                        <div style="height: 150px; background-color: #e2e8f0;">
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
                                <span class="badge {{ $rel->isAvailable() ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-muted' }} rounded-pill" style="font-size: 0.7rem;">
                                    {{ $rel->isAvailable() ? 'Stok: '.$rel->available_stock : 'Digital Only' }}
                                </span>
                                <div class="d-flex gap-1">
                                    @if($rel->pdf_url)
                                        <a href="{{ route('catalog.read', $rel) }}" class="btn btn-sm btn-success py-0 px-2 rounded-pill d-flex align-items-center gap-1" style="font-size: 0.75rem;" title="Baca Online">
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

@section('scripts')
<script>
    function updateDueDate() {
        const borrowDateInput = document.getElementById('borrow_date');
        const durationSelect = document.getElementById('duration_days');
        const dueDateText = document.getElementById('dueDateText');

        if (!borrowDateInput || !durationSelect || !dueDateText) return;

        const borrowDateVal = borrowDateInput.value;
        const duration = parseInt(durationSelect.value, 10);

        if (borrowDateVal && !isNaN(duration)) {
            const date = new Date(borrowDateVal);
            date.setDate(date.getDate() + duration);
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dueDateText.textContent = date.toLocaleDateString('id-ID', options);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const borrowDateInput = document.getElementById('borrow_date');
        if (borrowDateInput) {
            borrowDateInput.addEventListener('change', updateDueDate);
            updateDueDate();
        }
    });
</script>
@endsection
