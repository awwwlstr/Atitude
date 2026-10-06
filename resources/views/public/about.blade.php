@extends('layouts.app')

@section('title', 'Tentang Sistem - E-Attitude')

@section('content')
<div class="container py-2">
    <div class="card border-0 bg-primary text-white p-4 p-md-5 rounded-4 mb-4 shadow-sm">
        <div class="max-w-700">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">Tentang Platform</span>
            <h1 class="display-6 fw-bold">E-Attitude Learning Management</h1>
            <p class="lead text-white-50 mb-0">Platform Web Pembelajaran dan Evaluasi Nilai-Nilai Karakter oleh Tim Pengembang E-Attitude.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                <h4 class="fw-bold text-primary mb-3"><i class="bi bi-bullseye me-2"></i>Latar Belakang & Tujuan</h4>
                <p class="text-secondary leading-relaxed">
                    Pendidikan karakter memegang peranan krusial dalam membentuk insan yang berbudi luhur, berintegritas, dan bertanggung jawab. Sistem <strong>E-Attitude</strong> dirancang untuk mendukung proses pembelajaran nilai-nilai karakter secara sistematis, terukur, dan interaktif.
                </p>
                <p class="text-secondary leading-relaxed">
                    Aplikasi ini mengintegrasikan 4 peran pengguna dengan alur penjaminan mutu konten yang ketat: setiap modul yang dibuat harus melalui proses kurasi dan persetujuan Admin sebelum dapat diakses oleh siswa.
                </p>

                <h4 class="fw-bold text-primary mt-4 mb-3"><i class="bi bi-layers me-2"></i>Nilai-Nilai Karakter yang Dikembangkan</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-success"><i class="bi bi-shield-check me-2"></i>1. Jujur</h6>
                            <p class="small text-muted mb-0">Kesesuaian kata, hati, dan perbuatan serta menjunjung integritas.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>2. Disiplin</h6>
                            <p class="small text-muted mb-0">Kepatuhan pada norma, manajemen waktu, dan konsistensi.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-warning"><i class="bi bi-briefcase me-2"></i>3. Berbakti Kepada Orang Tua</h6>
                            <p class="small text-muted mb-0">Sikap hormat, taat, dan berbuat baik kepada ayah dan ibu dengan penuh kasih sayang serta tidak menyakiti perasaan mereka.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-info"><i class="bi bi-people me-2"></i>4. Berbicara</h6>
                            <p class="small text-muted mb-0">Cara menyampaikan pikiran, perasaan, atau informasi kepada orang lain dengan kata-kata yang sopan dan santun.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-danger"><i class="bi bi-heart-fill me-2"></i>5. Berdandan</h6>
                            <p class="small text-muted mb-0">Sikap menjaga penampilan agar bersih, rapi, dan pantas sesuai norma yang berlaku.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-secondary"><i class="bi bi-person-check me-2"></i>6. Berjalan</h6>
                            <p class="small text-muted mb-0">Sikap berjalan dengan sopan, tertib, dan tidak mengganggu atau merugikan orang lain.</p>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>Spesifikasi Sistem</h5>
                <ul class="list-unstyled small mb-0">
                    <li class="py-2 border-bottom d-flex justify-content-between">
                        <span class="text-muted">Framework</span>
                        <strong>Laravel 12</strong>
                    </li>
                    <li class="py-2 border-bottom d-flex justify-content-between">
                        <span class="text-muted">Frontend</span>
                        <strong>Blade + Bootstrap 5</strong>
                    </li>
                    <li class="py-2 border-bottom d-flex justify-content-between">
                        <span class="text-muted">Database</span>
                        <strong>MySQL</strong>
                    </li>
                    <li class="py-2 border-bottom d-flex justify-content-between">
                        <span class="text-muted">Role System</span>
                        <strong>Admin, Guru, Siswa, Kurator</strong>
                    </li>
                    <li class="py-2 d-flex justify-content-between">
                        <span class="text-muted">Evaluasi</span>
                        <strong>Skor & Progres Real-Time</strong>
                    </li>
                    <li class="py-2 d-flex align-items-start"> 
                        <span class="text-muted flex-shrink-0" style="width: 35%;"> Tim Pengembang </span> <strong class="text-end flex-grow-1"> Mahasiswa S2 Penelitian dan Evaluasi Pendidikan Reguler Angkatan 2026 </strong> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
