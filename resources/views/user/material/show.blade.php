@extends('layouts.app')

@section('title', $material->title . ' - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.materi.index') }}" class="text-decoration-none">Materi</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($material->title, 35) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative" style="max-height: 280px; overflow: hidden;">
                    <img src="{{ $material->cover_image_url }}" alt="{{ $material->title }}" class="w-100" style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.85));">
                        <span class="badge bg-{{ $material->category->color }} mb-2">
                            <i class="bi {{ $material->category->icon }} me-1"></i> {{ $material->category->name }}
                        </span>
                        <h2 class="fw-bold mb-1">{{ $material->title }}</h2>
                        <div class="small opacity-75">
                            Oleh: {{ $material->creator->name }} • Disetujui: {{ $material->approved_at ? $material->approved_at->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Deskripsi Materi</h5>
                    <p class="lead text-secondary fs-6 mb-4">{{ $material->description }}</p>

                    @if($material->content)
                        <div class="p-4 bg-light rounded-4 mb-4 border">
                            <h6 class="fw-bold text-dark mb-2">Penjelasan Umum:</h6>
                            <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $material->content }}</p>
                        </div>
                    @endif

                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-nested me-2"></i>Daftar Isi & Modul Pembelajaran</h5>
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
                                        <span class="badge bg-light text-muted text-uppercase" style="font-size: 0.65rem;">Modul: {{ $content->type }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success rounded-pill">
                                    <i class="bi bi-check2"></i> Siap Dibaca
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item p-3 text-muted">Belum ada lampiran modul khusus. Penjelasan ada pada materi utama.</div>
                        @endforelse

                        @if($material->questions->isNotEmpty())
                            <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-circle me-3">
                                        <i class="bi bi-patch-question-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Latihan Soal & Evaluasi Pemahaman Sikap</h6>
                                        <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size: 0.65rem;">{{ $material->questions->count() }} Soal Evaluasi</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    Evaluasi
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('user.materi.learn', $material->id) }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="bi bi-book-half me-2"></i> Buka Ruang Belajar
                        </a>
                        @if($material->questions->isNotEmpty())
                            <a href="{{ route('user.materi.quiz', $material->id) }}" class="btn btn-warning btn-lg text-dark rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-pencil-square me-2"></i> Kerjakan Latihan Soal
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Progress & Details -->
        <div class="col-lg-4">
            <!-- Progress Status Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart text-primary me-2"></i>Status Belajar Anda</h5>

                @if(!$progress)
                    <div class="alert alert-secondary py-3 text-center mb-3">
                        <i class="bi bi-info-circle fs-4 d-block mb-1"></i>
                        <span class="small">Anda belum memulai materi ini. Klik "Buka Ruang Belajar" untuk mulai membaca.</span>
                    </div>
                @elseif($progress->status === 'in_progress')
                    <div class="alert alert-warning py-3 text-center mb-3">
                        <i class="bi bi-hourglass-split fs-4 d-block mb-1 text-warning"></i>
                        <strong>Sedang Dipelajari</strong>
                        <div class="small text-muted mt-1">Mulai: {{ $progress->started_at ? $progress->started_at->format('d M Y H:i') : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progres</span>
                            <span class="fw-bold">{{ $progress->progress_percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" style="width: {{ $progress->progress_percentage }}%"></div>
                        </div>
                    </div>
                @elseif($progress->status === 'completed')
                    <div class="alert alert-success py-3 text-center mb-3">
                        <i class="bi bi-check-circle-fill fs-3 d-block mb-1 text-success"></i>
                        <strong>Materi Telah Selesai!</strong>
                        <div class="small text-muted mt-1">Selesai: {{ $progress->completed_at ? $progress->completed_at->format('d M Y H:i') : '-' }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-3 text-center mb-3">
                        <div class="text-muted small">Nilai Evaluasi Akhir:</div>
                        <h2 class="fw-extrabold text-success mb-0">{{ $progress->score ?? 0 }} <span class="fs-6 text-muted">/ 100</span></h2>
                        <div class="badge bg-success-subtle text-success mt-1">Percobaan: {{ $progress->attempts_count }}x</div>
                    </div>
                    <a href="{{ route('user.hasil', ['material_id' => $material->id]) }}" class="btn btn-outline-success btn-sm w-100 rounded-pill mb-2">
                        <i class="bi bi-card-checklist me-1"></i> Lihat Rincian Jawaban
                    </a>
                @endif
            </div>

            <!-- Attitude Topic Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 rounded-circle bg-{{ $material->category->color }} bg-opacity-10 text-{{ $material->category->color }} me-3">
                        <i class="bi {{ $material->category->icon }} fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Dimensi Sikap</span>
                        <h5 class="fw-bold mb-0">{{ $material->category->name }}</h5>
                    </div>
                </div>
                <p class="small text-muted mb-0">{{ $material->category->description }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
