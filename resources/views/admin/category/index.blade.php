@extends('layouts.app')

@section('title', 'Manajemen Kategori Attitude - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-tags-fill text-primary me-2"></i>Kategori Nilai Sikap (Attitude)</h3>
                <p class="text-muted small mb-0">Kelola 8 pilar nilai karakter utama atau tambahkan dimensi sikap baru.</p>
            </div>
            <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle me-1"></i> + Tambah Kategori
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kategori Sikap</th>
                            <th>Ikon & Warna</th>
                            <th>Deskripsi Nilai</th>
                            <th>Jumlah Materi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded-circle bg-{{ $category->color }} bg-opacity-10 text-{{ $category->color }} me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi {{ $category->icon }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $category->name }}</div>
                                            <code class="small text-muted">{{ $category->slug }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->color }}">{{ $category->color }}</span>
                                </td>
                                <td>
                                    <div class="small text-muted" style="max-width: 320px;">
                                        {{ $category->description }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $category->materials_count }} modul</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCatModal_{{ $category->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($category->materials_count == 0)
                                            <form action="{{ route('admin.kategori.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editCatModal_{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold">Edit Kategori Sikap</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.kategori.update', $category->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                                        </div>
                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label fw-semibold">Bootstrap Icon</label>
                                                                <input type="text" name="icon" class="form-control" value="{{ $category->icon }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label fw-semibold">Warna Badge</label>
                                                                <select name="color" class="form-select">
                                                                    <option value="primary" {{ $category->color == 'primary' ? 'selected' : '' }}>Primary (Biru)</option>
                                                                    <option value="success" {{ $category->color == 'success' ? 'selected' : '' }}>Success (Hijau)</option>
                                                                    <option value="warning" {{ $category->color == 'warning' ? 'selected' : '' }}>Warning (Kuning)</option>
                                                                    <option value="danger" {{ $category->color == 'danger' ? 'selected' : '' }}>Danger (Merah)</option>
                                                                    <option value="info" {{ $category->color == 'info' ? 'selected' : '' }}>Info (Biru Muda)</option>
                                                                    <option value="indigo" {{ $category->color == 'indigo' ? 'selected' : '' }}>Indigo (Ungu Biru)</option>
                                                                    <option value="teal" {{ $category->color == 'teal' ? 'selected' : '' }}>Teal (Hijau Tosca)</option>
                                                                    <option value="purple" {{ $category->color == 'purple' ? 'selected' : '' }}>Purple (Ungu)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi Nilai</label>
                                                            <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addCategoryModalLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori Nilai Sikap</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori Sikap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kejujuran" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Bootstrap Icon</label>
                            <input type="text" name="icon" class="form-control" placeholder="bi-shield-check" value="bi-bookmark-star">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Warna Tema</label>
                            <select name="color" class="form-select">
                                <option value="primary">Primary (Biru)</option>
                                <option value="success">Success (Hijau)</option>
                                <option value="warning">Warning (Kuning)</option>
                                <option value="danger">Danger (Merah)</option>
                                <option value="info">Info (Biru Muda)</option>
                                <option value="indigo">Indigo (Ungu Biru)</option>
                                <option value="teal">Teal (Hijau Tosca)</option>
                                <option value="purple">Purple (Ungu)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Penjelasan Nilai Sikap</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan pengertian dan ruang lingkup nilai sikap ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
