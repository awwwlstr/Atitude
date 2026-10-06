@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $user->name . ' - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle border border-3 border-primary-subtle me-3" width="72" height="72" style="object-fit: cover;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold mb-0 text-dark">{{ $user->name }}</h4>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status === 'active' ? 'Akun Aktif' : 'Akun Nonaktif' }}
                            </span>
                        </div>
                        <div class="small text-muted">{{ $user->email }} • <code>{{ '@' . $user->username }}</code></div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" onsubmit="return confirm('Ubah status keaktifan user?')">
                        @csrf
                        <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'outline-danger' : 'success' }} rounded-pill px-3 fw-semibold">
                            <i class="bi {{ $user->status === 'active' ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>
                            {{ $user->status === 'active' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Materi Diselesaikan</div>
                    <h3 class="fw-bold text-success mb-0">{{ $completedCount }} / {{ $totalPublishedMaterials }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Rata-rata Nilai Latihan</div>
                    <h3 class="fw-bold text-primary mb-0">{{ round($avgScore, 1) }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Total Interaksi Modul</div>
                    <h3 class="fw-bold text-info mb-0">{{ $progresses->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Learning Progress History -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-check text-primary me-2"></i>Riwayat Modul & Nilai Siswa</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Materi Karakter</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Progres</th>
                            <th>Nilai</th>
                            <th>Percobaan</th>
                            <th class="text-end pe-4">Selesai Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progresses as $prog)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $prog->material->title }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $prog->material->category->color }}">
                                        {{ $prog->material->category->name }}
                                    </span>
                                </td>
                                <td>
                                    @if($prog->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($prog->status === 'in_progress')
                                        <span class="badge bg-warning text-dark">Sedang Berjalan</span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                <td>{{ $prog->progress_percentage }}%</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold">
                                        {{ $prog->score ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $prog->attempts_count }}x</td>
                                <td class="text-end pe-4 small text-muted">
                                    {{ $prog->completed_at ? $prog->completed_at->format('d M Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Siswa belum memiliki aktivitas pembelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
