<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i>
            <span>E-Attitude</span>
            <span class="badge bg-light text-primary ms-2 fs-6 px-2 py-1 rounded-pill">Karakter</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.materials*') ? 'active fw-semibold' : '' }}" href="{{ route('public.materials') }}">
                        <i class="bi bi-journal-bookmark me-1"></i> Materi Karakter
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.categories*') ? 'active fw-semibold' : '' }}" href="{{ route('public.categories') }}">
                        <i class="bi bi-grid me-1"></i> Topik Nilai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.about*') ? 'active fw-semibold' : '' }}" href="{{ route('public.about') }}">
                        <i class="bi bi-info-circle me-1"></i> Tentang
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-3 rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-warning text-dark fw-semibold px-3 rounded-pill shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Daftar Siswa
                    </a>
                @else
                    {{-- Role Shortcut Button --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light text-primary btn-sm rounded-pill fw-semibold me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Panel Admin
                        </a>
                    @elseif(auth()->user()->isPembuat())
                        <a href="{{ route('pembuat.dashboard') }}" class="btn btn-light text-primary btn-sm rounded-pill fw-semibold me-2">
                            <i class="bi bi-pencil-square me-1"></i> Panel Pembuat
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-light text-primary btn-sm rounded-pill fw-semibold me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard Belajar
                        </a>
                    @endif

                    {{-- User Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-link text-white text-decoration-none dropdown-toggle d-flex align-items-center p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle border border-2 border-white me-2" width="36" height="36" style="object-fit: cover;">
                            <div class="d-none d-md-block text-start">
                                <div class="fw-semibold small leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-white-50 small" style="font-size: 0.75rem;">
                                    @if(auth()->user()->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif(auth()->user()->isPembuat())
                                        <span class="badge bg-warning text-dark">Pembuat Materi</span>
                                    @else
                                        <span class="badge bg-success">User / Siswa</span>
                                    @endif
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><h6 class="dropdown-header text-uppercase small">Pengguna Terautentikasi</h6></li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                    <i class="bi bi-person-circle text-primary me-2"></i> Profil Saya
                                </a>
                            </li>
                            @if(auth()->user()->isUser())
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('user.dashboard') }}">
                                        <i class="bi bi-speedometer2 text-info me-2"></i> Dashboard Belajar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('user.progres') }}">
                                        <i class="bi bi-graph-up text-success me-2"></i> Progres Belajar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('user.hasil') }}">
                                        <i class="bi bi-award text-warning me-2"></i> Hasil Latihan
                                    </a>
                                </li>
                            @elseif(auth()->user()->isPembuat())
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('pembuat.dashboard') }}">
                                        <i class="bi bi-speedometer2 text-warning me-2"></i> Dashboard Pembuat
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('pembuat.materi.index') }}">
                                        <i class="bi bi-journal-text text-primary me-2"></i> Kelola Materi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('pembuat.materi.create') }}">
                                        <i class="bi bi-plus-circle text-success me-2"></i> Buat Materi Baru
                                    </a>
                                </li>
                            @elseif(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 text-danger me-2"></i> Dashboard Admin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.materi.pending') }}">
                                        <i class="bi bi-patch-question text-warning me-2"></i> Review Materi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people text-info me-2"></i> Manajemen User
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
