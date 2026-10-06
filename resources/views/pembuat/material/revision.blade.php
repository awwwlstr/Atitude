@extends('layouts.app')

@section('title', 'Catatan Revisi Materi - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Pembuat -->
    <div class="col-lg-3">
        @include('layouts.sidebar-pembuat')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-octagon-fill me-2"></i>Catatan Revisi dari Administrator</h5>
                <a href="{{ route('pembuat.materi.show', $material->id) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Ke Editor Materi
                </a>
            </div>
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-2">{{ $material->title }}</h4>
                <div class="mb-4">
                    <span class="badge bg-{{ $material->category->color }} me-2">{{ $material->category->name }}</span>
                    <span class="badge bg-danger">Status: Perlu Revisi</span>
                </div>

                <div class="p-4 bg-danger bg-opacity-10 rounded-4 border border-danger-subtle mb-4">
                    <h6 class="fw-bold text-danger mb-2"><i class="bi bi-chat-quote-fill me-1"></i>Pesan Revisi dari Admin:</h6>
                    <div class="text-dark fs-6" style="white-space: pre-line;">{{ $material->revision_note ?? 'Tidak ada catatan khusus.' }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Setelah melakukan perbaikan materi, klik tombol di sebelah kanan untuk mengirim ulang materi ke Admin.
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pembuat.materi.edit', $material->id) }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                            <i class="bi bi-pencil me-1"></i> Edit Deskripsi & Judul
                        </a>
                        <a href="{{ route('pembuat.materi.show', $material->id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">
                            <i class="bi bi-wrench me-1"></i> Kelola Modul & Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
