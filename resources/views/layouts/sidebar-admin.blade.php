@php
    $pendingReviewCount = \App\Models\Material::where('status', 'pending')->count();
@endphp
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-dark text-white p-3 d-flex align-items-center">
        <i class="bi bi-shield-lock-fill text-warning fs-5 me-2"></i>
        <span class="fw-bold">Menu Administrator</span>
    </div>
    <div class="list-group list-group-flush small">
        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-speedometer2 fs-5 me-3"></i>
            <span class="fw-semibold">Dashboard Utama</span>
        </a>

        <div class="list-group-item bg-light text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
            Manajemen Konten
        </div>

        <a href="{{ route('admin.materi.pending') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between {{ request()->routeIs('admin.materi.pending*') ? 'active bg-primary text-white border-0' : '' }}">
            <div class="d-flex align-items-center">
                <i class="bi bi-patch-question fs-5 me-3 text-warning"></i>
                <span class="fw-semibold">Review Materi</span>
            </div>
            @if($pendingReviewCount > 0)
                <span class="badge rounded-pill bg-danger">{{ $pendingReviewCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.materi.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.materi.index', 'admin.materi.show') && !request()->routeIs('admin.materi.pending*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-journal-album fs-5 me-3"></i>
            <span class="fw-semibold">Semua Materi</span>
        </a>

        <a href="{{ route('admin.kategori.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.kategori.*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-tags fs-5 me-3"></i>
            <span class="fw-semibold">Topik / Kategori Attitude</span>
        </a>

        <div class="list-group-item bg-light text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
            Manajemen Pengguna
        </div>

        <a href="{{ route('admin.pembuat.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.pembuat.*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-pencil-square fs-5 me-3"></i>
            <span class="fw-semibold">Pembuat Materi</span>
        </a>

        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-people fs-5 me-3"></i>
            <span class="fw-semibold">User (Siswa)</span>
        </a>

        <div class="list-group-item bg-light text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
            Monitoring & Evaluasi
        </div>

        <a href="{{ route('admin.progres.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.progres.*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-graph-up fs-5 me-3"></i>
            <span class="fw-semibold">Monitoring Progres Siswa</span>
        </a>

        <a href="{{ route('admin.laporan.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('admin.laporan.*') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-file-earmark-bar-graph fs-5 me-3"></i>
            <span class="fw-semibold">Laporan & Statistik</span>
        </a>
        {{-- Tombol Logout Langsung di Sidebar --}}
        <form action="{{ route('logout') }}" method="POST" class="p-2 border-top">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center">
                <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
            </button>
        </form>
    </div>
</div>
