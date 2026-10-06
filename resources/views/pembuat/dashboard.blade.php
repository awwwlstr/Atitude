@extends('layouts.app')

@section('title', 'Dashboard Pembuat Materi - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Pembuat -->
    <div class="col-lg-3">
        @include('layouts.sidebar-pembuat')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Header Banner -->
        <div class="card border-0 bg-primary text-white p-4 rounded-4 mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">Panel Kurator Konten</span>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-white-50 mb-0">Kelola dan kembangkan materi pembelajaran nilai karakter sikap dengan standar terbaik.</p>
                </div>
                <div>
                    <a href="{{ route('pembuat.materi.create') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow">
                        <i class="bi bi-plus-circle-fill me-1"></i> + Buat Materi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Revision Alert Notification if any -->
        @if($revisionMaterials->isNotEmpty())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4" role="alert">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-octagon-fill fs-4 me-2 text-danger"></i>
                    <h5 class="fw-bold mb-0">Perhatian: Ada {{ $revisionMaterials->count() }} Materi Memerlukan Revisi</h5>
                </div>
                <p class="mb-3 small">Admin telah meninjau materi Anda dan memberikan catatan perbaikan. Silakan periksa catatan dan perbaiki materi di bawah ini:</p>
                <div class="list-group">
                    @foreach($revisionMaterials as $revMat)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-3 mb-2 border shadow-sm">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $revMat->title }}</h6>
                                <div class="text-danger small"><i class="bi bi-chat-left-quote me-1"></i> "{{ Str::limit($revMat->revision_note, 80) }}"</div>
                            </div>
                            <a href="{{ route('pembuat.materi.show', $revMat->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold">
                                <i class="bi bi-wrench me-1"></i> Perbaiki Sekarang
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Stats Widgets -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="text-muted small fw-semibold">Total Materi</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="text-muted small fw-semibold">Draft</div>
                    <h3 class="fw-bold text-secondary mb-0">{{ $stats['draft'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="text-muted small fw-semibold">Menunggu Review</div>
                    <h3 class="fw-bold text-warning mb-0">{{ $stats['pending'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="text-muted small fw-semibold">Disetujui</div>
                    <h3 class="fw-bold text-success mb-0">{{ $stats['approved'] }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100">
                    <div class="text-muted small fw-semibold">Revisi</div>
                    <h3 class="fw-bold text-danger mb-0">{{ $stats['revision'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Recent Materials Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text text-primary me-2"></i>Daftar Materi Pembelajaran Anda</h5>
                <a href="{{ route('pembuat.materi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    Lihat Semua
                </a>
            </div>
            @if($recentMaterials->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-file-earmark-plus fs-1 text-muted"></i>
                    <h6 class="fw-bold mt-2">Belum ada materi yang Anda buat</h6>
                    <p class="small">Mulai buat materi baru mengenai nilai-nilai attitude untuk disetujui Admin.</p>
                    <a href="{{ route('pembuat.materi.create') }}" class="btn btn-primary rounded-pill btn-sm px-4">
                        + Buat Materi Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Judul Materi</th>
                                <th>Kategori Attitude</th>
                                <th>Status</th>
                                <th>Konten</th>
                                <th>Soal</th>
                                <th>Terakhir Diubah</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMaterials as $mat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $mat->title }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mat->category->color }}">
                                            {{ $mat->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $mat->status_badge_class }}">
                                            {{ $mat->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mat->contents->count() }} media</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mat->questions->count() }} soal</span>
                                    </td>
                                    <td class="small text-muted">{{ $mat->updated_at->format('d M Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pembuat.materi.show', $mat->id) }}" class="btn btn-outline-primary" title="Buka Detail">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if(in_array($mat->status, ['draft', 'revision']))
                                                <a href="{{ route('pembuat.materi.edit', $mat->id) }}" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
