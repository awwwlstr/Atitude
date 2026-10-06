<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Attitude Learning') - Platform Pembelajaran Nilai Karakter</title>

    <!-- Bootstrap 5 CSS -->
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


<!-- Chart.js -->
<script src="{{ asset('js/chart.umd.min.js') }}"></script>
    <style>
        :root {
            --bs-primary: #0d6efd;
            --bs-primary-rgb: 13, 110, 253;
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        .card {
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            font-weight: 500;
            padding: 0.45em 0.75em;
        }

        .progress {
            border-radius: 9999px;
            background-color: #e2e8f0;
        }

        .bg-indigo {
            background-color: #6366f1 !important;
            color: #ffffff !important;
        }

        .bg-purple {
            background-color: #a855f7 !important;
            color: #ffffff !important;
        }

        .bg-teal {
            background-color: #14b8a6 !important;
            color: #ffffff !important;
        }

        .navbar-brand {
            letter-spacing: -0.5px;
        }

        .hero-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #0284c7 100%);
            color: white;
            border-radius: 1.5rem;
        }

        .stat-widget {
            border-left: 4px solid var(--bs-primary);
        }

        footer {
            flex-shrink: 0;
            background-color: #0f172a;
            color: #94a3b8;
        }

        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')

    <main class="py-4">
        <div class="container-fluid px-lg-4">
            @include('layouts.alerts')
            @yield('content')
        </div>
    </main>

    <footer class="py-4 mt-5">
        <div class="container-fluid px-lg-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="bi bi-mortarboard-fill fs-4 text-warning me-2"></i>
                        <span class="fw-bold text-white fs-5">E-Attitude Learning</span>
                    </div>
                    <p class="small text-muted mb-0 mt-1">Sistem Pembelajaran & Evaluasi Nilai Karakter Berbasis Web</p>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    <p class="mb-0">&copy; {{ date('Y') }} E-Attitude. Dibangun dengan Laravel & Desain Modular.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle (Local Asset) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
