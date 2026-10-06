@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Pembelajaran - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3 d-print-none">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold mb-2">Evaluasi Pendidikan Karakter</span>
                    <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-bar-graph-fill text-primary me-2"></i>Laporan & Rekapitulasi Pembelajaran</h3>
                    <p class="text-muted small mb-0">Ringkasan performa pembelajaran nilai sikap & pencapaian evaluasi karakter peserta didik.</p>
                </div>
                <div class="d-print-none">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>

        <!-- System Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center">
                    <div class="text-muted small fw-bold">Total Siswa Aktif</div>
                    <h3 class="fw-extrabold text-primary mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center">
                    <div class="text-muted small fw-bold">Materi Terpublikasi</div>
                    <h3 class="fw-extrabold text-success mb-0">{{ $totalMaterials }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center">
                    <div class="text-muted small fw-bold">Modul Diselesaikan</div>
                    <h3 class="fw-extrabold text-warning mb-0">{{ $totalCompletions }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center">
                    <div class="text-muted small fw-bold">Rata-rata Skor Sistem</div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ round($avgSystemScore, 1) }}</h3>
                </div>
            </div>
        </div>

        <!-- Performance Per Category Breakdown -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Rekapitulasi Capaian per Dimensi Sikap</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Dimensi Nilai Karakter</th>
                            <th>Jumlah Modul</th>
                            <th>Total Penyelesaian Siswa</th>
                            <th>Rata-rata Skor Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi {{ $cat->icon }} text-{{ $cat->color }} fs-5 me-2"></i>
                                        <span class="fw-bold text-dark">{{ $cat->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $cat->published_materials_count }} modul</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $cat->completions_count }}x pengerjaan</span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold">
                                        {{ $cat->avg_score }} / 100
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Students -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i>Top Siswa Berprestasi Pembelajaran Karakter</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Peringkat</th>
                            <th>Nama Siswa</th>
                            <th>Email</th>
                            <th>Materi Selesai</th>
                            <th>Rata-rata Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topStudents as $rank => $st)
                            <tr>
                                <td class="ps-4">
                                    @if($rank === 0)
                                        <span class="badge bg-warning text-dark px-3 py-1 fw-bold">🥇 Juara 1</span>
                                    @elseif($rank === 1)
                                        <span class="badge bg-secondary px-3 py-1 fw-bold">🥈 Juara 2</span>
                                    @elseif($rank === 2)
                                        <span class="badge bg-danger-subtle text-danger px-3 py-1 fw-bold">🥉 Juara 3</span>
                                    @else
                                        <span class="badge bg-light text-dark border px-2 py-1">#{{ $rank + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $st->name }}</span>
                                </td>
                                <td class="small text-muted">{{ $st->email }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $st->completed_count }} materi</span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fw-bold fs-6">{{ $st->avg_score }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
