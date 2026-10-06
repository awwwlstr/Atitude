@extends('layouts.app')

@section('title', 'Review Materi: ' . $material->title . ' - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Review Action Bar Header -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-{{ $material->category->color }} fs-6">
                            {{ $material->category->name }}
                        </span>
                        <span class="badge {{ $material->status_badge_class }} fs-6">
                            Status: {{ $material->status_label }}
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $material->title }}</h3>
                    <div class="small text-muted">
                        Pembuat Konten: <strong>{{ $material->creator->name }}</strong> ({{ $material->creator->email }}) • Diajukan: {{ $material->created_at->format('d M Y H:i') }}
                    </div>
                </div>

                <!-- Decision Buttons -->
                <div class="d-flex gap-2 flex-wrap">
                    @if($material->status !== 'published')
                        <form action="{{ route('admin.materi.approve', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan mempublikasikan materi ini?')">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Setujui & Publikasikan
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#revisionModal">
                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Minta Revisi
                        </button>
                    @else
                        <span class="btn btn-success disabled rounded-pill px-3">
                            <i class="bi bi-check-all me-1"></i> Materi Telah Dipublikasikan
                        </span>
                    @endif

                    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Revision Note from previous review if any -->
        @if($material->revision_note)
            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-chat-quote-fill text-warning me-1"></i>Catatan Revisi Terakhir:</h6>
                <div class="small text-dark" style="white-space: pre-line;">{{ $material->revision_note }}</div>
            </div>
        @endif

        <!-- General Overview -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Deskripsi & Konsep Pokok</h5>
            <p class="lead fs-6 text-secondary mb-3">{{ $material->description }}</p>
            @if($material->content)
                <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line;">{{ $material->content }}</div>
            @endif
        </div>

        <!-- Contents / Modules List -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-collection-play text-primary me-2"></i>Konten & Modul Pembelajaran ({{ $material->contents->count() }})
                </h5>
            </div>
            <div class="card-body p-4">
                @if($material->contents->isEmpty())
                    <div class="text-center py-4 text-muted">Belum ada lampiran modul khusus.</div>
                @else
                    <div class="d-flex flex-column gap-4">
                        @foreach($material->contents as $index => $content)
                            <div class="p-4 bg-light rounded-4 border">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-primary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h5 class="fw-bold mb-0 text-dark">{{ $content->title }}</h5>
                                    <span class="badge bg-secondary ms-2 text-uppercase">{{ $content->type }}</span>
                                </div>

                                @if($content->type === 'text')
                                    <div class="text-secondary leading-relaxed" style="white-space: pre-line;">
                                        {{ $content->content }}
                                    </div>
                                @elseif($content->type === 'image')
                                    @if($content->content)<p class="small text-muted">{{ $content->content }}</p>@endif
                                    @if($content->file_path)
                                        <div class="text-center my-2">
                                            <img src="{{ $content->file_url }}" alt="{{ $content->title }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 250px;">
                                        </div>
                                    @endif
                                @elseif($content->type === 'video')
                                    <div class="small text-muted mb-2">Video: {{ $content->content }}</div>
                                    @if($content->content && (str_contains($content->content, 'embed') || str_contains($content->content, 'youtube')))
                                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm" style="max-width: 500px;">
                                            <iframe src="{{ str_replace('watch?v=', 'embed/', $content->content) }}" title="{{ $content->title }}" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                @elseif($content->type === 'file')
                                    <div class="p-3 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-4 me-2"></i>
                                            <span class="fw-semibold">{{ $content->title }}</span>
                                        </div>
                                        @if($content->file_path)
                                            <a href="{{ $content->file_url }}" download class="btn btn-outline-primary btn-sm rounded-pill">Unduh File</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Practice Questions List -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-patch-question text-warning me-2"></i>Latihan Soal Evaluasi Karakter ({{ $material->questions->count() }})
                </h5>
            </div>
            <div class="card-body p-4">
                @if($material->questions->isEmpty())
                    <div class="text-center py-4 text-muted">Belum ada latihan soal evaluasi.</div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($material->questions as $index => $q)
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-warning text-dark me-2">Soal No. {{ $index + 1 }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary me-2">{{ $q->type }}</span>
                                    <span class="badge bg-success-subtle text-success">{{ $q->points }} Poin</span>
                                </div>

                                <h6 class="fw-bold text-dark mb-3">{{ $q->question }}</h6>

                                <div class="ps-3 border-start border-3 border-warning">
                                    <div class="small fw-semibold text-muted mb-1">Opsi Jawaban & Kunci:</div>
                                    @foreach($q->options as $opt)
                                        <div class="small {{ $opt->is_correct ? 'text-success fw-bold' : 'text-secondary' }}">
                                            @if($opt->is_correct)
                                                <i class="bi bi-check-circle-fill me-1"></i> (Jawaban Benar)
                                            @else
                                                <i class="bi bi-dash-circle me-1 text-muted"></i>
                                            @endif
                                            {{ $opt->option_text }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Minta Revisi -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="revisionModalLabel"><i class="bi bi-exclamation-octagon-fill me-2"></i>Form Permintaan Revisi Materi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.materi.revision', $material->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="revision_note" class="form-label fw-semibold">Catatan / Instruksi Perbaikan untuk Pembuat Materi <span class="text-danger">*</span></label>
                        <textarea name="revision_note" id="revision_note" class="form-control" rows="5" placeholder="Contoh: Mohon perbaiki butir soal no 2 dan tambahkan contoh perilaku jujur di lingkungan sekolah..." required></textarea>
                        <div class="form-text">Catatan ini akan langsung tampil pada dashboard Pembuat Materi.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Kirim Catatan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
