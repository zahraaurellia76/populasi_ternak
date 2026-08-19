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
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        main { flex: 1; }

        /* Navigation Bar */
        .main-navbar {
            background: linear-gradient(to right, #164e3b 0%, #226040 50%, #2d7a4c 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .main-navbar .navbar-brand,
        .main-navbar .nav-link {
            color: #ffffff !important;
        }
        .main-navbar .nav-link:hover {
            color: #eab308 !important;
        }

        /* Action Buttons & Badges (Global) */
        .btn-action-edit {
            background-color: #fef3c7 !important;
            color: #d97706 !important;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 12px;
            transition: all 0.2s ease-in-out;
        }
        .btn-action-edit:hover {
            background-color: #fde68a !important;
            color: #b45309 !important;
            transform: translateY(-2px);
        }
        .btn-action-delete {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 12px;
            transition: all 0.2s ease-in-out;
        }
        .btn-action-delete:hover {
            background-color: #fca5a5 !important;
            color: #991b1b !important;
            transform: translateY(-2px);
        }
        .badge-soft-emerald {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .badge-soft-blue {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }

        /* Footer Layout */
        .site-footer {
            background-color: #1a4d3e;
            color: #ffffff;
            margin-top: 50px;
        }
        .footer-brand {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
        }
        .footer-description {
            color: #cbd5e1;
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 650px;
        }
        .footer-title {
            color: #eab308;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #cbd5e1;
            font-size: 0.88rem;
            line-height: 1.5;
        }
        .contact-item i {
            color: #eab308;
            width: 18px;
            margin-top: 3px;
        }
        .footer-bottom {
            background-color: #143e32;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 12px 0;
        }
        .footer-bottom small {
            color: #cbd5e1;
            font-size: 0.78rem;
        }

        @media (max-width: 767px) {
            .site-footer { text-align: left; }
            .footer-description { max-width: 100%; }
            .footer-bottom .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar sticky-top shadow-sm">
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

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container py-4">
            <div class="row align-items-start">
                <div class="col-lg-7 col-md-7 mb-3 mb-md-0">
                    <div class="footer-brand mb-2">
                        <i class="fa-solid fa-cow me-2 text-warning"></i>
                        <span>Simnak Kediri</span>
                    </div>
                    <p class="footer-description mb-0">
                        Sistem Informasi Monitoring Populasi Ternak Kabupaten Kediri.
                        Menyajikan informasi populasi ternak tingkat kecamatan secara terintegrasi dan transparan.
                    </p>
                </div>
                <div class="col-lg-5 col-md-5">
                    <h6 class="footer-title">Kontak Dinas</h6>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fa-solid fa-building"></i>
                            <span>Dinas Ketahanan Pangan dan Peternakan (DKPP) Kabupaten Kediri</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Jl. Penanggungan No.12, Bandar Lor, Kec. Mojoroto, Kabupaten Kediri, Jawa Timur 64114</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small>© {{ date('Y') }} Simnak Kediri</small>
                    <small>DKPP Kabupaten Kediri</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>