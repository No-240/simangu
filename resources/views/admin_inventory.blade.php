@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan stok dan barang yang perlu diperhatikan')

@section('content')
@php
    $totalJenis   = $barang->count();
    $habis        = $barang->where('stok', 0)->count();
    $hampirHabis  = $barang->filter(fn ($b) => $b->stok > 0 && $b->stok <= $b->stok_minimum);
    $aman         = $totalJenis - $habis - $hampirHabis->count();
    $perluPerhatian = $barang->filter(fn ($b) => $b->stok <= $b->stok_minimum)->sortBy('stok');

    $perKategori = $barang
        ->groupBy(fn ($b) => $b->kategori ?: 'Tanpa kategori')
        ->map(fn ($grup) => $grup->count())
        ->sortDesc();
    $maksKategori = max($perKategori->max() ?? 0, 1);
@endphp

{{-- Aksi cepat --}}
<div class="mb-6 flex flex-wrap gap-3">
    <a href="{{ url('/barang-masuk/create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
        <i class="fa-solid fa-arrow-down-to-line"></i> Catat Barang Masuk
    </a>
    <a href="{{ url('/barang-keluar/create') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        <i class="fa-solid fa-arrow-up-from-line"></i> Catat Barang Keluar
    </a>
</div>

{{-- Kartu ringkasan --}}
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach ([
        ['label' => 'Total Jenis',  'nilai' => $totalJenis,            'ikon' => 'fa-boxes-stacked',       'warna' => 'bg-slate-100 text-slate-600'],
        ['label' => 'Aman',         'nilai' => $aman,                  'ikon' => 'fa-circle-check',        'warna' => 'bg-green-100 text-green-600'],
        ['label' => 'Hampir Habis', 'nilai' => $hampirHabis->count(),  'ikon' => 'fa-triangle-exclamation', 'warna' => 'bg-amber-100 text-amber-600'],
        ['label' => 'Habis',        'nilai' => $habis,                 'ikon' => 'fa-circle-xmark',        'warna' => 'bg-red-100 text-red-600'],
    ] as $k)
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">{{ $k['label'] }}</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $k['warna'] }}">
                    <i class="fa-solid {{ $k['ikon'] }}"></i>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $k['nilai'] }}</p>
        </div>
    @endforeach
</div>

{{-- Perlu perhatian dan kategori --}}
<div class="mb-6 grid gap-6 lg:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Perlu Perhatian</h2>
            <p class="text-xs text-slate-500">Barang yang habis atau sudah di bawah stok minimum</p>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($perluPerhatian as $b)
                <li class="flex items-center justify-between gap-4 px-5 py-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-slate-900">{{ $b->nama }}</p>
                        <p class="text-xs text-slate-500">{{ $b->kode }} · Min. {{ $b->stok_minimum }} {{ $b->satuan }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-4">
                        <p class="text-sm font-semibold {{ $b->stok == 0 ? 'text-red-600' : 'text-amber-600' }}">
                            {{ $b->stok }} {{ $b->satuan }}
                        </p>
                        <a href="{{ url('/barang-masuk/create') }}" class="text-xs font-medium text-green-700 hover:underline">Catat masuk</a>
                    </div>
                </li>
            @empty
                <li class="px-5 py-8 text-center text-sm text-slate-500">Semua stok dalam kondisi aman.</li>
            @endforelse
        </ul>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Per Kategori</h2>
            <p class="text-xs text-slate-500">Jumlah jenis barang</p>
        </div>
        <div class="space-y-4 px-5 py-5">
            @forelse ($perKategori as $nama => $jumlah)
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-slate-700">{{ $nama }}</span>
                        <span class="font-semibold text-slate-900">{{ $jumlah }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-green-600" style="width: {{ $jumlah / $maksKategori * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-slate-500">Belum ada data.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Tabel semua barang --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="font-semibold text-slate-900">Semua Barang</h2>
        <div class="flex gap-2">
            <input id="cari" type="search" placeholder="Cari kode atau nama..."
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-green-600 focus:outline-none sm:w-64">
            <select id="filterStatus" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="semua">Semua status</option>
                <option value="aman">Aman</option>
                <option value="hampir">Hampir habis</option>
                <option value="habis">Habis</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="tabelStok" class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3 text-right">Stok</th>
                    <th class="px-5 py-3 text-right">Min.</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($barang as $b)
                    @php
                        $status = $b->stok == 0 ? 'habis' : ($b->stok <= $b->stok_minimum ? 'hampir' : 'aman');
                    @endphp
                    <tr class="hover:bg-slate-50" data-status="{{ $status }}" data-cari="{{ strtolower($b->kode . ' ' . $b->nama) }}">
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $b->kode }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $b->nama }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $b->kategori ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $b->lokasi ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-900">
                            {{ $b->stok }} <span class="font-normal text-slate-500">{{ $b->satuan }}</span>
                        </td>
                        <td class="px-5 py-3 text-right text-slate-600">{{ $b->stok_minimum }}</td>
                        <td class="px-5 py-3">
                            @if ($status === 'habis')
                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">Habis</span>
                            @elseif ($status === 'hampir')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">Hampir Habis</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var cari = document.getElementById('cari');
        var filter = document.getElementById('filterStatus');
        var baris = document.querySelectorAll('#tabelStok tbody tr[data-status]');

        function terapkan() {
            var kata = cari.value.toLowerCase();
            var status = filter.value;
            baris.forEach(function (tr) {
                var cocokTeks = tr.dataset.cari.indexOf(kata) !== -1;
                var cocokStatus = status === 'semua' || tr.dataset.status === status;
                tr.classList.toggle('hidden', !(cocokTeks && cocokStatus));
            });
        }

        cari.addEventListener('input', terapkan);
        filter.addEventListener('change', terapkan);
    });
</script>
@endsection