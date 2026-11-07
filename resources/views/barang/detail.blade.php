<div id="modal-detail-barang" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-md rounded-2xl border border-line bg-surface shadow-2xl">

        <div class="flex items-center justify-between border-b border-line px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-100">Detail Barang</h3>
            <button type="button" data-close class="text-muted hover:text-slate-100" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <dl class="divide-y divide-line px-6 py-2 text-sm">
            <div class="flex justify-between py-3">
                <dt class="text-muted">Kode Barang</dt>
                <dd id="detail_kode" class="font-medium text-slate-100">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Nama Barang</dt>
                <dd id="detail_nama" class="font-medium text-slate-100">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Kategori</dt>
                <dd id="detail_kategori" class="text-slate-200">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Satuan</dt>
                <dd id="detail_satuan" class="text-slate-200">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Lokasi / Rak</dt>
                <dd id="detail_lokasi" class="text-slate-200">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Stok Minimum</dt>
                <dd id="detail_stok_minimum" class="text-slate-200">-</dd>
            </div>
            <div class="flex justify-between py-3">
                <dt class="text-muted">Stok Saat Ini</dt>
                <dd id="detail_stok" class="font-semibold text-brand">-</dd>
            </div>
        </dl>

        <div class="flex justify-end border-t border-line px-6 py-4">
            <button type="button" data-close
                    class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-surface-2">
                Tutup
            </button>
        </div>
    </div>
</div>