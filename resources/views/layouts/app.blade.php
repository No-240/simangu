@php
    $user         = auth()->user();
    $isAdmin      = $user->role === 'admin';
    $dashboardUrl = $isAdmin ? url('/admin/inventory') : url('/staff/inventory');

    $linkOn  = 'bg-brand/15 text-brand';
    $linkOff = 'text-muted hover:bg-surface-2 hover:text-slate-100';
    $linkBase = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition';
@endphp
<!DOCTYPE html>
<html lang="id" data-sidebar="full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIMANGU</title>

    {{-- Terapkan status sidebar sebelum halaman tampil, supaya tidak berkedip --}}
    <script>
        document.documentElement.dataset.sidebar = localStorage.getItem('simangu_sidebar') || 'full';
    </script>

    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- jQuery dan SweetAlert dipakai halaman transaksi dan data barang sampai ditulis ulang --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('head')
</head>
<body class="font-sans antialiased">

<div class="min-h-screen">

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-line bg-surface transition-all duration-200">
        <div class="flex h-16 items-center gap-3 border-b border-line px-5">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand font-bold text-app">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div class="label min-w-0">
                <p class="truncate text-sm font-semibold text-slate-100">{{ $user->name }}</p>
                <p class="text-xs text-muted">{{ $isAdmin ? 'Admin / Owner' : 'Staff Gudang' }}</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <a href="{{ $dashboardUrl }}"
               class="{{ $linkBase }} {{ request()->is('admin/inventory', 'staff/inventory') ? $linkOn : $linkOff }}">
                <i class="fa-solid fa-fire w-5 text-center"></i>
                <span class="label">Dashboard</span>
            </a>

            @if ($isAdmin)
                <p class="label px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-muted">Data Master</p>
                <a href="{{ url('/barang') }}"
                   class="{{ $linkBase }} {{ request()->is('barang', 'barang/*') ? $linkOn : $linkOff }}">
                    <i class="fa-solid fa-cubes w-5 text-center"></i>
                    <span class="label">Data Barang</span>
                </a>
            @endif

            <p class="label px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-muted">Transaksi</p>
            <a href="{{ url('/barang-masuk') }}"
               class="{{ $linkBase }} {{ request()->is('barang-masuk*') ? $linkOn : $linkOff }}">
                <i class="fa-solid fa-arrow-down w-5 text-center"></i>
                <span class="label">Barang Masuk</span>
            </a>
            <a href="{{ url('/barang-keluar') }}"
               class="{{ $linkBase }} {{ request()->is('barang-keluar*') ? $linkOn : $linkOff }}">
                <i class="fa-solid fa-arrow-up w-5 text-center"></i>
                <span class="label">Barang Keluar</span>
            </a>

            <p class="label px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-muted">Laporan</p>
            <a href="{{ route('laporan') }}"
               class="{{ $linkBase }} {{ request()->is('laporan*') ? $linkOn : $linkOff }}">
                <i class="fa-solid fa-file-lines w-5 text-center"></i>
                <span class="label">Laporan</span>
            </a>

            @if ($isAdmin)
                <p class="label px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-muted">Manajemen User</p>
                <a href="{{ route('pengguna') }}"
                   class="{{ $linkBase }} {{ request()->is('pengguna*') ? $linkOn : $linkOff }}">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span class="label">Manajemen User</span>
                </a>
            @endif
        </nav>
    </aside>

    {{-- Area kanan --}}
    <div id="main" class="ml-64 min-h-screen transition-all duration-200">

        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-line bg-surface/90 px-6 backdrop-blur">
            <button id="toggle-sidebar" type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-muted hover:bg-surface-2 hover:text-slate-100"
                    aria-label="Buka atau tutup sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Yakin ingin keluar?')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </header>

        <main class="p-6">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-brand/30 bg-brand/10 px-4 py-3 text-sm text-brand">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-xl font-semibold text-slate-100">@yield('title', 'Dashboard')</h1>
                <p class="text-sm text-muted">@yield('subtitle', now()->translatedFormat('l, d F Y'))</p>
            </div>

            @yield('content')
        </main>

        <footer class="border-t border-line px-6 py-4 text-xs text-muted">
            Inventaris Gudang &copy; {{ date('Y') }}
        </footer>
    </div>
</div>

<script>
    document.getElementById('toggle-sidebar').addEventListener('click', function () {
        var next = document.documentElement.dataset.sidebar === 'mini' ? 'full' : 'mini';
        document.documentElement.dataset.sidebar = next;
        localStorage.setItem('simangu_sidebar', next);
    });
</script>

@stack('scripts')
</body>
</html>v