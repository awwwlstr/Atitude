@extends('layouts.app')

@section('title', 'Dashboard Siswa - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Welcome Header Banner -->
    <div class="card border-0 bg-primary text-white p-4 p-md-5 rounded-4 mb-4 shadow">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2 shadow-sm">
                    <i class="bi bi-mortarboard-fill me-1"></i> Dashboard Siswa
                </span>
                <h2 class="display-6 fw-bold mb-1">Halo, {{ $user->name }}! 👋</h2>
                <p class="lead text-white-50 mb-0">Selamat datang di portal pembelajaran karakter. Tingkatkan nilai sikap dan pantau kemajuan belajarmu di sini.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('user.materi.index') }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-4 shadow">
                    <i class="bi bi-play-fill me-1"></i> Mulai Belajar Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Progress Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Progres Pembelajaran Keseluruhan</h5>
                    <span class="fs-5 fw-extrabold text-primary">{{ $overallPercentage }}%</span>
                </div>
                <div class="progress mb-2" style="height: 16px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $overallPercentage }}%" aria-valuenow="{{ $overallPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="small text-muted">
                    <strong>{{ $completedCount }}</strong> dari <strong>{{ $totalPublishedMaterials }}</strong> materi telah selesai Anda pelajari.
                </div>
            </div>
            <div class="col-md-6">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h4 class="fw-bold text-primary mb-0">{{ $totalPublishedMaterials }}</h4>
                            <div class="small text-muted">Total Materi</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h4 class="fw-bold text-success mb-0">{{ $completedCount }}</h4>
                            <div class="small text-muted">Selesai</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3">
                            <h4 class="fw-bold text-warning mb-0">{{ $averageScore }}</h4>
                            <div class="small text-muted">Rata-rata Skor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Learning / In-Progress Materials -->
    @if($inProgressMaterials->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-warning me-2"></i>Materi yang Sedang Dipelajari</h4>
                <a href="{{ route('user.materi.index', ['status' => 'in_progress']) }}" class="small text-decoration-none">Lihat Semua</a>
            </div>

            <div class="row g-3">
                @foreach($inProgressMaterials as $material)
                    @php
                        $matProg = $material->progress->first();
                    @endphp
                    <div class="col-md-4">
                        <div class="card card-hover border-0 shadow-sm h-100 p-3">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-{{ $material->category->color }} me-2">
                                    {{ $material->category->name }}
                                </span>
                                <span class="badge bg-warning text-dark">Sedang Berjalan</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">{{ $material->title }}</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Progres</span>
                                    <span>{{ $matProg->progress_percentage ?? 40 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $matProg->progress_percentage ?? 40 }}%"></div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('user.materi.learn', $material->id) }}" class="btn btn-primary w-100 rounded-pill btn-sm fw-semibold">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Lanjutkan Belajar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recommended Materials -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-stars text-primary me-2"></i>Rekomendasi Materi Karakter</h4>
            <a href="{{ route('user.materi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Semua Materi</a>
        </div>

        @if($recommendedMaterials->isEmpty())
            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="bi bi-award-fill text-warning fs-1 mb-2"></i>
                <h5 class="fw-bold">Luar Biasa!</h5>
                <p class="text-muted mb-0">Anda telah menyelesaikan seluruh materi pembelajaran karakter yang tersedia saat ini.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($recommendedMaterials as $material)
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-hover border-0 shadow-sm h-100 overflow-hidden d-flex flex-column">
                            <div class="position-relative" style="height: 140px;">
                                <img src="{{ $material->cover_image_url }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $material->title }}">
                                <span class="position-absolute top-0 start-0 m-2 badge bg-{{ $material->category->color }}">
                                    {{ $material->category->name }}
                                </span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $material->title }}</h6>
                                <p class="small text-muted mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $material->description }}
                                </p>
                                <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-auto fw-semibold">
                                    <i class="bi bi-play-circle me-1"></i> Mulai Belajar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Completed Materials -->
    @if($recentCompleted->isNotEmpty())
        <div class="mb-4">
            <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-check2-all text-success me-2"></i>Materi yang Telah Selesai</h4>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Materi Karakter</th>
                                <th>Kategori</th>
                                <th>Nilai Evaluasi</th>
                                <th>Percobaan</th>
                                <th>Waktu Selesai</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCompleted as $prog)
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
                                        <span class="badge bg-success-subtle text-success fs-6 fw-bold px-3 py-1">
                                            {{ $prog->score ?? 0 }} / 100
                                        </span>
                                    </td>
                                    <td>{{ $prog->attempts_count }}x</td>
                                    <td class="small text-muted">{{ $prog->completed_at ? $prog->completed_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('user.materi.show', $prog->material->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            Buka Kembali
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
