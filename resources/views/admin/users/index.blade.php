@extends('layouts.app')

@section('title', 'Manajemen User (Siswa) - E-Attitude')

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
                <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Manajemen User (Siswa)</h3>
                <p class="text-muted small mb-0">Kelola akun siswa, aktifkan/nonaktifkan akses, dan pantau aktivitas belajar.</p>
            </div>
            <div>
                <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">Total: {{ $users->total() }} Siswa</span>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card border-0 shadow-sm p-3 rounded-4 mb-4 bg-white">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-light" placeholder="Cari nama, email, atau username siswa..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select bg-light">
                        <option value="">Semua Status Akun</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">Filter</button>
                    @if(request()->hasAny(['q', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-2">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            @if($users->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-people fs-1"></i>
                    <h6 class="fw-bold mt-2">Tidak ada data user yang sesuai</h6>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Pengguna / Siswa</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Materi Diikuti</th>
                                <th>Terdaftar</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $user->username ?? '-' }}</code>
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $user->progress_count }} materi</span>
                                    </td>
                                    <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-primary" title="Lihat Detail & Progres">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status akun user ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }}" title="{{ $user->status === 'active' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    <i class="bi {{ $user->status === 'active' ? 'bi-person-x' : 'bi-person-check' }}"></i>
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
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
