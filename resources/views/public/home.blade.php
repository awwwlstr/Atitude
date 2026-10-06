@extends('layouts.app')

@section('title', 'Beranda - E-Attitude Learning')

@section('content')
<!-- Hero Section -->
<div class="hero-banner p-4 p-md-5 mb-5 shadow">
    <div class="row align-items-center">
        <div class="col-lg-7 py-3">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                <i class="bi bi-stars me-1"></i> Media Pembelajaran Karakter & Nilai Sikap
            </span>
            <h1 class="display-5 fw-extrabold text-white mb-3">Membangun Karakter Unggul untuk Generasi Masa Depan</h1>
            <p class="lead text-white-50 mb-4">
                Platform pembelajaran interaktif nilai-nilai attitude oleh mahasiswa S2 UNNES prodi Penelitian dan Evaluasi Pendidikan Reguler Angkatan 2026.
            </p>
            <div class="d-flex flex-wrap gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-4 shadow">
                        <i class="bi bi-person-plus me-1"></i> Mulai Belajar Sekarang
                    </a>
                    <a href="{{ route('public.materials') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="bi bi-compass me-1"></i> Jelajahi Materi
                    </a>
                @else
                    @if(auth()->user()->isUser())
                        <a href="{{ route('user.dashboard') }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-4 shadow">
                            <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard Belajar
                        </a>
                    @elseif(auth()->user()->isPembuat())
                        <a href="{{ route('pembuat.dashboard') }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-4 shadow">
                            <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard Pembuat
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-4 shadow">
                            <i class="bi bi-shield-lock me-1"></i> Panel Administrator
                        </a>
                    @endif
                @endguest
            </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-center">
            <div class="p-4 bg-white bg-opacity-10 backdrop-blur rounded-4 border border-white border-opacity-25 shadow">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-25 rounded-3">
                            <h3 class="fw-bold mb-0 text-warning">{{ $stats['total_materials'] }}</h3>
                            <div class="small text-white">Materi Karakter</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-25 rounded-3">
                            <h3 class="fw-bold mb-0 text-warning">{{ $stats['total_categories'] }}</h3>
                            <div class="small text-white">Topik Nilai</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-25 rounded-3">
                            <h3 class="fw-bold mb-0 text-white">{{ $stats['total_users'] }}</h3>
                            <div class="small text-white-50">Siswa Terdaftar</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-25 rounded-3">
                            <h3 class="fw-bold mb-0 text-white">{{ $stats['total_creators'] }}</h3>
                            <div class="small text-white-50">Pembuat Konten</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 8 Topik Attitude Grid -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h6 class="text-primary fw-bold text-uppercase mb-1">Nilai-Nilai Luhur</h6>
            <h2 class="fw-bold mb-0">Topik Sikap & Karakter</h2>
        </div>
        <a href="{{ route('public.categories') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Lihat Semua Topik <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3">
        @foreach($categories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('public.materials', ['kategori' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card card-hover border-0 shadow-sm h-100 p-3 bg-white">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 rounded-circle bg-{{ $category->color }} bg-opacity-10 text-{{ $category->color }} me-2">
                                <i class="bi {{ $category->icon }} fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $category->name }}</h6>
                                <span class="badge bg-light text-muted">{{ $category->published_materials_count }} Materi</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-0 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $category->description }}
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Published Materials Highlights -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h6 class="text-primary fw-bold text-uppercase mb-1">Katalog Pembelajaran</h6>
            <h2 class="fw-bold mb-0">Materi Siap Pelajari</h2>
        </div>
        <a href="{{ route('public.materials') }}" class="btn btn-primary rounded-pill px-4">
            Jelajahi Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    @if($recentMaterials->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-journal-x fs-1 text-muted"></i>
                <h5 class="fw-bold mt-2">Belum ada materi yang dipublikasikan</h5>
                <p class="text-muted">Materi yang telah direview dan disetujui Admin akan tampil di sini.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($recentMaterials as $material)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover border-0 shadow-sm h-100 overflow-hidden d-flex flex-column">
                        <div class="position-relative" style="height: 180px; overflow: hidden;">
                            <img src="{{ $material->cover_image_url }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $material->title }}">
                            <span class="position-absolute top-0 start-0 m-3 badge bg-{{ $material->category->color }} shadow-sm">
                                <i class="bi {{ $material->category->icon }} me-1"></i> {{ $material->category->name }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column p-4 flex-grow-1">
                            <h5 class="fw-bold text-dark mb-2">{{ $material->title }}</h5>
                            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $material->description }}
                            </p>

                            <div class="pt-3 border-top mt-auto d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="small text-muted">
                                        <i class="bi bi-person me-1"></i> {{ $material->creator->name }}
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.materi.preview', $material->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        Preview
                                    </a>
                                    @auth
                                        @if(auth()->user()->isUser())
                                            <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                                Mulai <i class="bi bi-arrow-right"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                            Masuk & Belajar
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Alur Sistem Workflow Information -->
<div class="card border-0 bg-light rounded-4 p-4 p-md-5 mb-4 shadow-sm">
    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold mb-2">Alur Pembelajaran & Review Terpadu</span>
        <h2 class="fw-bold">Bagaimana Sistem Bekerja?</h2>
        <p class="text-muted">Proses terstruktur yang menjamin kualitas materi dan akurasi evaluasi karakter siswa.</p>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-4 shadow-sm h-100">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex mb-3">
                    <i class="bi bi-pencil-square fs-3"></i>
                </div>
                <h6 class="fw-bold">1. Pembuatan Materi</h6>
                <p class="small text-muted mb-0">Pembuat Materi menyusun teks, video, gambar, dan latihan soal berbasis 8 attitude.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-4 shadow-sm h-100">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex mb-3">
                    <i class="bi bi-patch-check fs-3"></i>
                </div>
                <h6 class="fw-bold">2. Review Admin</h6>
                <p class="small text-muted mb-0">Admin meninjau materi. Admin dapat menyetujui langsung atau meminta revisi perbaikan.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-4 shadow-sm h-100">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex mb-3">
                    <i class="bi bi-book fs-3"></i>
                </div>
                <h6 class="fw-bold">3. Pembelajaran Siswa</h6>
                <p class="small text-muted mb-0">User/Siswa mempelajari modul yang telah dipublikasikan dan mengerjakan latihan soal.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-4 shadow-sm h-100">
                <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle d-inline-flex mb-3">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                </div>
                <h6 class="fw-bold">4. Progres & Evaluasi</h6>
                <p class="small text-muted mb-0">Sistem merekam pencapaian, nilai latihan, dan persentase progres secara otomatis.</p>
            </div>
        </div>
    </div>
</div>
@endsection
