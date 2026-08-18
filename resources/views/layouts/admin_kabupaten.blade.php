<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Kabupaten') - Simnak</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-navbar: #113d2f;
            --emerald-accent: #10b981;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .custom-navbar {
            background-color: var(--bg-navbar);
            padding: 0.75rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .brand-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
            text-decoration: none;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.5rem 0.85rem !important;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background-color: var(--emerald-accent);
            font-weight: 600;
        }

        .user-badge {
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout-custom {
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 0.85rem;
            padding: 0.375rem 0.85rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout-custom:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .text-emerald {
            color: var(--bg-navbar) !important;
        }

        .btn-emerald {
            background-color: var(--emerald-accent);
            border-color: var(--emerald-accent);
        }
        .btn-emerald:hover {
            background-color: #059669;
            border-color: #059669;
        }
    </style>
</head>
<body>

    <!-- NAVBAR SIMETRIS & PRESISI -->
    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container-fluid">
            <!-- Brand / Logo (Kiri) -->
            <a class="brand-logo d-flex align-items-center" href="{{ route('admin.kabupaten.dashboard') }}">
                Simnak Kabupaten
            </a>

            <button class="navbar-toggler navbar-dark border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <!-- Menu Navigasi Utama (Di Tengah: mx-auto) -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 text-center">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.dashboard') }}">
                           Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.data_ternak*') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.data_ternak') }}">
                           Data Ternak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.rekapitulasi') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.rekapitulasi') }}">
                           Rekapitulasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.prediksi*') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.prediksi') }}">
                           Regresi / Prediksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.kecamatan*') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.kecamatan.index') }}">
                           Kelola Kecamatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.jenis_ternak*') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.jenis_ternak.index') }}">
                           Jenis Ternak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::routeIs('admin.kabupaten.users*') ? 'active' : '' }}" 
                           href="{{ route('admin.kabupaten.users.index') }}">
                           Kelola User
                        </a>
                    </li>
                </ul>

                <!-- User Profile Dengan Icon & Logout (Kanan) -->
                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <span class="user-badge">
                        <i class="fa-solid fa-user me-1"></i>
                        {{ Auth::user()->name ?? 'Admin Kabupaten' }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout-custom">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-4 px-4">
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>