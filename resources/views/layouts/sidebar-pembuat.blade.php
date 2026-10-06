@php
    $creatorId = auth()->id();
    $revisionCount = \App\Models\Material::where('creator_id', $creatorId)->where('status', 'revision')->count();
    $pendingCount = \App\Models\Material::where('creator_id', $creatorId)->where('status', 'pending')->count();
@endphp
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-gradient bg-primary text-white p-3">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-pencil-square fs-5 me-2 text-warning"></i>
            <span class="fw-bold">Menu Pembuat Materi</span>
        </div>
        <div class="small opacity-75">Kelola modul pembelajaran karakter</div>
    </div>
    <div class="p-3 bg-light border-bottom">
        <a href="{{ route('pembuat.materi.create') }}" class="btn btn-success w-100 rounded-pill fw-semibold shadow-sm d-flex align-items-center justify-content-center">
            <i class="bi bi-plus-circle-fill me-2 fs-5"></i> + Buat Materi Baru
        </a>
    </div>
    <div class="list-group list-group-flush small">
        <a href="{{ route('pembuat.dashboard') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('pembuat.dashboard') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-speedometer2 fs-5 me-3"></i>
            <span class="fw-semibold">Dashboard Pembuat</span>
        </a>

        <a href="{{ route('pembuat.materi.index') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between {{ request()->routeIs('pembuat.materi.index', 'pembuat.materi.show', 'pembuat.materi.edit') ? 'active bg-primary text-white border-0' : '' }}">
            <div class="d-flex align-items-center">
                <i class="bi bi-journal-text fs-5 me-3"></i>
                <span class="fw-semibold">Daftar Seluruh Materi</span>
            </div>
        </a>

        <a href="{{ route('pembuat.materi.index', ['status' => 'pending']) }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split fs-5 me-3 text-warning"></i>
                <span class="fw-semibold">Menunggu Review</span>
            </div>
            @if($pendingCount > 0)
                <span class="badge rounded-pill bg-warning text-dark">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('pembuat.materi.index', ['status' => 'revision']) }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon fs-5 me-3 text-danger"></i>
                <span class="fw-semibold">Perlu Revisi</span>
            </div>
            @if($revisionCount > 0)
                <span class="badge rounded-pill bg-danger">{{ $revisionCount }}</span>
            @endif
        </a>

        <a href="{{ route('pembuat.materi.index', ['status' => 'published']) }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
            <span class="fw-semibold">Materi Terpublikasi</span>
        </a>

         <a href="{{ route('profile') }}" class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ request()->routeIs('profile') ? 'active bg-primary text-white border-0' : '' }}">
            <i class="bi bi-person-gear fs-5 me-3"></i>
            <span class="fw-semibold">Pengaturan Akun</span>
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
