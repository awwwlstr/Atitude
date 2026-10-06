@extends('layouts.app')

@section('title', 'Belajar: ' . $material->title . ' - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Breadcrumb & Nav -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.materi.index') }}" class="text-decoration-none">Materi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.materi.show', $material->id) }}" class="text-decoration-none">{{ Str::limit($material->title, 25) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ruang Belajar</li>
            </ol>
        </nav>
        <div class="mt-2 mt-md-0">
            @if($material->questions->isNotEmpty())
                <a href="{{ route('user.materi.quiz', $material->id) }}" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Ke Latihan Soal <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>

    <!-- Main Learning Container -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <!-- Header -->
        <div class="bg-primary bg-gradient text-white p-4 p-md-5">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-{{ $material->category->color }} me-2">
                    <i class="bi {{ $material->category->icon }} me-1"></i> {{ $material->category->name }}
                </span>
                <span class="badge bg-light text-primary">Modul Pembelajaran</span>
            </div>
            <h1 class="fw-bold mb-2">{{ $material->title }}</h1>
            <p class="lead text-white-50 mb-0">{{ $material->description }}</p>
        </div>

        <div class="card-body p-4 p-md-5">
            <!-- Main Explanation if available -->
            @if($material->content)
                <div class="p-4 bg-light rounded-4 mb-5 border-start border-4 border-primary">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-chat-square-quote-fill text-primary me-2"></i>Konsep Utama:</h5>
                    <div class="text-secondary fs-6 leading-relaxed" style="white-space: pre-line;">{{ $material->content }}</div>
                </div>
            @endif

            <!-- Material Contents Items -->
            @if($material->contents->isNotEmpty())
                <h4 class="fw-bold text-dark mb-4"><i class="bi bi-collection-play-fill text-primary me-2"></i>Uraian Modul & Media Pembelajaran</h4>

                @foreach($material->contents as $index => $content)
                    <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4 border border-light">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                {{ $index + 1 }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $content->title }}</h5>
                        </div>

                        <!-- Content Render Based on Type -->
                        @if($content->type === 'text')
                            <div class="text-secondary leading-relaxed p-2" style="white-space: pre-line; line-height: 1.8;">
                                {{ $content->content }}
                            </div>

                        @elseif($content->type === 'image')
                            @if($content->content)
                                <p class="text-secondary mb-3">{{ $content->content }}</p>
                            @endif
                            @if($content->file_path)
                                <div class="text-center my-3">
                                    <img src="{{ $content->file_url }}" alt="{{ $content->title }}" class="img-fluid rounded-4 shadow-sm border" style="max-height: 480px; object-fit: contain;">
                                </div>
                            @endif

                        @elseif($content->type === 'video')
                            @if($content->content)
                                @php
                                    $videoUrl = $content->content;
                                    // Handle youtube URL conversion to embed format if needed
                                    if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                        $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                    }
                                @endphp

                                @if(str_contains($videoUrl, 'embed') || str_contains($videoUrl, 'youtube'))
                                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm my-3 border">
                                        <iframe src="{{ $videoUrl }}" title="{{ $content->title }}" allowfullscreen></iframe>
                                    </div>
                                @else
                                    <div class="p-3 bg-light rounded-3 mb-3">
                                        <a href="{{ $videoUrl }}" target="_blank" class="btn btn-danger rounded-pill">
                                            <i class="bi bi-play-btn-fill me-2"></i> Tonton Video di Tautan Eksternal
                                        </a>
                                    </div>
                                @endif
                            @endif

                            @if($content->file_path)
                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm my-3 border bg-black">
                                    <video controls class="w-100 h-100">
                                        <source src="{{ $content->file_url }}" type="video/mp4">
                                        Browser Anda tidak mendukung pemutar video HTML5.
                                    </video>
                                </div>
                            @endif

                        @elseif($content->type === 'file')
                            <div class="p-4 bg-light rounded-4 border d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-down-fill fs-1 text-primary me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $content->title }}</h6>
                                        <div class="small text-muted">{{ $content->content ?? 'Dokumen materi pendukung (PDF / File)' }}</div>
                                    </div>
                                </div>
                                @if($content->file_path)
                                    <a href="{{ $content->file_url }}" download class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" target="_blank">
                                        <i class="bi bi-download me-1"></i> Unduh File Pendukung
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            <!-- Navigation to Quiz / Completion -->
            <div class="card bg-light border-0 rounded-4 p-4 mt-5 text-center shadow-sm">
                <div class="max-w-700 mx-auto">
                    <i class="bi bi-check2-circle text-success fs-1 mb-2"></i>
                    <h4 class="fw-bold mb-2">Sudah Selesai Mempelajari Materi?</h4>
                    <p class="text-muted mb-4">Uji pemahaman Anda mengenai materi sikap karakter ini melalui latihan soal interaktif.</p>
                    
                    @if($material->questions->isNotEmpty())
                        <a href="{{ route('user.materi.quiz', $material->id) }}" class="btn btn-warning btn-lg text-dark fw-bold rounded-pill px-5 shadow">
                            <i class="bi bi-pencil-square me-2"></i> Mulai Kerjakan Latihan Soal ({{ $material->questions->count() }} Soal)
                        </a>
                    @else
                        <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="bi bi-check-lg me-2"></i> Selesaikan Materi Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
