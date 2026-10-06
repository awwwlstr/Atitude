@extends('layouts.app')

@section('title', 'Manajemen Seluruh Materi - E-Attitude')

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
                <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-journal-album text-primary me-2"></i>Manajemen Seluruh Materi</h3>
                <p class="text-muted small mb-0">Pantau seluruh materi, status publikasi, dan lakukan kurasi materi karakter.</p>
            </div>
            <a href="{{ route('admin.materi.pending') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-patch-question me-1"></i> Materi Menunggu Review
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="card border-0 shadow-sm p-3 rounded-4 mb-4 bg-white">
            <form action="{{ route('admin.materi.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-light" placeholder="Cari judul materi..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select bg-light">
                        <option value="">Semua Kategori Attitude</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select bg-light">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revisi</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">Filter</button>
                    @if(request()->hasAny(['q', 'kategori', 'status']))
                        <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary rounded-pill px-2">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Materials Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            @if($materials->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1"></i>
                    <h6 class="fw-bold mt-2">Tidak ada data materi ditemukan</h6>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Materi Pembelajaran</th>
                                <th>Kategori</th>
                                <th>Pembuat</th>
                                <th>Status</th>
                                <th>Konten / Soal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $mat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $mat->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($mat->description, 55) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mat->category->color }}">
                                            {{ $mat->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $mat->creator->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $mat->status_badge_class }}">
                                            {{ $mat->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mat->contents->count() }} media</span>
                                        <span class="badge bg-light text-dark border">{{ $mat->questions->count() }} soal</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.materi.show', $mat->id) }}" class="btn btn-outline-primary" title="Review & Detail">
                                                <i class="bi bi-patch-check"></i> Review
                                            </a>
                                            <form action="{{ route('admin.materi.destroy', $mat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $materials->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
