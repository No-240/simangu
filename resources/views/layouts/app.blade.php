@php
    $user         = auth()->user();
    $isAdmin      = $user->role === 'admin';
    $dashboardUrl = $isAdmin ? url('/admin/inventory') : url('/staff/inventory');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMANGU')</title>

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        /* ===== TEMA HIJAU PROFESIONAL (EMERALD) ===== */
        :root {
            --primary-green: #059669;
            --hover-green: #047857;
            --bg-body: #F3F4F6;
            --text-dark: #1F2937;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* 1. Pita Hijau Atas */
        .navbar-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 240px;
            background-color: var(--primary-green);
            background-image: none;
            box-shadow: none;
            z-index: 0;
        }

        /* Navbar transparan di atas pita */
        .navbar {
            background: transparent !important;
            border-bottom: none !important;
            box-shadow: none !important;
            z-index: 2;
        }
        .navbar .nav-link,
        .navbar .nav-link-user,
        .navbar .navbar-brand {
            color: #fff !important;
            font-weight: 500;
        }

        /* Sidebar */
        .main-sidebar {
            z-index: 3;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        /* Profil di sidebar */
        .sidebar-profile {
            background-color: var(--primary-green);
            color: #fff;
            text-align: center;
            padding: 28px 16px 22px;
            border-radius: 0;
            margin: 0;
            width: 100%;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-profile .sidebar-brand-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 14px;
            white-space: nowrap;
        }
        .sidebar-profile .avatar-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fff;
            color: var(--primary-green);
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .sidebar-profile .user-name {
            font-weight: 600;
            font-size: 15px;
            white-space: nowrap;
        }
        .sidebar-profile .user-role {
            font-size: 12px;
            opacity: 0.8;
            white-space: nowrap;
        }

        /* Menu aktif dan hover */
        .sidebar-menu li a.active,
        .sidebar-menu li a:hover {
            background-color: rgba(5, 150, 105, 0.1);
            box-shadow: inset 3px 0 0 var(--primary-green);
            color: var(--primary-green);
            font-weight: 600;
        }

        /* 2. Main Content & Efek Overlap */
        .main-content {
            background: transparent !important;
            padding-top: 90px;
            position: relative;
            z-index: 1;
        }

        .section {
            background: transparent;
            box-shadow: none;
            padding: 0 30px 30px;
        }

        .content-overlap {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            padding: 30px;
            margin-top: -60px;
            min-height: 600px;
        }

        .section-header-custom {
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header-custom h1 {
            color: var(--text-dark) !important;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: #fff;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
        }
        .btn-primary:hover {
            background-color: var(--hover-green);
            border-color: var(--hover-green);
        }
        .btn-outline-primary {
            color: var(--primary-green);
            border-color: var(--primary-green);
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }

        /* ===== PERBAIKAN SIDEBAR MINIMIZE ===== */
        .sidebar-mini .sidebar-profile,
        .main-sidebar.mini .sidebar-profile {
            padding: 15px 0;
        }

        .sidebar-mini .sidebar-profile .sidebar-brand-name,
        .sidebar-mini .sidebar-profile .user-name,
        .sidebar-mini .sidebar-profile .user-role,
        .main-sidebar.mini .sidebar-profile .sidebar-brand-name,
        .main-sidebar.mini .sidebar-profile .user-name,
        .main-sidebar.mini .sidebar-profile .user-role {
            display: none !important;
        }

        .sidebar-mini .sidebar-profile .avatar-circle,
        .main-sidebar.mini .sidebar-profile .avatar-circle {
            width: 40px;
            height: 40px;
            font-size: 16px;
            margin-bottom: 0;
            border-width: 2px;
        }

        /* ===== SIDEBAR MODE MINI: ikon saja, digerakkan oleh class di body ===== */
        body.sidebar-mini .main-sidebar .sidebar-menu li a {
            justify-content: center;
            padding: 12px 0;
        }
        body.sidebar-mini .main-sidebar .sidebar-menu li a span {
            display: none;
        }
        body.sidebar-mini .main-sidebar .sidebar-menu li a i {
            margin: 0;
            width: auto;
            font-size: 18px;
        }
        body.sidebar-mini .main-sidebar .menu-header {
            display: none;
        }
            </style>
        </head>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>

        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    {{-- ID dipakai JavaScript untuk menyimpan status sidebar --}}
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg" id="sidebar-toggle-btn"><i class="fas fa-bars"></i></a></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user">
                        <div class="d-sm-none d-lg-inline-block">Hi, {{ $user->name }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" id="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                    </div>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </nav>

        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-profile">
                    <div class="sidebar-brand-name">INVENTORY GUDANG</div>
                    <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">{{ $isAdmin ? 'Admin / Owner' : 'Staff Gudang' }}</div>
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a class="nav-link {{ request()->is('admin/inventory', 'staff/inventory') ? 'active' : '' }}" href="{{ $dashboardUrl }}">
                            <i class="fas fa-fire"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    @if ($isAdmin)
                        <li class="menu-header">DATA MASTER</li>
                        <li>
                            <a class="nav-link {{ request()->is('barang', 'barang/*') ? 'active' : '' }}" href="{{ url('/barang') }}">
                                <i class="fa fa-solid fa-cubes"></i> <span>Data Barang</span>
                            </a>
                        </li>
                    @endif

                    <li class="menu-header">TRANSAKSI</li>
                    <li>
                        <a class="nav-link {{ request()->is('barang-masuk*') ? 'active' : '' }}" href="{{ url('/barang-masuk') }}">
                            <i class="fa fa-solid fa-arrow-right"></i> <span>Barang Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->is('barang-keluar*') ? 'active' : '' }}" href="{{ url('/barang-keluar') }}">
                            <i class="fa fa-solid fa-arrow-left"></i> <span>Barang Keluar</span>
                        </a>
                    </li>

                    <li class="menu-header">LAPORAN</li>
                        <li>
                            <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" href="{{ route('laporan') }}">
                                <i class="fa fa-file-alt"></i> <span>Laporan</span>
                            </a>
                        </li>

                        @if ($isAdmin)
                            <li class="menu-header">MANAJEMEN USER</li>
                            <li>
                                <a class="nav-link {{ request()->is('pengguna*') ? 'active' : '' }}" href="{{ route('pengguna') }}">
                                    <i class="fa fa-solid fa-users"></i> <span>Manajemen User</span>
                                </a>
                            </li>
                        @endif
                </ul>
            </aside>
        </div>

        <div class="main-content">
            <section class="section">
                <div class="content-overlap">
                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="footer-left">Inventaris-Gudang &copy; {{ date('Y') }}</div>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/modules/moment.min.js') }}"></script>
<script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/js/stisla.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
        // --- 1. PERSISTENSI STATUS SIDEBAR ---
        const body = $('body');
        const sidebar = $('.main-sidebar');
        const storageKey = 'simangu_sidebar_state';

        // Class mini di sidebar selalu mengikuti class di body
        function sinkronSidebar() {
            sidebar.toggleClass('mini', body.hasClass('sidebar-mini'));
        }

        // Pulihkan status terakhir saat halaman dibuka
        body.toggleClass('sidebar-mini', localStorage.getItem(storageKey) === 'mini');
        sinkronSidebar();

        // Setelah tombol toggle diklik, Stisla mengubah class di body.
        // Kita sinkronkan sidebar dan simpan statusnya.
        $(document).on('click', '#sidebar-toggle-btn', function () {
            setTimeout(function () {
                sinkronSidebar();
                localStorage.setItem(storageKey, body.hasClass('sidebar-mini') ? 'mini' : 'full');
            }, 100);
        });

        // --- 2. LOGOUT ---
        $('#btn-logout').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then(function (r) {
                if (r.isConfirmed) $('#logout-form').submit();
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>