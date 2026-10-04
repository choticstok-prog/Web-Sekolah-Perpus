@extends('layouts.app')

@section('title', 'Tambah Buku Baru - PustakaSekolah')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Buku Baru</h3>
                <p class="text-muted small mb-0">Isi formulir berikut untuk mendaftarkan buku baru ke dalam katalog perpustakaan.</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Judul Buku -->
                    <div class="col-md-8">
                        <label for="title" class="form-label small fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Belajar Pemrograman Web Laravel">
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-4">
                        <label for="category_id" class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengarang -->
                    <div class="col-md-6">
                        <label for="author" class="form-label small fw-semibold">Nama Pengarang / Penulis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" 
                               id="author" name="author" value="{{ old('author') }}" required placeholder="Contoh: John Doe">
                        @error('author')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penerbit -->
                    <div class="col-md-4">
                        <label for="publisher" class="form-label small fw-semibold">Nama Penerbit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('publisher') is-invalid @enderror" 
                               id="publisher" name="publisher" value="{{ old('publisher') }}" required placeholder="Contoh: Kemendikbudristek">
                        @error('publisher')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kota Terbit -->
                    <div class="col-md-4">
                        <label for="city" class="form-label small fw-semibold">Kota Terbit</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               id="city" name="city" value="{{ old('city', 'Jakarta') }}" placeholder="Contoh: Jakarta">
                        @error('city')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun Terbit -->
                    <div class="col-md-4">
                        <label for="year" class="form-label small fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" 
                               id="year" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        @error('year')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ISBN -->
                    <div class="col-md-6">
                        <label for="isbn" class="form-label small fw-semibold">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                               id="isbn" name="isbn" value="{{ old('isbn') }}" placeholder="Contoh: 978-602-244-361-2">
                        @error('isbn')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stok Buku -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label small fw-semibold">Jumlah Stok (Eksemplar) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" value="{{ old('stock', 1) }}" min="1" required>
                        @error('stock')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gambar Sampul / Cover -->
                    <div class="col-md-6">
                        <label for="cover_url" class="form-label small fw-semibold">URL Cover Buku (Online / Lokal)</label>
                        <input type="text" class="form-control @error('cover_url') is-invalid @enderror" 
                               id="cover_url" name="cover_url" value="{{ old('cover_url') }}" placeholder="/images/covers/sejarah-kelas-10-siswa.png atau URL online">
                        <div class="form-text small">Masukkan path cover lokal atau link gambar dari internet.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="cover_file" class="form-label small fw-semibold">Atau Upload Gambar Cover (File)</label>
                        <input type="file" class="form-control @error('cover_file') is-invalid @enderror" 
                               id="cover_file" name="cover_file" accept="image/*">
                        <div class="form-text small">Format JPG, PNG, atau WEBP (Maksimal 2MB).</div>
                    </div>

                    <!-- Link Sumber Rujukan / Ringkasan Materi -->
                    <div class="col-md-6">
                        <label for="source_url" class="form-label small fw-semibold">Link Sumber Rujukan (SIBI / Kemdikbud)</label>
                        <input type="url" class="form-control @error('source_url') is-invalid @enderror" 
                               id="source_url" name="source_url" value="{{ old('source_url') }}" 
                               placeholder="Contoh: https://buku.kemendikdasmen.go.id/katalog/sejarah-untuk-smama-kelas-x">
                        <div class="form-text small">Tautan informasi rujukan buku resmi.</div>
                        @error('source_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link PDF Buku (Fitur Baca Online) -->
                    <div class="col-md-6">
                        <label for="pdf_url" class="form-label small fw-semibold">
                            <i class="bi bi-file-earmark-pdf text-danger"></i> Link File PDF Buku (Fitur Baca Online)
                        </label>
                        <input type="url" class="form-control @error('pdf_url') is-invalid @enderror" 
                               id="pdf_url" name="pdf_url" value="{{ old('pdf_url') }}" 
                               placeholder="Contoh: https://static-sc.cloudapp.web.id/content/pdf/bukuteks/sejarah_x.pdf">
                        <div class="form-text small">URL dokumen PDF untuk mengaktifkan fitur baca online (E-Reader).</div>
                        @error('pdf_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Buku -->
                    <div class="col-12">
                        <label for="description" class="form-label small fw-semibold">Deskripsi / Sinopsis Buku</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" 
                                  placeholder="Tuliskan sinopsis buku dan ringkasan pokok bahasan materi per bab...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
