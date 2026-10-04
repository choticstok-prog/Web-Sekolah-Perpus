@extends('layouts.app')

@section('title', 'Kelola Data Buku Sejarah - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-journal-bookmark text-success me-2"></i>Kelola Koleksi Buku</h3>
        <p class="text-muted small mb-0">Manajemen inventaris buku perpustakaan, stok eksemplar, dan informasi penerbitan.</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="card border-0 shadow-sm p-3 mb-4 bg-white">
    <form action="{{ route('admin.books.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control border-start-0" 
                       placeholder="Cari judul, pengarang, penerbit, ISBN...">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ ($categoryId ?? '') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
            @if($search || $categoryId)
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Books Table -->
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width: 70px;">Cover</th>
                    <th>Judul & Pengarang</th>
                    <th>Kategori</th>
                    <th>Penerbit & Tahun</th>
                    <th>ISBN</th>
                    <th>Stok (Sisa/Total)</th>
                    <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td class="ps-3">
                            <div style="width: 50px; height: 70px; background: #e2e8f0; border-radius: 6px; overflow: hidden;" class="shadow-sm">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $book->title }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-book"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $book->title }}">
                                <a href="{{ route('catalog.show', $book) }}" class="text-decoration-none text-dark" target="_blank">
                                    {{ $book->title }} <i class="bi bi-box-arrow-up-right small text-muted"></i>
                                </a>
                            </div>
                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $book->author }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $book->category->name }}</span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $book->publisher }}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $book->city ?? 'Jakarta' }}, {{ $book->year }}</small>
                            @if($book->source_url)
                                <div class="mt-1">
                                    <a href="{{ $book->source_url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-primary fw-semibold" style="font-size: 0.75rem;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Sumber Materi
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            <code class="small text-secondary">{{ $book->isbn ?? '-' }}</code>
                        </td>
                        <td>
                            <div>
                                <span class="badge {{ $book->available_stock > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-2 py-1">
                                    Tersedia: {{ $book->available_stock }}
                                </span>
                            </div>
                            <small class="text-muted">Total: {{ $book->stock }} eks</small>
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-warning text-dark me-1" title="Edit Buku">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus buku {{ $book->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Buku">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-book fs-1 d-block mb-2 text-muted"></i>
                            Tidak ada data buku yang sesuai dengan filter atau pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection
