@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('subtitle', 'Stok awal 0. Isi stok masuk lewat menu Barang Masuk.')

@section('content')
@php
    $label = 'mb-1.5 block text-sm font-medium text-slate-700';
    $input = 'w-full rounded-lg border px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100';
    $borderOk    = 'border-slate-300';
    $borderError = 'border-red-400';
    $hint  = 'mt-1 text-xs text-slate-500';
    $error = 'mt-1 text-xs text-red-600';
    $satuanList = ['lembar', 'batang', 'pcs', 'kg', 'meter', 'rol', 'lainnya'];
    $kategoriSaran = $kategoriSaran ?? collect(['Bahan baku', 'Pengikat', 'Komponen', 'Elektronik', 'Lainnya']);
@endphp

<form method="POST" action="{{ route('barang.store') }}" novalidate>
    @csrf

    {{-- Aksi --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ url('/admin/inventory') }}" class="text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke daftar barang
        </a>
        <div class="flex gap-2">
            <a href="{{ url('/admin/inventory') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Barang
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            Ada {{ $errors->count() }} isian yang perlu diperbaiki sebelum disimpan.
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Kolom kiri: informasi barang --}}
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 font-semibold text-slate-900">Informasi Barang</h2>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="nama" class="{{ $label }}">Nama Barang <span class="text-red-600">*</span></label>
                        <input id="nama" name="nama" type="text" value="{{ old('nama') }}"
                               placeholder="Contoh: Plat stainless 304 tebal 2 mm"
                               class="{{ $input }} {{ $errors->has('nama') ? $borderError : $borderOk }}" required>
                        @error('nama') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kode" class="{{ $label }}">Kode / SKU <span class="text-red-600">*</span></label>
                        <input id="kode" name="kode" type="text" value="{{ old('kode') }}"
                               placeholder="Contoh: PLT-304-2"
                               class="{{ $input }} uppercase {{ $errors->has('kode') ? $borderError : $borderOk }}" required>
                        @error('kode')
                            <p class="{{ $error }}">{{ $message }}</p>
                        @else
                            <p class="{{ $hint }}">Harus unik, tidak boleh sama dengan barang lain.</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kategori" class="{{ $label }}">Kategori</label>
                        <input id="kategori" name="kategori" type="text" list="daftarKategori"
                               value="{{ old('kategori') }}" placeholder="Pilih atau ketik baru"
                               class="{{ $input }} {{ $errors->has('kategori') ? $borderError : $borderOk }}">
                        <datalist id="daftarKategori">
                            @foreach ($kategoriSaran as $k)
                                <option value="{{ $k }}">
                            @endforeach
                        </datalist>
                        @error('kategori') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="satuan" class="{{ $label }}">Satuan <span class="text-red-600">*</span></label>
                        <select id="satuan" name="satuan"
                                class="{{ $input }} bg-white {{ $errors->has('satuan') ? $borderError : $borderOk }}" required>
                            <option value="">-- Pilih satuan --</option>
                            @foreach ($satuanList as $s)
                                <option value="{{ $s }}" @selected(old('satuan') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('satuan') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="{{ $label }}">Lokasi / Rak</label>
                        <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi') }}"
                               placeholder="Contoh: Rak A1"
                               class="{{ $input }} {{ $errors->has('lokasi') ? $borderError : $borderOk }}">
                        @error('lokasi') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        {{-- Kolom kanan: stok --}}
        <div class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 font-semibold text-slate-900">Pengaturan Stok</h2>

                    <div class="mb-5">
                        <label for="stok_awal" class="{{ $label }}">Stok Awal</label>
                        <input id="stok_awal" name="stok_awal" type="number" min="0" inputmode="numeric"
                                value="{{ old('stok_awal', 0) }}" class="{{ $input }} {{ $borderOk }}">
                        <p class="{{ $hint }}">Dicatat otomatis sebagai barang masuk "Stok awal".</p>
                    </div>

                <div>
                    <label for="stok_minimum" class="{{ $label }}">Stok Minimum <span class="text-red-600">*</span></label>
                    <input id="stok_minimum" name="stok_minimum" type="number" min="0" inputmode="numeric"
                           value="{{ old('stok_minimum', 0) }}"
                           class="{{ $input }} {{ $errors->has('stok_minimum') ? $borderError : $borderOk }}" required>
                    @error('stok_minimum')
                        <p class="{{ $error }}">{{ $message }}</p>
                    @else
                        <p class="{{ $hint }}">Stok di bawah atau sama dengan angka ini ditandai Hampir Habis.</p>
                    @enderror
                </div>
            </section>
        </div>
    </div>
</form>
@endsection