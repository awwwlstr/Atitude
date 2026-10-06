@extends('layouts.app')

@section('title', 'Hasil & Evaluasi Latihan Sikap - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Header banner -->
    <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">Evaluasi Hasil Belajar</span>
                <h2 class="fw-bold mb-1"><i class="bi bi-award-fill me-2"></i>Hasil Latihan & Kuis Sikap</h2>
                <p class="text-white-50 mb-0">Lihat skor yang Anda peroleh dan telaah kembali jawaban pada setiap soal latihan.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('user.materi.index') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                    <i class="bi bi-book me-1"></i> Materi Lainnya
                </a>
            </div>
        </div>
    </div>

    <!-- Results Overview -->
    <div class="row g-4 mb-4">
        <!-- List of completed materials -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-check me-2 text-primary"></i>Pilih Materi yang Telah Dikerjakan</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($completedProgresses as $prog)
                        <a href="{{ route('user.hasil', ['material_id' => $prog->material_id]) }}" class="list-group-item list-group-item-action p-3 {{ $selectedMaterial && $selectedMaterial->id == $prog->material_id ? 'bg-primary bg-opacity-10 border-start border-4 border-primary' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-{{ $prog->material->category->color }}">{{ $prog->material->category->name }}</span>
                                <span class="badge bg-success-subtle text-success fw-bold fs-6">Skor: {{ $prog->score }}</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $prog->material->title }}</h6>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Percobaan: {{ $prog->attempts_count }}x</span>
                                <span>{{ $prog->completed_at ? $prog->completed_at->format('d M Y H:i') : '' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-emoji-neutral fs-2 d-block mb-2"></i>
                            Belum ada latihan soal yang diselesaikan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detailed Review Section -->
        <div class="col-lg-7">
            @if($selectedMaterial)
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <span class="badge bg-{{ $selectedMaterial->category->color }} mb-1">{{ $selectedMaterial->category->name }}</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $selectedMaterial->title }}</h5>
                        </div>
                        <a href="{{ route('user.materi.quiz', $selectedMaterial->id) }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 fw-semibold">
                            <i class="bi bi-arrow-repeat me-1"></i> Ulangi Latihan
                        </a>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-card-checklist me-2"></i>Rincian Soal & Jawaban Terakhir:</h6>

                    @forelse($selectedMaterial->questions as $index => $q)
                        @php
                            $userAns = $detailedAnswers[$q->id] ?? null;
                            $correctOpt = $q->options->firstWhere('is_correct', true);
                        @endphp
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 {{ $userAns && $userAns->is_correct ? 'bg-success bg-opacity-10 border-start border-4 border-success' : 'bg-danger bg-opacity-10 border-start border-4 border-danger' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold small">Soal No. {{ $index + 1 }}</span>
                                @if($userAns && $userAns->is_correct)
                                    <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Benar (+{{ $userAns->score }} Poin)</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i> Kurang Tepat (0 Poin)</span>
                                @endif
                            </div>

                            <p class="fw-semibold text-dark mb-2">{{ $q->question }}</p>

                            <div class="small text-muted mb-1">
                                <strong>Jawaban Anda:</strong> {{ $userAns ? $userAns->answer : 'Tidak dijawab' }}
                            </div>

                            @if(!$userAns || !$userAns->is_correct)
                                <div class="small text-success fw-semibold">
                                    <i class="bi bi-lightbulb me-1"></i> <strong>Kunci Jawaban yang Benar:</strong> {{ $correctOpt ? $correctOpt->option_text : '-' }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="alert alert-info">Materi ini tidak memiliki soal evaluasi tertulis.</div>
                    @endforelse
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <i class="bi bi-hand-index-thumb fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">Pilih Materi di Samping</h5>
                    <p class="mb-0">Pilih salah satu materi yang telah selesai Anda kerjakan untuk melihat rincian evaluasi dan jawaban soal.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
