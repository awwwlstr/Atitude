@extends('layouts.app')

@section('title', 'Preview: ' . $material->title . ' - E-Attitude')

@section('content')
<div class="container py-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.materials') }}" class="text-decoration-none">Materi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preview: {{ Str::limit($material->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Preview Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative" style="max-height: 320px; overflow: hidden;">
                    <img src="{{ $material->cover_image_url }}" alt="{{ $material->title }}" class="w-100" style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <span class="badge bg-{{ $material->category->color }} mb-2">
                            <i class="bi {{ $material->category->icon }} me-1"></i> {{ $material->category->name }}
                        </span>
                        <h2 class="fw-bold mb-1">{{ $material->title }}</h2>
                        <div class="small opacity-75">
                            Oleh: <strong>{{ $material->creator->name }}</strong> • Disetujui: {{ $material->approved_at ? $material->approved_at->format('d M Y') : 'Baru' }}
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Tentang Materi Ini</h5>
                    <p class="lead text-muted fs-6 mb-4">{{ $material->description }}</p>

                    @if($material->content)
                        <div class="p-4 bg-light rounded-4 mb-4 border">
                            <h6 class="fw-bold mb-2">Ringkasan Materi:</h6>
                            <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $material->content }}</p>
                        </div>
                    @endif

                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-collection-play me-2"></i>Struktur Konten & Silabus</h5>
                    <div class="list-group mb-4 shadow-sm rounded-3">
                        @forelse($material->contents as $index => $content)
                            <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                                        @if($content->type === 'video')
                                            <i class="bi bi-play-circle-fill fs-5 text-danger"></i>
                                        @elseif($content->type === 'image')
                                            <i class="bi bi-image-fill fs-5 text-info"></i>
                                        @elseif($content->type === 'file')
                                            <i class="bi bi-file-earmark-pdf-fill fs-5 text-warning"></i>
                                        @else
                                            <i class="bi bi-file-text-fill fs-5 text-primary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $content->title }}</h6>
                                        <span class="badge bg-light text-muted text-uppercase" style="font-size: 0.65rem;">Tipe: {{ $content->type }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                    <i class="bi bi-lock-fill me-1"></i> Terkunci
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item p-3 text-muted">Belum ada konten tambahan pada materi ini.</div>
                        @endforelse

                        @if($material->questions->isNotEmpty())
                            <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-success bg-opacity-10 text-success rounded-circle me-3">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Latihan Soal & Evaluasi Sikap</h6>
                                        <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem;">{{ $material->questions->count() }} Pertanyaan Evaluasi</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                    <i class="bi bi-lock-fill me-1"></i> Terkunci
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Call To Action -->
                    <div class="card bg-primary text-white border-0 rounded-4 p-4 text-center shadow">
                        <h4 class="fw-bold mb-2">Ingin Mempelajari Materi Ini & Mengerjakan Latihan?</h4>
                        <p class="text-white-50 mb-4">Masuk atau daftarkan akun siswa Anda untuk mengakses modul lengkap, menonton video, dan mencatat progres penilaian karakter Anda.</p>
                        <div class="d-flex justify-content-center gap-3">
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Akun
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light rounded-pill px-4">
                                    <i class="bi bi-person-plus me-1"></i> Daftar Gratis
                                </a>
                            @else
                                @if(auth()->user()->isUser())
                                    <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-5 shadow">
                                        <i class="bi bi-play-fill me-1"></i> Masuk ke Ruang Belajar
                                    </a>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Related Materials -->
        <div class="col-lg-4">
            <!-- Attitude Topic Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 rounded-circle bg-{{ $material->category->color }} bg-opacity-10 text-{{ $material->category->color }} me-3">
                        <i class="bi {{ $material->category->icon }} fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Kategori Sikap</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $material->category->name }}</h5>
                    </div>
                </div>
                <p class="small text-muted mb-0">{{ $material->category->description }}</p>
            </div>

            <!-- Related Materials -->
            @if($relatedMaterials->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-text me-2"></i>Materi Terkait Lainnya</h6>
                    <div class="list-group list-group-flush">
                        @foreach($relatedMaterials as $rel)
                            <a href="{{ route('public.materi.preview', $rel->slug) }}" class="list-group-item list-group-item-action px-0 py-3 d-flex align-items-center">
                                <img src="{{ $rel->cover_image_url }}" alt="{{ $rel->title }}" class="rounded-3 me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-1 fw-semibold small text-dark">{{ $rel->title }}</h6>
                                    <span class="badge bg-light text-muted" style="font-size: 0.7rem;">{{ $rel->creator->name }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
