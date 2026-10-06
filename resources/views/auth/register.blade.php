@extends('layouts.app')

@section('title', 'Daftar Akun Siswa - E-Attitude')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient bg-primary text-white text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle mb-2" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-plus-fill fs-2 text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Registrasi Akun Siswa</h4>
                    <p class="small text-white-50 mb-0">Mulai langkah membangun karakter mulia bersama E-Attitude</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-info py-2 small mb-4">
                        <i class="bi bi-info-circle-fill me-1"></i> Registrasi publik diperuntukkan bagi <strong>User/Siswa</strong>. Akun Pembuat Materi & Admin dibuat oleh Administrator.
                    </div>

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Pratama" required autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="budipratama" required>
                            </div>
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 6 karakter" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark fw-bold w-100 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-person-check-fill me-2"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Sudah memiliki akun? </span>
                        <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none small">Masuk di Sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
