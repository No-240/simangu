@extends('layouts.app')

@section('title', 'Catat Barang Keluar')
@section('subtitle', 'Stok berkurang otomatis setelah disimpan')

@section('content')
@php
    $label = 'mb-1.5 block text-sm font-medium text-slate-700';
    $input = 'w-full rounded-lg border px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100';
    $borderOk    = 'border-slate-300';
    $borderError = 'border-red-400';
    $error = 'mt-1 text-xs text-red-600';
@endphp

<form method="POST" action="{{ route('barang-keluar.store') }}" novalidate>
    @csrf

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('barang-keluar.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke riwayat
        </a>
        <div class="flex gap-2">
            <a href="{{ route('barang-keluar.index') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" id="tombol-simpan"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50">
                <i class="fa-solid fa-floppy-disk"></i> Proses Keluar
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Kolom kiri: data transaksi --}}
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 font-semibold text-slate-900">Data Pengeluaran</h2>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="barang_id" class="{{ $label }}">Barang <span class="text-red-600">*</span></label>
                        <select id="barang_id" name="barang_id"
                                class="{{ $input }} bg-white {{ $errors->has('barang_id') ? $borderError : $borderOk }}" required>
                            <option value="">-- Pilih barang --</option>
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}"
                                        data-stok="{{ $b->stok }}"
                                        data-satuan="{{ $b->satuan }}"
                                        @selected(old('barang_id') == $b->id)>
                                    {{ $b->kode }} - {{ $b->nama }} (stok: {{ $b->stok }} {{ $b->satuan }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="jumlah" class="{{ $label }}">Jumlah Keluar <span class="text-red-600">*</span></label>
                        <input id="jumlah" name="jumlah" type="number" min="1" inputmode="numeric"
                               value="{{ old('jumlah') }}"
                               class="{{ $input }} {{ $errors->has('jumlah') ? $borderError : $borderOk }}" required>
                        @error('jumlah') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="{{ $label }}">Tanggal <span class="text-red-600">*</span></label>
                        <input id="tanggal" name="tanggal" type="date"
                               value="{{ old('tanggal', now()->toDateString()) }}"
                               max="{{ now()->toDateString() }}"
                               class="{{ $input }} {{ $errors->has('tanggal') ? $borderError : $borderOk }}" required>
                        @error('tanggal') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="keperluan" class="{{ $label }}">Keperluan</label>
                        <input id="keperluan" name="keperluan" type="text" maxlength="150"
                               value="{{ old('keperluan') }}"
                               placeholder="Contoh: Pesanan MTO-2026-014 / Produksi tangki"
                               class="{{ $input }} {{ $errors->has('keperluan') ? $borderError : $borderOk }}">
                        @error('keperluan') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 font-semibold text-slate-900">Penerima</h2>
                <div class="grid gap-5">
                    <div>
                        <label for="penerima" class="{{ $label }}">Nama Penerima</label>
                        <input id="penerima" name="penerima" type="text" maxlength="100"
                               value="{{ old('penerima') }}"
                               placeholder="Nama orang yang mengambil barang"
                               class="{{ $input }} {{ $errors->has('penerima') ? $borderError : $borderOk }}">
                        @error('penerima') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="keterangan" class="{{ $label }}">Keterangan <span class="text-slate-400">(opsional)</span></label>
                        <textarea id="keterangan" name="keterangan" rows="3" maxlength="500"
                                  class="{{ $input }} {{ $errors->has('keterangan') ? $borderError : $borderOk }}">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        {{-- Kolom kanan: pratinjau stok --}}
        <div class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 font-semibold text-slate-900">Pratinjau Stok</h2>

                <div class="space-y-4 rounded-lg bg-slate-50 p-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Stok sekarang</span>
                        <span id="stok-sekarang" class="font-semibold text-slate-900">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Keluar</span>
                        <span id="stok-keluar" class="font-semibold text-slate-900">-</span>
                    </div>
                    <div class="border-t border-slate-200 pt-4 flex justify-between">
                        <span class="text-slate-600">Sisa setelah keluar</span>
                        <span id="stok-sisa" class="font-semibold text-slate-900">-</span>
                    </div>
                </div>

                <p id="info-stok" class="mt-4 text-sm text-slate-500">Pilih barang untuk melihat stok.</p>
            </section>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var pilih    = document.getElementById('barang_id');
        var jumlah   = document.getElementById('jumlah');
        var tombol   = document.getElementById('tombol-simpan');
        var stokNow  = document.getElementById('stok-sekarang');
        var stokOut  = document.getElementById('stok-keluar');
        var stokSisa = document.getElementById('stok-sisa');
        var info     = document.getElementById('info-stok');

        function perbarui() {
            var opsi = pilih.options[pilih.selectedIndex];

            if (!opsi || !opsi.value) {
                stokNow.textContent = stokOut.textContent = stokSisa.textContent = '-';
                info.textContent = 'Pilih barang untuk melihat stok.';
                tombol.disabled = false;
                return;
            }

            var stok   = parseInt(opsi.dataset.stok, 10) || 0;
            var satuan = opsi.dataset.satuan || '';
            var j      = parseInt(jumlah.value, 10) || 0;
            var sisa   = stok - j;

            stokNow.textContent  = stok + ' ' + satuan;
            stokOut.textContent  = (j > 0 ? j : 0) + ' ' + satuan;
            stokSisa.textContent = sisa + ' ' + satuan;

            if (j > stok) {
                stokSisa.classList.replace('text-slate-900', 'text-red-600');
                info.className = 'mt-4 text-sm text-red-600';
                info.textContent = 'Jumlah melebihi stok yang tersedia.';
                tombol.disabled = true;
            } else {
                stokSisa.classList.replace('text-red-600', 'text-slate-900');
                info.className = 'mt-4 text-sm text-slate-500';
                info.textContent = j > 0 ? 'Stok mencukupi.' : 'Masukkan jumlah keluar.';
                tombol.disabled = false;
            }
        }

        pilih.addEventListener('change', perbarui);
        jumlah.addEventListener('input', perbarui);
        perbarui();
    });
</script>
@endsection