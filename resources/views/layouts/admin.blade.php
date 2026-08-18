<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin Kecamatan - Simnak' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            white-space: nowrap;
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
            white-space: nowrap;
        }

        .btn-logout-custom {
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 0.85rem;
            padding: 0.375rem 0.85rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            background: transparent;
            white-space: nowrap;
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

    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container-fluid">
            <a class="brand-logo d-flex align-items-center" href="{{ url('/admin-kecamatan/dashboard') }}">
                Simnak Kecamatan
            </a>

            <button class="navbar-toggler navbar-dark border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 text-center">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::is('admin-kecamatan/dashboard*') ? 'active' : '' }}" 
                           href="{{ url('/admin-kecamatan/dashboard') }}">
                           Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::is('admin-kecamatan/populasi*') ? 'active' : '' }}" 
                           href="{{ url('/admin-kecamatan/populasi') }}">
                           Input Populasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::is('admin-kecamatan/rekapitulasi*') ? 'active' : '' }}" 
                           href="{{ url('/admin-kecamatan/rekapitulasi') }}">
                           Rekapitulasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ Request::is('admin-kecamatan/prediksi*') ? 'active' : '' }}" 
                           href="{{ url('/admin-kecamatan/prediksi') }}">
                           Regresi / Prediksi
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <span class="user-badge">
                        <i class="fa-solid fa-user me-1"></i>
                        {{ Auth::user()->nama ?? Auth::user()->name ?? 'Admin Kecamatan' }}
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

    <main class="container-fluid py-4 px-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>