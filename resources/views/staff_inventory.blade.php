@extends('layouts.app')

@section('title', 'Stok Barang')
@section('subtitle', 'Cek stok harian dan lokasi barang')

@section('content')
@php
    $habis       = $barang->where('stok', 0)->count();
    $hampirHabis = $barang->filter(fn ($b) => $b->stok > 0 && $b->stok <= $b->stok_minimum)->count();
@endphp

{{-- Kartu ringkasan --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Total Jenis Barang</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $barang->count() }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Hampir Habis</p>
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
        </div>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $hampirHabis }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Stok Habis</p>
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600">
                <i class="fa-solid fa-circle-xmark"></i>
            </span>
        </div>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $habis }}</p>
    </div>
</div>

{{-- Tabel stok --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
        <h2 class="font-semibold text-slate-900">Daftar Stok</h2>
        <span class="text-sm text-slate-500">{{ $barang->count() }} barang</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3 text-right">Stok</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($barang as $b)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $b->kode }}</td>
                        <td class="px-5 py-3 text-slate-900">{{ $b->nama }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $b->lokasi ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-900">
                            {{ $b->stok }} <span class="font-normal text-slate-500">{{ $b->satuan }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if ($b->stok == 0)
                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">Habis</span>
                            @elseif ($b->stok <= $b->stok_minimum)
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">Hampir Habis</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection