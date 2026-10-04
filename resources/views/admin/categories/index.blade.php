@extends('layouts.app')

@section('title', 'Kelola Kategori Buku - PustakaSekolah')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-tags text-primary me-2"></i>Kelola Kategori Buku</h3>
        <p class="text-muted small mb-0">Kelompokkan koleksi buku berdasarkan bidang keilmuan dan genre.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
    </button>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width: 60px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi</th>
                    <th>Total Koleksi Buku</th>
                    <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $cat->name }}</div>
                        </td>
                        <td>
                            <code class="small text-secondary">{{ $cat->slug }}</code>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $cat->description ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold">
                                {{ $cat->books_count }} Buku
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCategoryModal"
                                    data-id="{{ $cat->id }}"
                                    data-name="{{ $cat->name }}"
                                    data-description="{{ $cat->description }}"
                                    data-action="{{ route('admin.categories.update', $cat) }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori {{ $cat->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" {{ $cat->books_count > 0 ? 'disabled title="Kategori masih memiliki buku"' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada kategori buku terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-1 text-primary"></i> Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Kecerdasan Buatan">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label small fw-semibold">Deskripsi Singkat</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Keterangan singkat tentang kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Simpan Kategori</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-1 text-warning"></i> Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label small fw-semibold">Deskripsi Singkat</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Perbarui Kategori</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const editModal = document.getElementById('editCategoryModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');

            const form = document.getElementById('editCategoryForm');
            form.action = action;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description || '';
        });
    }
</script>
@endsection
