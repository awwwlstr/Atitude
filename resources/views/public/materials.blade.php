@extends('layouts.app')

@section('title', 'Katalog Materi Pembelajaran Karakter - E-Attitude')

@section('content')
<div class="container py-2">
    <!-- Header banner -->
    <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1"><i class="bi bi-journal-bookmark-fill me-2"></i>Katalog Materi Karakter</h2>
                <p class="text-white-50 mb-0">Temukan topik nilai-nilai sikap untuk membangun karakter unggul dan berbudi pekerti luhur.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-light text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                    {{ $materials->total() }} Materi Tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm p-3 rounded-4 mb-4">
        <form action="{{ route('public.materials') }}" method="GET" class="row g-3">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="Cari judul atau topik materi..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <select name="kategori" class="form-select bg-light">
                    <option value="">Semua Kategori Attitude</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->published_materials_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['q', 'kategori']))
                    <a href="{{ route('public.materials') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Materials Grid -->
    @if($materials->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h5 class="fw-bold mt-2">Materi tidak ditemukan</h5>
                <p class="text-muted">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                <a href="{{ route('public.materials') }}" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua Materi</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($materials as $material)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover border-0 shadow-sm h-100 overflow-hidden d-flex flex-column">
                        <div class="position-relative" style="height: 180px;">
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
                                <div class="small text-muted">
                                    <i class="bi bi-person me-1"></i> {{ $material->creator->name }}
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.materi.preview', $material->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        Preview
                                    </a>
                                    @auth
                                        @if(auth()->user()->isUser())
                                            <a href="{{ route('user.materi.show', $material->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                                Belajar <i class="bi bi-arrow-right"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                            Masuk
                                        </a>
                                    @endauth
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
