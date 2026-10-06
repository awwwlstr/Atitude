@extends('layouts.app')

@section('title', 'Review Materi Menunggu Persetujuan - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Admin -->
    <div class="col-lg-3">
        @include('layouts.sidebar-admin')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card border-0 bg-warning text-dark p-4 rounded-4 mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-dark text-white px-3 py-2 rounded-pill fw-bold mb-2">Penjaminan Mutu Konten</span>
                    <h3 class="fw-bold mb-1"><i class="bi bi-patch-question-fill me-2"></i>Materi Menunggu Review Admin</h3>
                    <p class="mb-0 small">Modul di bawah ini diajukan oleh Pembuat Materi dan membutuhkan persetujuan atau catatan revisi sebelum dipublikasikan.</p>
                </div>
                <div>
                    <span class="badge bg-white text-dark fs-6 px-3 py-2 rounded-pill fw-bold">
                        {{ $materials->total() }} Menunggu
                    </span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            @if($materials->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-check-circle-fill fs-1 text-success mb-2 d-block"></i>
                    <h5 class="fw-bold">Semua Materi Telah Ditinjau!</h5>
                    <p class="small mb-0">Tidak ada materi yang sedang menunggu review saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Materi</th>
                                <th>Kategori</th>
                                <th>Pembuat Konten</th>
                                <th>Modul / Media</th>
                                <th>Soal Latihan</th>
                                <th>Diajukan Pada</th>
                                <th class="text-end pe-4">Aksi Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $mat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $mat->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($mat->description, 50) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mat->category->color }}">
                                            {{ $mat->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark small">{{ $mat->creator->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mat->contents->count() }} media</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mat->questions->count() }} soal</span>
                                    </td>
                                    <td class="small text-muted">{{ $mat->updated_at->format('d M Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.materi.show', $mat->id) }}" class="btn btn-warning text-dark btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-search me-1"></i> Tinjau & Putuskan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $materials->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
