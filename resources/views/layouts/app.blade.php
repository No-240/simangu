@php
    $user    = auth()->user();
    $isAdmin = $user->role === 'admin';

    $menu = [
        ['label' => 'Stok Barang',   'icon' => 'fa-boxes-stacked',      'url' => $isAdmin ? url('/admin/inventory') : url('/staff/inventory'), 'aktif' => request()->is('admin/inventory', 'staff/inventory')],
        ['label' => 'Barang Masuk',  'icon' => 'fa-arrow-down-to-line', 'url' => url('/barang-masuk'),  'aktif' => request()->is('barang-masuk*')],
        ['label' => 'Barang Keluar', 'icon' => 'fa-arrow-up-from-line', 'url' => url('/barang-keluar'), 'aktif' => request()->is('barang-keluar*')],
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - SIMANGU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

{{-- Sidebar (layar besar) --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-white lg:flex">
    <a href="{{ url('/') }}" class="flex h-16 items-center gap-3 border-b border-slate-200 px-6">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo CV Jayagiry" class="h-9 w-auto">
        <div class="leading-tight">
            <p class="text-sm font-bold text-slate-900">CV JAYAGIRY</p>
            <p class="text-xs text-slate-500">SIMANGU</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 px-3 py-6">
        @foreach ($menu as $m)
            <a href="{{ $m['url'] }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                      {{ $m['aktif'] ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i class="fa-solid {{ $m['icon'] }} w-5 text-center"></i>
                {{ $m['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-200 p-4">
        <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
        <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-medium
                     {{ $isAdmin ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
            {{ $isAdmin ? 'Admin / Owner' : 'Staff Gudang' }}
        </span>
    </div>
</aside>

{{-- Area kanan: header, menu mobile, dan isi halaman --}}
<div class="lg:pl-64">

    <header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 sm:px-6">
        <div class="flex items-center gap-3 lg:hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo CV Jayagiry" class="h-8 w-auto">
            <span class="text-sm font-bold text-slate-900">SIMANGU</span>
        </div>

        <div class="ml-auto flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                <p class="text-xs text-slate-500">{{ $isAdmin ? 'Admin / Owner' : 'Staff Gudang' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Menu untuk layar kecil --}}
    <nav class="flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 lg:hidden">
        @foreach ($menu as $m)
            <a href="{{ $m['url'] }}"
               class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                      {{ $m['aktif'] ? 'bg-green-50 text-green-700' : 'text-slate-600' }}">
                <i class="fa-solid {{ $m['icon'] }} mr-1"></i>{{ $m['label'] }}
            </a>
        @endforeach
    </nav>

    <main class="px-4 py-6 sm:px-6">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">@yield('title', 'Dashboard')</h1>
            <p class="text-sm text-slate-500">@yield('subtitle', date('d M Y'))</p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>