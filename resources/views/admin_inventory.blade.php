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

    $sapaan = now()->hour < 11 ? 'Selamat pagi' : (now()->hour < 15 ? 'Selamat siang' : (now()->hour < 18 ? 'Selamat sore' : 'Selamat malam'));
@endphp

<div class="section-header-custom">
    <h1>{{ $sapaan }}, {{ auth()->user()->name }}!</h1>
    <div class="ml-auto">
        <a href="{{ url('/barang-masuk?tambah=1') }}" class="btn btn-primary">
            <i class="fa fa-arrow-down"></i> Catat Barang Masuk
        </a>
        <a href="{{ url('/barang-keluar?tambah=1') }}" class="btn btn-outline-primary">
            <i class="fa fa-arrow-up"></i> Catat Barang Keluar
        </a>
    </div>
</div>

{{-- Kartu statistik --}}
<div class="row">
    @foreach ([
        ['label' => 'Total Jenis Barang', 'nilai' => $totalJenis, 'ikon' => 'fa-boxes',                'warna' => 'bg-primary'],
        ['label' => 'Stok Aman',          'nilai' => $aman,       'ikon' => 'fa-check-circle',         'warna' => 'bg-success'],
        ['label' => 'Hampir Habis',       'nilai' => $hampir,     'ikon' => 'fa-exclamation-triangle', 'warna' => 'bg-warning'],
        ['label' => 'Stok Habis',         'nilai' => $habis,      'ikon' => 'fa-times-circle',         'warna' => 'bg-danger'],
    ] as $k)
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon {{ $k['warna'] }}">
                    <i class="fas {{ $k['ikon'] }}"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header"><h4>{{ $k['label'] }}</h4></div>
                    <div class="card-body">{{ $k['nilai'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Grafik --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h4>Jumlah Jenis Barang per Kategori</h4></div>
            <div class="card-body">
                <canvas id="chartKategori" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h4>Status Stok</h4></div>
            <div class="card-body">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Perlu perhatian dan kategori --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h4>Perlu Perhatian</h4></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok</th>
                                <th>Min.</th>
                                <th style="width: 160px">Kondisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($perluPerhatian as $b)
                                @php
                                    $persen = $b->stok_minimum > 0 ? min(100, round($b->stok / $b->stok_minimum * 100)) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $b->nama }}</strong><br>
                                        <small class="text-muted">{{ $b->kode }}</small>
                                    </td>
                                    <td>{{ $b->stok }} {{ $b->satuan }}</td>
                                    <td>{{ $b->stok_minimum }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px">
                                            <div class="progress-bar {{ $b->stok == 0 ? 'bg-danger' : 'bg-warning' }}" style="width: {{ $persen }}%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($b->stok == 0)
                                            <span class="badge badge-danger">Habis</span>
                                        @else
                                            <span class="badge badge-warning">Hampir Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Semua stok dalam kondisi aman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h4>Per Kategori</h4></div>
            <div class="card-body">
                @forelse ($perKategori as $nama => $jumlah)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $nama }}</span>
                            <span class="font-weight-bold">{{ $jumlah }}</span>
                        </div>
                        <div class="progress" style="height: 8px">
                            <div class="progress-bar bg-success" style="width: {{ $jumlah / max($perKategori->max(), 1) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada data barang.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Semua barang --}}
<div class="card">
    <div class="card-header"><h4>Semua Barang</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel_dashboard" class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Stok</th>
                        <th>Min.</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barang as $b)
                        @php
                            $status = $b->stok == 0 ? 'habis' : ($b->stok <= $b->stok_minimum ? 'hampir' : 'aman');
                        @endphp
                        <tr>
                            <td>{{ $b->kode }}</td>
                            <td>{{ $b->nama }}</td>
                            <td>{{ $b->kategori ?? '-' }}</td>
                            <td>{{ $b->lokasi ?? '-' }}</td>
                            <td>{{ $b->stok }} {{ $b->satuan }}</td>
                            <td>{{ $b->stok_minimum }}</td>
                            <td>
                                @if ($status === 'habis')
                                    <span class="badge badge-danger">Habis</span>
                                @elseif ($status === 'hampir')
                                    <span class="badge badge-warning">Hampir Habis</span>
                                @else
                                    <span class="badge badge-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
$(function () {
    $('#tabel_dashboard').DataTable({
        order: [[4, 'asc']]
    });

    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: @json($perKategori->keys()->values()->all()),
            datasets: [{
                label: 'Jumlah jenis',
                data: @json($perKategori->values()->all()),
                backgroundColor: '#2fbf71',
                borderRadius: 6
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Aman', 'Hampir Habis', 'Habis'],
            datasets: [{
                data: [{{ $aman }}, {{ $hampir }}, {{ $habis }}],
                backgroundColor: ['#1e8f4e', '#f0ad4e', '#e74c3c']
            }]
        },
        options: { cutout: '70%' }
    });
});
</script>
@endpush