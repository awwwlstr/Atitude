@extends('layouts.app')

@section('title', 'Masuk ke Akun - E-Attitude')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle mb-2" style="width: 56px; height: 56px;">
                        <i class="bi bi-mortarboard-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Masuk ke E-Attitude</h4>
                    <p class="small text-white-50 mb-0">Silakan masukkan akun Anda untuk melanjutkan pembelajaran</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label fw-semibold">Email atau Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login') }}" placeholder="admin@attitude.com / username" required autofocus>
                            </div>
                            @error('login')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-semibold mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small" for="remember">Ingat Saya di Perangkat Ini</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-pill shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Belum memiliki akun siswa? </span>
                        <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none small">Daftar Akun Baru</a>
                    </div>
                </div>

                {{-- Demo Accounts Quick Helper for Evaluator/Reviewer --}}
                
                <!--<div class="card-footer bg-light p-3 border-top">
                    <div class="small fw-semibold text-muted mb-2 text-center">
                        <i class="bi bi-info-circle me-1"></i> Akun Pengujian / Demo:
                    </div>
                    <div class="row g-2 text-center" style="font-size: 0.75rem;">
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100 p-1 rounded" onclick="fillLogin('admin@attitude.com', 'password')">
                                <strong>Admin</strong><br>admin@attitude.com
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-warning text-dark btn-sm w-100 p-1 rounded" onclick="fillLogin('pembuat@attitude.com', 'password')">
                                <strong>Pembuat</strong><br>pembuat@attitude.com
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-success btn-sm w-100 p-1 rounded" onclick="fillLogin('ahmad@attitude.com', 'password')">
                                <strong>User/Siswa</strong><br>ahmad@attitude.com
                            </button>
                        </div>
                    </div>
                </div>-->
                
            </div>
        </div>
    </div>
</div>\*

<script>
function fillLogin(email, pass) {
    document.getElementById('login').value = email;
    document.getElementById('password').value = pass;
}
</script>
@endsection
