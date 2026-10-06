@extends('layouts.app')

@section('title', 'Manajemen Pembuat Materi - E-Attitude')

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
                <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Manajemen Pembuat Materi</h3>
                <p class="text-muted small mb-0">Kelola akun pembuat materi/konten yang bertugas menyusun modul karakter.</p>
            </div>
            <a href="{{ route('admin.pembuat.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="bi bi-person-plus me-1"></i> + Buat Akun Pembuat
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="card border-0 shadow-sm p-3 rounded-4 mb-4 bg-white">
            <form action="{{ route('admin.pembuat.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-light" placeholder="Cari nama, email, username..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select bg-light">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">Filter</button>
                    @if(request()->hasAny(['q', 'status']))
                        <a href="{{ route('admin.pembuat.index') }}" class="btn btn-outline-secondary rounded-pill px-2">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Creators Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            @if($creators->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-person-x fs-1"></i>
                    <h6 class="fw-bold mt-2">Belum ada data pembuat materi</h6>
                    <a href="{{ route('admin.pembuat.create') }}" class="btn btn-primary rounded-pill btn-sm px-4 mt-2">+ Buat Akun Pertama</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Pembuat Materi</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Materi Dibuat</th>
                                <th>Terdaftar</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($creators as $creator)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $creator->name }}</div>
                                                <div class="small text-muted">{{ $creator->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $creator->username ?? '-' }}</code>
                                    </td>
                                    <td>
                                        @if($creator->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $creator->materials_count }} modul</span>
                                    </td>
                                    <td class="small text-muted">{{ $creator->created_at->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.pembuat.edit', $creator->id) }}" class="btn btn-outline-primary" title="Edit Akun">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.pembuat.toggle', $creator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah status keaktifan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $creator->status === 'active' ? 'danger' : 'success' }}" title="{{ $creator->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="bi {{ $creator->status === 'active' ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.pembuat.destroy', $creator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun ini secara permanen?')">
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
                    {{ $creators->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
