@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $totalJenis = $barang->count();
    $habis      = $barang->where('stok', 0)->count();
    $hampir     = $barang->filter(fn ($b) => $b->stok > 0 && $b->stok <= $b->stok_minimum)->count();
    $aman       = $totalJenis - $habis - $hampir;
@endphp

{{-- Tombol cepat --}}
<div class="mb-6 flex flex-wrap gap-3">
    <a href="{{ url('/barang-masuk?tambah=1') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white">
        <i class="fa-solid fa-arrow-down"></i> Catat Barang Masuk
    </a>
    <a href="{{ url('/barang-keluar?tambah=1') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-slate-200 hover:border-brand hover:text-brand">
        <i class="fa-solid fa-arrow-up"></i> Catat Barang Keluar
    </a>
</div>

{{-- Kartu statistik --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ([
        ['label' => 'Total Jenis Barang', 'nilai' => $totalJenis, 'ikon' => 'fa-boxes-stacked',        'warna' => 'bg-indigo-500/15 text-indigo-300'],
        ['label' => 'Stok Aman',          'nilai' => $aman,       'ikon' => 'fa-circle-check',         'warna' => 'bg-brand/15 text-brand'],
        ['label' => 'Hampir Habis',       'nilai' => $hampir,     'ikon' => 'fa-triangle-exclamation', 'warna' => 'bg-amber-500/15 text-amber-300'],
    ] as $k)
        <div class="rounded-xl border border-line bg-surface p-5">
            <div class="flex items-start justify-between">
                <p class="text-sm text-muted">{{ $k['label'] }}</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-full {{ $k['warna'] }}">
                    <i class="fa-solid {{ $k['ikon'] }}"></i>
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-100">{{ $k['nilai'] }}</p>
        </div>
    @endforeach
</div>

{{-- Daftar stok --}}
<div class="overflow-hidden rounded-xl border border-line bg-surface">
    <div class="flex flex-col gap-3 border-b border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-semibold text-slate-100">Daftar Stok</h2>
            <p class="text-xs text-muted">Cek stok dan lokasi barang sebelum mengambil</p>
        </div>
        <div class="flex gap-2">
            <input id="cari" type="search" placeholder="Cari kode atau nama..."
                   class="w-full rounded-lg border border-line bg-surface-2 px-3 py-2 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none sm:w-64">
            <select id="filterStatus" class="rounded-lg border border-line bg-surface-2 px-3 py-2 text-sm text-slate-100 focus:border-brand focus:outline-none">
                <option value="semua">Semua status</option>
                <option value="aman">Aman</option>
                <option value="hampir">Hampir habis</option>
                <option value="habis">Habis</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="tabelStok" class="min-w-full text-sm">
            <thead class="bg-surface-2 text-left text-xs uppercase tracking-wide text-muted">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode</th>
                    <th class="px-5 py-3 font-semibold">Nama</th>
                    <th class="px-5 py-3 font-semibold">Lokasi</th>
                    <th class="px-5 py-3 text-right font-semibold">Stok</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($barang as $b)
                    @php
                        $status = $b->stok == 0 ? 'habis' : ($b->stok <= $b->stok_minimum ? 'hampir' : 'aman');
                    @endphp
                    <tr class="hover:bg-surface-2" data-status="{{ $status }}" data-cari="{{ strtolower($b->kode . ' ' . $b->nama) }}">
                        <td class="px-5 py-3 font-medium text-slate-300">{{ $b->kode }}</td>
                        <td class="px-5 py-3 text-slate-100">{{ $b->nama }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $b->lokasi ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-100">
                            {{ $b->stok }} <span class="font-normal text-muted">{{ $b->satuan }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if ($status === 'habis')
                                <span class="rounded-full bg-red-500/15 px-2.5 py-1 text-xs font-semibold text-red-300">Habis</span>
                            @elseif ($status === 'hampir')
                                <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-semibold text-amber-300">Hampir Habis</span>
                            @else
                                <span class="rounded-full bg-brand/15 px-2.5 py-1 text-xs font-semibold text-brand">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-muted">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush