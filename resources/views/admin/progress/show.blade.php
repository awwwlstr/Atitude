@extends('layouts.app')

@section('title', 'Detail Progres Siswa: ' . $user->name . ' - E-Attitude')

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
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3 border border-3 border-primary-subtle" width="64" height="64" style="object-fit: cover;">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
                        <div class="small text-muted">{{ $user->email }} • <code>{{ '@' . $user->username }}</code></div>
                    </div>
                </div>
                <a href="{{ route('admin.progres.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Monitoring
                </a>
            </div>
        </div>

        <!-- Summary Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Penyelesaian Modul</div>
                    <h3 class="fw-bold text-success mb-0">{{ $completedCount }} / {{ $totalPublishedMaterials }} ({{ $overallPercentage }}%)</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Rata-rata Skor Evaluasi</div>
                    <h3 class="fw-bold text-primary mb-0">{{ round($avgScore, 1) }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                    <div class="small text-muted fw-bold">Modul yang Sedang Berjalan</div>
                    <h3 class="fw-bold text-warning mb-0">{{ $user->progress->where('status', 'in_progress')->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Progress List Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-check text-primary me-2"></i>Daftar Progres Modul Sikap</h5>
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
                            <th>Waktu Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->progress as $prog)
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
                                        <span class="badge bg-warning text-dark">Berjalan</span>
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
                                <td class="small text-muted">
                                    {{ $prog->completed_at ? $prog->completed_at->format('d M Y H:i') : ($prog->started_at ? $prog->started_at->format('d M Y H:i') : '-') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada progres pengerjaan modul.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
