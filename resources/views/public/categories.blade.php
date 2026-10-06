@extends('layouts.app')

@section('title', '8 Topik Nilai Karakter - E-Attitude')

@section('content')
<div class="container py-2">
    <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1"><i class="bi bi-grid-fill me-2"></i>Topik Nilai Karakter & Attitude</h2>
                <p class="text-white-50 mb-0">Pilar-pilar penting dalam pembentukan etika, moral, dan kedewasaan karakter peserta didik.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-light text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                    {{ $categories->count() }} Dimensi Sikap
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100 p-4 text-center d-flex flex-column">
                    <div class="p-3 bg-{{ $category->color }} bg-opacity-10 text-{{ $category->color }} rounded-circle mx-auto mb-3" style="width: 72px; height: 72px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi {{ $category->icon }} fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $category->name }}</h5>
                    <p class="text-muted small mb-4 flex-grow-1">{{ $category->description }}</p>

                    <div class="mt-auto">
                        <a href="{{ route('public.materials', ['kategori' => $category->slug]) }}" class="btn btn-outline-{{ $category->color }} w-100 rounded-pill py-2 fw-semibold">
                            Lihat Materi ({{ $category->published_materials_count }})
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
