@php
    $input = 'w-full rounded-lg border border-line bg-surface-2 px-3 py-2.5 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none';
    $label = 'mb-1.5 block text-sm font-medium text-slate-300';
    $error = 'alert-field mt-1.5 hidden text-xs text-red-400';
@endphp

<div id="modal-barang" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-2xl rounded-2xl border border-line bg-surface shadow-2xl">

        <div class="flex items-center justify-between border-b border-line px-6 py-4">
            <h3 id="modal-barang-judul" class="text-lg font-semibold text-slate-100">Tambah Barang</h3>
            <button type="button" data-close class="text-muted hover:text-slate-100" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-barang" class="px-6 py-5">
            <input type="hidden" id="barang_id_edit" value="">

            <div class="grid gap-5 md:grid-cols-2">

                {{-- Kiri --}}
                <div class="space-y-5">
                    <div>
                        <label for="barang_kode" class="{{ $label }}">Kode Barang</label>
                        <input type="text" id="barang_kode" name="kode" placeholder="Contoh: PLT-304-2"
                               class="{{ $input }} uppercase">
                        <p id="alert-kode" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="barang_nama" class="{{ $label }}">Nama Barang</label>
                        <input type="text" id="barang_nama" name="nama" placeholder="Masukkan nama barang" class="{{ $input }}">
                        <p id="alert-nama" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="barang_kategori" class="{{ $label }}">Kategori</label>
                        <select id="barang_kategori" name="kategori" class="{{ $input }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                        <p id="alert-kategori" class="{{ $error }}"></p>
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-5">
                    <div>
                        <label for="barang_satuan" class="{{ $label }}">Satuan</label>
                        <select id="barang_satuan" name="satuan" class="{{ $input }}">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuanList as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                        <p id="alert-satuan" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="barang_stok_minimum" class="{{ $label }}">Stok Minimum</label>
                        <input type="number" id="barang_stok_minimum" name="stok_minimum" min="0" inputmode="numeric"
                               placeholder="Misal: 10" class="{{ $input }}">
                        <p id="alert-stok_minimum" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="barang_lokasi" class="{{ $label }}">Lokasi / Rak <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="barang_lokasi" name="lokasi" placeholder="Contoh: Rak A1" maxlength="50" class="{{ $input }}">
                        <p id="alert-lokasi" class="{{ $error }}"></p>
                    </div>

                    <p class="text-xs text-muted">Stok awal diisi lewat menu Barang Masuk.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-line pt-5">
                <button type="button" data-close
                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-surface-2">
                    Keluar
                </button>
                <button type="submit" id="btn-simpan"
                        class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white disabled:opacity-50">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>