@extends('layouts.app')

@section('title', 'Progres Belajar Sikap & Karakter - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Header banner -->
    <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">Pencapaian Pembelajaran</span>
                <h2 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Statistik & Progres Karakter</h2>
                <p class="text-white-50 mb-0">Pantau perkembangan belajarmu di setiap dimensi nilai karakter sikap.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('user.hasil') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                    <i class="bi bi-award me-1"></i> Rincian Nilai Latihan
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Widgets -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold">Penyelesaian</span>
                    <i class="bi bi-pie-chart-fill fs-4 text-primary"></i>
                </div>
                <h2 class="fw-bold text-primary mb-1">{{ $overallPercentage }}%</h2>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: {{ $overallPercentage }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold">Materi Selesai</span>
                    <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                </div>
                <h2 class="fw-bold text-success mb-1">{{ $completedCount }} <span class="fs-6 text-muted">/ {{ $allPublishedCount }}</span></h2>
                <div class="small text-muted">{{ $unstartedCount }} materi belum dimulai</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold">Sedang Berjalan</span>
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <h2 class="fw-bold text-warning mb-1">{{ $inProgressCount }}</h2>
                <div class="small text-muted">Materi dalam pengerjaan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold">Rata-rata Nilai</span>
                    <i class="bi bi-star-fill fs-4 text-warning"></i>
                </div>
                <h2 class="fw-bold text-dark mb-1">{{ round($avgScore, 1) }}</h2>
                <div class="small text-muted">Dari seluruh latihan selesai</div>
            </div>
        </div>
    </div>

    <!-- Category Attitude Progress Breakdown -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-grid-fill text-primary me-2"></i>Progres per Dimensi Sikap / Attitude</h5>
        <div class="row g-3">
            @foreach($categories as $cat)
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark small"><i class="bi {{ $cat->icon }} text-{{ $cat->color }} me-1"></i> {{ $cat->name }}</span>
                            <span class="badge bg-{{ $cat->color }}">{{ $cat->percentage }}%</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-{{ $cat->color }}" style="width: {{ $cat->percentage }}%"></div>
                        </div>
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            {{ $cat->completed_count }} dari {{ $cat->published_materials_count }} modul selesai
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Progress History Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check me-2"></i>Riwayat Progres Belajar</h5>
        </div>
        @if($progresses->isEmpty())
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1"></i>
                <p class="mt-2 mb-0">Belum ada aktivitas belajar. Silakan buka menu Materi untuk mulai belajar.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Materi Karakter</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Progres</th>
                            <th>Nilai</th>
                            <th>Waktu Dimulai</th>
                            <th>Waktu Selesai</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progresses as $prog)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $prog->material->title }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $prog->material->category->color }}">
                                        {{ $prog->material->category->name }}
                                    </span>
                                </td>
                                <td>
                                    @if($prog->status === 'completed')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                                    @elseif($prog->status === 'in_progress')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Berjalan</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Dimulai</span>
                                    @endif
                                </td>
                                <td style="min-width: 120px;">
                                    <div class="d-flex align-items-center">
                                        <span class="small me-2 fw-semibold">{{ $prog->progress_percentage }}%</span>
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar {{ $prog->status === 'completed' ? 'bg-success' : 'bg-primary' }}" style="width: {{ $prog->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($prog->score !== null)
                                        <span class="fw-bold text-success">{{ $prog->score }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $prog->started_at ? $prog->started_at->format('d M Y H:i') : '-' }}</td>
                                <td class="small text-muted">{{ $prog->completed_at ? $prog->completed_at->format('d M Y H:i') : '-' }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('user.materi.show', $prog->material->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $progresses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
