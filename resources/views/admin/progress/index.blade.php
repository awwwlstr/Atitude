@extends('layouts.app')

@section('title', 'Monitoring Progres Siswa - E-Attitude')

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
                <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Monitoring Progres Pembelajaran Siswa</h3>
                <p class="text-muted small mb-0">Pantau perkembangan seluruh siswa dalam menyelesaikan modul dan nilai evaluasi sikap.</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan Lengkap
            </a>
        </div>

        <!-- Filter Search -->
        <div class="card border-0 shadow-sm p-3 rounded-4 mb-4 bg-white">
            <form action="{{ route('admin.progres.index') }}" method="GET" class="row g-3">
                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-light" placeholder="Cari nama atau email siswa..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">Cari</button>
                    @if(request()->filled('q'))
                        <a href="{{ route('admin.progres.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Progress Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">User / Siswa</th>
                            <th>Materi Selesai</th>
                            <th>Progres (%)</th>
                            <th>Rata-rata Nilai</th>
                            <th>Status Akun</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="rounded-circle me-3" width="38" height="38" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                                            <div class="small text-muted">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $u->completed_count }}</span> <span class="text-muted small">/ {{ $totalPublishedMaterials }}</span>
                                </td>
                                <td style="min-width: 150px;">
                                    <div class="d-flex align-items-center">
                                        <span class="small me-2 fw-bold text-dark">{{ $u->progress_percentage }}%</span>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $u->progress_percentage >= 80 ? 'bg-success' : ($u->progress_percentage >= 40 ? 'bg-primary' : 'bg-warning') }}" style="width: {{ $u->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($u->avg_score > 0)
                                        <span class="badge bg-success-subtle text-success fs-6 fw-bold">
                                            {{ $u->avg_score }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($u->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.progres.user', $u->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-search me-1"></i> Rincian
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada siswa ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
