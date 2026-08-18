<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Populasi Ternak Kabupaten Kediri')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f7f4;
        }
        main { flex: 1; }
        .bg-emerald {
            background-color: #1b4d3e !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #1b4d3e 0%, #2e7d32 100%);
            color: white;
            padding: 60px 0;
        }
        .btn-emerald {
            background-color: #2e7d32;
            color: white;
        }
        .btn-emerald:hover {
            background-color: #1b4d3e;
            color: white;
        }
    </style>
</head>
<body>

    <!-- HEADER / NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-emerald sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="{{ route('public.index') }}">
                <i class="fa-solid fa-cow me-2 text-warning"></i> Simnak Kediri
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('public.index') }}">Beranda</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        @auth
                            <a class="btn btn-warning btn-sm px-3 fw-bold" href="{{ route('admin.prediksi') }}">
                                <i class="fa-solid fa-gauge me-1"></i> Dashboard Admin
                            </a>
                        @else
                            <a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Login Petugas
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENT PLACEHOLDER -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-emerald text-white text-center text-lg-start mt-5">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase text-warning fw-bold">Sistem Monitoring Ternak</h5>
                    <p class="small text-light">
                        Layanan Pemantauan Populasi Ternak Kabupaten Kediri. Menyajikan data terintegrasi populasi ternak tingkat kecamatan secara akurat dan transparan.
                    </p>
                </div>
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-lg-end">
                    <h5 class="text-uppercase text-warning fw-bold">Kontak Dinas</h5>
                    <p class="small text-light mb-0">
                        Dinas Ketahanan Pangan dan Peternakan (DKPP) Kabupaten Kediri<br>
                        Jl. Soekarno Hatta No. 1, Kediri, Jawa Timur
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center p-3 bg-black bg-opacity-25 border-top border-success">
            <small>&copy; {{ date('Y') }} DKPP Kabupaten Kediri. All rights reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>