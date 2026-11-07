@php
    $input = 'w-full rounded-lg border border-line bg-surface-2 px-3 py-2.5 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none';
    $label = 'mb-1.5 block text-sm font-medium text-slate-300';
    $error = 'alert-field mt-1.5 hidden text-xs text-red-400';
@endphp

<div id="modal-barang-keluar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-2xl rounded-2xl border border-line bg-surface shadow-2xl">

        <div class="flex items-center justify-between border-b border-line px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-100">Tambah Barang Keluar</h3>
            <button type="button" data-close class="text-muted hover:text-slate-100" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-barang-keluar" class="px-6 py-5">
            <div class="grid gap-5 md:grid-cols-2">

                {{-- Kiri --}}
                <div class="space-y-5">
                    <div>
                        <label for="tanggal_keluar" class="{{ $label }}">Tanggal Keluar</label>
                        <input type="date" id="tanggal_keluar" name="tanggal" class="{{ $input }}"
                               max="{{ now()->toDateString() }}">
                        <p id="alert-tanggal" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="kode_transaksi" class="{{ $label }}">Kode Transaksi</label>
                        <input type="text" id="kode_transaksi" value="{{ $kodeTransaksi }}" readonly
                               class="{{ $input }} text-muted">
                    </div>

                    <div>
                        <label for="stok" class="{{ $label }}">Stok Saat Ini</label>
                        <input type="number" id="stok" disabled class="{{ $input }} text-muted">
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-5">
                    <div>
                        <label for="barang_id" class="{{ $label }}">Pilih Barang</label>
                        <select id="barang_id" name="barang_id" class="{{ $input }}">
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->id }}">{{ $b->kode }} - {{ $b->nama }}</option>
                            @endforeach
                        </select>
                        <p id="alert-barang_id" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="penerima" class="{{ $label }}">Penerima</label>
                        <input type="text" id="penerima" name="penerima" list="daftar-penerima"
                               placeholder="Pilih atau ketik nama penerima" maxlength="100" class="{{ $input }}">
                        <datalist id="daftar-penerima">
                            @foreach ($penerima as $p)
                                <option value="{{ $p }}">
                            @endforeach
                        </datalist>
                        <p id="alert-penerima" class="{{ $error }}"></p>
                    </div>

                    <div>
                        <label for="jumlah" class="{{ $label }}">Jumlah Keluar</label>
                        <div class="flex">
                            <input type="number" id="jumlah" name="jumlah" min="1" inputmode="numeric"
                                   class="{{ $input }} rounded-r-none">
                            <span id="satuan_text"
                                  class="inline-flex items-center rounded-r-lg border border-l-0 border-line bg-surface-2 px-3 text-sm text-muted">-</span>
                        </div>
                        <p id="alert-jumlah" class="{{ $error }}"></p>
                    </div>
                </div>

                {{-- Lebar penuh --}}
                <div class="md:col-span-2">
                    <label for="keperluan" class="{{ $label }}">Keperluan <span class="text-muted">(opsional, mis. nomor pesanan MTO)</span></label>
                    <input type="text" id="keperluan" name="keperluan" maxlength="150" class="{{ $input }}"
                           placeholder="Contoh: Pesanan MTO-2026-014">
                    <p id="alert-keperluan" class="{{ $error }}"></p>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="{{ $label }}">Keterangan <span class="text-muted">(opsional)</span></label>
                    <textarea id="keterangan" name="keterangan" rows="2" maxlength="500" class="{{ $input }}"></textarea>
                    <p id="alert-keterangan" class="{{ $error }}"></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-line pt-5">
                <button type="button" data-close
                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-surface-2">
                    Keluar
                </button>
                <button type="submit" id="btn-simpan"
                        class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white disabled:opacity-50">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>