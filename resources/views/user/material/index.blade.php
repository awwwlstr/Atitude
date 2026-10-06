@extends('layouts.app')

@section('title', 'Daftar Materi Belajar - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Header banner -->
    <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i>Materi Pembelajaran Karakter</h2>
                <p class="text-white-50 mb-0">Pilih materi, pelajari kontennya, dan kerjakan latihan evaluasi untuk mengasah sikap.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-light text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                    {{ $materials->total() }} Modul Tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm p-3 rounded-4 mb-4">
        <form action="{{ route('user.materi.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="Cari materi..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select bg-light">
                    <option value="">Semua Topik Attitude</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">Semua Status Belajar</option>
                    <option value="unstarted" {{ request('status') == 'unstarted' ? 'selected' : '' }}>Belum Dimulai</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Sudah Selesai</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->hasAny(['q', 'kategori', 'status']))
                    <a href="{{ route('user.materi.index') }}" class="btn btn-outline-secondary rounded-pill px-2">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Materials Grid -->
    @if($materials->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-journal-x fs-1 text-muted"></i>
                <h5 class="fw-bold mt-2">Materi tidak ditemukan</h5>
                <p class="text-muted">Coba ubah kriteria pencarian atau filter status Anda.</p>
                <a href="{{ route('user.materi.index') }}" class="btn btn-outline-primary rounded-pill px-4">Reset Filter</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($materials as $material)
                @php
                    $prog = $material->progress->first();
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover border-0 shadow-sm h-100 overflow-hidden d-flex flex-column">
                        <div class="position-relative" style="height: 180px;">
                            <img src="{{ $material->cover_image_url }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $material->title }}">
                            <span class="position-absolute top-0 start-0 m-3 badge bg-{{ $material->category->color }} shadow-sm">
                                <i class="bi {{ $material->category->icon }} me-1"></i> {{ $material->category->name }}
                            </span>
                            <div class="position-absolute top-0 end-0 m-3">
                                @if(!$prog)
                                    <span class="badge bg-secondary shadow-sm">Belum Dimulai</span>
                                @elseif($prog->status === 'in_progress')
                                    <span class="badge bg-warning text-dark shadow-sm"><i class="bi bi-hourglass-split"></i> Berjalan</span>
                                @elseif($prog->status === 'completed')
                                    <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle-fill"></i> Selesai ({{ $prog->score }} pts)</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column p-4 flex-grow-1">
                            <h5 class="fw-bold text-dark mb-2">{{ $material->title }}</h5>
                            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $material->description }}
                            </p>

                            <!-- Progress Indicator -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Progres Pengerjaan</span>
                                    <span class="fw-semibold">{{ $prog->progress_percentage ?? 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $prog && $prog->status === 'completed' ? 'bg-success' : 'bg-primary' }}" style="width: {{ $prog->progress_percentage ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="pt-3 border-top mt-auto d-flex align-items-center justify-content-between">
                                <div class="small text-muted">
                                    <i class="bi bi-person me-1"></i> {{ $material->creator->name }}
                                </div>
                                <div>
                                    @if(!$prog)
                                        <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold">
                                            <i class="bi bi-play-circle me-1"></i> Mulai Belajar
                                        </a>
                                    @elseif($prog->status === 'in_progress')
                                        <a href="{{ route('user.materi.learn', $material->id) }}" class="btn btn-warning text-dark btn-sm rounded-pill px-3 fw-semibold">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Lanjutkan
                                        </a>
                                    @else
                                        <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">
                                            <i class="bi bi-check2-circle me-1"></i> Ulas Materi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $materials->links() }}
        </div>
    @endif
</div>
@endsection
