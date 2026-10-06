@extends('layouts.app')

@section('title', 'Latihan Soal: ' . $material->title . ' - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.materi.index') }}" class="text-decoration-none">Materi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.materi.show', $material->id) }}" class="text-decoration-none">{{ Str::limit($material->title, 25) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Latihan Soal</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <!-- Header -->
                <div class="bg-primary bg-gradient text-white p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="badge bg-warning text-dark mb-1">Evaluasi Pemahaman</span>
                            <h3 class="fw-bold mb-0">{{ $material->title }}</h3>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-primary fs-6 px-3 py-2 rounded-pill">
                                <i class="bi bi-question-circle me-1"></i> {{ $material->questions->count() }} Pertanyaan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-info py-3 mb-4 rounded-3 d-flex align-items-center">
                        <i class="bi bi-lightbulb-fill fs-4 text-warning me-3"></i>
                        <div>
                            <strong>Petunjuk Pengerjaan:</strong>
                            <div class="small">Bacalah setiap pertanyaan dengan saksama dan pilihlah jawaban yang paling mencerminkan penerapan nilai karakter yang tepat. Jawaban Anda akan dinilai langsung oleh sistem.</div>
                        </div>
                    </div>

                    <form action="{{ route('user.materi.submit', $material->id) }}" method="POST" id="quizForm">
                        @csrf

                        @foreach($material->questions as $index => $question)
                            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light border-start border-4 border-primary">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-primary mb-0">Pertanyaan No. {{ $index + 1 }}</h6>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $question->points }} Poin</span>
                                </div>

                                <p class="fw-bold text-dark fs-5 mb-4">{{ $question->question }}</p>

                                @if($question->type === 'multiple_choice' || $question->type === 'true_false')
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($question->options as $optIndex => $option)
                                            <div class="form-check p-0">
                                                <input class="btn-check" type="radio" name="answers[{{ $question->id }}]" id="opt_{{ $option->id }}" value="{{ $option->id }}" required>
                                                <label class="btn btn-outline-light text-dark w-100 text-start p-3 rounded-3 border bg-white d-flex align-items-center" for="opt_{{ $option->id }}">
                                                    <span class="badge bg-light text-dark border me-3 px-2 py-1">
                                                        {{ chr(65 + $optIndex) }}
                                                    </span>
                                                    <span>{{ $option->option_text }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($question->type === 'short_answer')
                                    <div class="mb-2">
                                        <input type="text" name="answers[{{ $question->id }}]" class="form-control form-control-lg bg-white" placeholder="Tuliskan jawaban singkat Anda di sini..." required>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="card bg-white border-0 p-4 rounded-4 shadow-sm text-center">
                            <h5 class="fw-bold mb-2">Pastikan Seluruh Soal Telah Terjawab</h5>
                            <p class="text-muted small mb-4">Setelah menekan tombol kirim, nilai dan progres Anda akan langsung disimpan ke database.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                                    Batal & Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
                                    <i class="bi bi-send-check-fill me-2"></i> Kirim Jawaban Evaluasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-check:checked + .btn-outline-light {
    background-color: #e0f2fe !important;
    border-color: #0284c7 !important;
    color: #0369a1 !important;
    font-weight: 600;
}
</style>
@endsection
