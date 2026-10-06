@extends('layouts.app')

@section('title', 'Dashboard Administrator - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Header Banner -->
        <div class="card border-0 bg-dark text-white p-4 rounded-4 mb-4 shadow">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold mb-2">Panel Pusat Administrator</span>
                    <h3 class="fw-bold mb-1">Pusat Kendali E-Attitude Learning</h3>
                    <p class="text-white-50 mb-0">Pantau seluruh pengguna, kelola kurasi materi karakter, dan monitor progres evaluasi siswa.</p>
                </div>
                <div>
                    <a href="{{ route('admin.materi.pending') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow">
                        <i class="bi bi-patch-question me-1"></i> Review Materi ({{ $stats['pending_materials'] }})
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Review Alert if pending materials exist -->
        @if($stats['pending_materials'] > 0)
            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bell-fill fs-2 text-warning me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Ada {{ $stats['pending_materials'] }} Materi Menunggu Review Anda!</h5>
                        <p class="mb-0 small text-muted">Pembuat Materi telah mengajukan konten baru yang siap untuk ditinjau kelayakannya.</p>
                    </div>
                </div>
                <a href="{{ route('admin.materi.pending') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                    Tinjau Sekarang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif

        <!-- Stats Counter Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold">User (Siswa)</span>
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $stats['total_users'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold">Pembuat Materi</span>
                        <i class="bi bi-pencil-square text-warning fs-4"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $stats['total_pembuat'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold">Total Materi</span>
                        <i class="bi bi-journal-album text-info fs-4"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $stats['total_materials'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold">Rata Progres Siswa</span>
                        <i class="bi bi-graph-up text-success fs-4"></i>
                    </div>
                    <h3 class="fw-extrabold text-success mb-0">{{ $stats['avg_progress'] }}%</h3>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Chart 1: Materials per Category -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Jumlah Materi per Kategori Attitude</h5>
                    <div style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Material Status Distribution -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Status Distribusi Materi</h5>
                    <div style="height: 250px; position: relative;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Pending Review Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-activity text-danger me-2"></i>Aktivitas Progres Siswa Terbaru</h5>
                <a href="{{ route('admin.progres.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    Lihat Semua Progres
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Siswa</th>
                            <th>Materi yang Dipelajari</th>
                            <th>Kategori</th>
                            <th>Status & Nilai</th>
                            <th>Waktu Pengerjaan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProgresses as $prog)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $prog->user->name }}</div>
                                    <div class="small text-muted">{{ $prog->user->email }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $prog->material->title }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $prog->material->category->color }}">
                                        {{ $prog->material->category->name }}
                                    </span>
                                </td>
                                <td>
                                    @if($prog->status === 'completed')
                                        <span class="badge bg-success">Selesai (Skor: {{ $prog->score }})</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Berjalan ({{ $prog->progress_percentage }}%)</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $prog->updated_at->format('d M Y H:i') }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.progres.user', $prog->user_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail Siswa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas pengerjaan oleh siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Category Chart
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoriesChart->pluck('name')) !!},
            datasets: [{
                label: 'Jumlah Materi',
                data: {!! json_encode($categoriesChart->pluck('materials_count')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 2. Status Chart
    const ctxStat = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStat, {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Pending Review', 'Disetujui/Publikasi', 'Perlu Revisi'],
            datasets: [{
                data: [
                    {{ $stats['draft_materials'] }},
                    {{ $stats['pending_materials'] }},
                    {{ $stats['approved_materials'] }},
                    {{ $stats['revision_materials'] }}
                ],
                backgroundColor: ['#6c757d', '#ffc107', '#198754', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endpush
