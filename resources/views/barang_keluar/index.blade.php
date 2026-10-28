@extends('layouts.app')

@section('title', 'Riwayat Barang Keluar')
@section('subtitle', 'Catatan pengeluaran barang dari gudang')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('barang-keluar.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
        <i class="fa-solid fa-arrow-up-from-line"></i> Catat Barang Keluar
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Barang</th>
                    <th class="px-5 py-3 text-right">Jumlah</th>
                    <th class="px-5 py-3">Keperluan</th>
                    <th class="px-5 py-3">Penerima</th>
                    <th class="px-5 py-3">Dicatat oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($riwayat as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 whitespace-nowrap text-slate-600">{{ $r->tanggal->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-900">{{ $r->barang->nama }}</p>
                            <p class="text-xs text-slate-500">{{ $r->barang->kode }}</p>
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-red-600 whitespace-nowrap">
                            -{{ $r->jumlah }} <span class="font-normal text-slate-500">{{ $r->barang->satuan }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $r->keperluan ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $r->penerima ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $r->user->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada catatan barang keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($riwayat->hasPages())
        <div class="border-t border-slate-200 px-5 py-4">
            {{ $riwayat->links() }}
        </div>
    @endif
</div>
@endsection