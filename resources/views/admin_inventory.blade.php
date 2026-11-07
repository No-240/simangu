@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $totalJenis = $barang->count();
    $habis      = $barang->where('stok', 0)->count();
    $hampir     = $barang->filter(fn ($b) => $b->stok > 0 && $b->stok <= $b->stok_minimum)->count();
    $aman       = $totalJenis - $habis - $hampir;

    $perluPerhatian = $barang->filter(fn ($b) => $b->stok <= $b->stok_minimum)->sortBy('stok')->values();

    $perKategori = $barang
        ->groupBy(fn ($b) => $b->kategori ?: 'Tanpa kategori')
        ->map->count()
        ->sortDesc();
    $maksKategori = max($perKategori->max() ?? 0, 1);
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
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([
        ['label' => 'Total Jenis Barang', 'nilai' => $totalJenis, 'ikon' => 'fa-boxes-stacked',        'warna' => 'bg-indigo-500/15 text-indigo-300'],
        ['label' => 'Stok Aman',          'nilai' => $aman,       'ikon' => 'fa-circle-check',         'warna' => 'bg-brand/15 text-brand'],
        ['label' => 'Hampir Habis',       'nilai' => $hampir,     'ikon' => 'fa-triangle-exclamation', 'warna' => 'bg-amber-500/15 text-amber-300'],
        ['label' => 'Stok Habis',         'nilai' => $habis,      'ikon' => 'fa-circle-xmark',         'warna' => 'bg-red-500/15 text-red-300'],
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

{{-- Grafik --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-xl border border-line bg-surface p-5 lg:col-span-2">
        <h2 class="mb-4 font-semibold text-slate-100">Jumlah Jenis Barang per Kategori</h2>
        <canvas id="chartKategori" height="120"></canvas>
    </div>
    <div class="rounded-xl border border-line bg-surface p-5">
        <h2 class="mb-4 font-semibold text-slate-100">Status Stok</h2>
        <canvas id="chartStatus"></canvas>
    </div>
</div>

{{-- Perlu perhatian dan per kategori --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="overflow-hidden rounded-xl border border-line bg-surface lg:col-span-2">
        <div class="border-b border-line px-5 py-4">
            <h2 class="font-semibold text-slate-100">Perlu Perhatian</h2>
            <p class="text-xs text-muted">Barang yang habis atau sudah di bawah stok minimum</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-2 text-left text-xs uppercase tracking-wide text-muted">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Barang</th>
                        <th class="px-5 py-3 font-semibold">Stok</th>
                        <th class="px-5 py-3 text-right font-semibold">Min.</th>
                        <th class="px-5 py-3 font-semibold">Kondisi</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($perluPerhatian as $b)
                        @php
                            $persen = $b->stok_minimum > 0 ? min(100, round($b->stok / $b->stok_minimum * 100)) : 0;
                        @endphp
                        <tr class="hover:bg-surface-2">
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-100">{{ $b->nama }}</p>
                                <p class="text-xs text-muted">{{ $b->kode }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-200">{{ $b->stok }} {{ $b->satuan }}</td>
                            <td class="px-5 py-3 text-right text-slate-300">{{ $b->stok_minimum }}</td>
                            <td class="px-5 py-3">
                                <div class="h-2 w-32 overflow-hidden rounded-full bg-surface-2">
                                    <div class="h-2 rounded-full {{ $b->stok == 0 ? 'bg-red-500' : 'bg-amber-400' }}" style="width: {{ $persen }}%"></div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if ($b->stok == 0)
                                    <span class="rounded-full bg-red-500/15 px-2.5 py-1 text-xs font-semibold text-red-300">Habis</span>
                                @else
                                    <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-semibold text-amber-300">Hampir Habis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-muted">Semua stok dalam kondisi aman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-line bg-surface p-5">
        <h2 class="mb-4 font-semibold text-slate-100">Per Kategori</h2>
        <div class="space-y-4">
            @forelse ($perKategori as $nama => $jumlah)
                <div>
                    <div class="mb-1.5 flex justify-between text-sm">
                        <span class="text-slate-300">{{ $nama }}</span>
                        <span class="font-semibold text-slate-100">{{ $jumlah }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-surface-2">
                        <div class="h-2 rounded-full bg-brand" style="width: {{ $jumlah / $maksKategori * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-muted">Belum ada data barang.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Semua barang --}}
<div class="overflow-hidden rounded-xl border border-line bg-surface">
    <div class="flex flex-col gap-3 border-b border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="font-semibold text-slate-100">Semua Barang</h2>
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
                    <th class="px-5 py-3 font-semibold">Kategori</th>
                    <th class="px-5 py-3 font-semibold">Lokasi</th>
                    <th class="px-5 py-3 text-right font-semibold">Stok</th>
                    <th class="px-5 py-3 text-right font-semibold">Min.</th>
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
                        <td class="px-5 py-3 text-slate-400">{{ $b->kategori ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $b->lokasi ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-100">
                            {{ $b->stok }} <span class="font-normal text-muted">{{ $b->satuan }}</span>
                        </td>
                        <td class="px-5 py-3 text-right text-slate-400">{{ $b->stok_minimum }}</td>
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
                        <td colspan="7" class="px-5 py-10 text-center text-muted">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pencarian dan filter status
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

    // Grafik
    var teks = '#94a3b8';
    var garis = 'rgba(255,255,255,0.06)';

    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->keys()->values()->all()),
            datasets: [{
                data: @json($perKategori->values()->all()),
                backgroundColor: '#22c55e',
                borderRadius: 6
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: teks }, grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0, color: teks }, grid: { color: garis } }
            }
        }
    });

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Aman', 'Hampir Habis', 'Habis'],
            datasets: [{
                data: [{{ $aman }}, {{ $hampir }}, {{ $habis }}],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '68%',
            plugins: { legend: { labels: { color: teks } } }
        }
    });
});
</script>
@endpush