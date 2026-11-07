@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('subtitle', 'Riwayat penerimaan barang di gudang')

@section('content')
<div id="notif" class="fixed right-6 top-20 z-[60] hidden rounded-lg px-4 py-3 text-sm font-medium shadow-lg"></div>

<div class="mb-4 flex justify-end">
    <button type="button" id="btn-tambah"
            class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white">
        <i class="fa-solid fa-plus"></i> Barang Masuk
    </button>
</div>

<div class="overflow-hidden rounded-xl border border-line bg-surface">
    <div class="flex flex-col gap-3 border-b border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <input id="cari" type="search" placeholder="Cari kode, barang, atau supplier..."
               class="w-full rounded-lg border border-line bg-surface-2 px-3 py-2 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none sm:w-80">
        <span id="jumlah-data" class="text-sm text-muted"></span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-surface-2 text-left text-xs uppercase tracking-wide text-muted">
                <tr>
                    <th class="px-5 py-3 font-semibold">No</th>
                    <th class="px-5 py-3 font-semibold">Kode Transaksi</th>
                    <th class="px-5 py-3 font-semibold">Tanggal Masuk</th>
                    <th class="px-5 py-3 font-semibold">Nama Barang</th>
                    <th class="px-5 py-3 text-right font-semibold">Stok Masuk</th>
                    <th class="px-5 py-3 font-semibold">Supplier</th>
                    <th class="px-5 py-3 font-semibold">Opsi</th>
                </tr>
            </thead>
            <tbody id="tbody-data" class="divide-y divide-line"></tbody>
        </table>
    </div>
</div>

@include('barang-masuk.create', [
    'barangs'       => $barangs,
    'suppliers'     => $suppliers,
    'kodeTransaksi' => $kodeTransaksi,
])
@endsection

@push('scripts')
<script>
(function () {
    const csrf      = document.querySelector('meta[name="csrf-token"]').content;
    const modal     = document.getElementById('modal-barang-masuk');
    const form      = document.getElementById('form-barang-masuk');
    const btnSimpan = document.getElementById('btn-simpan');
    const tbody     = document.getElementById('tbody-data');
    const cari      = document.getElementById('cari');
    const pilihBarang = document.getElementById('barang_id');
    const stokEl    = document.getElementById('stok');
    const satuanEl  = document.getElementById('satuan_text');
    let semua = [];

    function esc(s) {
        return String(s ?? '-').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    function notif(pesan, jenis = 'sukses') {
        const el = document.getElementById('notif');
        el.textContent = pesan;
        el.className = 'fixed right-6 top-20 z-[60] rounded-lg px-4 py-3 text-sm font-medium shadow-lg ' +
            (jenis === 'sukses' ? 'bg-brand text-app' : 'bg-red-600 text-white');
        setTimeout(() => el.classList.add('hidden'), 2500);
    }

    function render() {
        const kata  = cari.value.toLowerCase();
        const hasil = semua.filter(i =>
            (i.kode_transaksi + ' ' + i.nama_barang + ' ' + (i.supplier ?? '')).toLowerCase().includes(kata)
        );

        document.getElementById('jumlah-data').textContent = hasil.length + ' data';

        tbody.innerHTML = hasil.length
            ? hasil.map((i, n) => `
                <tr class="hover:bg-surface-2">
                    <td class="px-5 py-3 text-muted">${n + 1}</td>
                    <td class="px-5 py-3 font-medium text-slate-300">${esc(i.kode_transaksi)}</td>
                    <td class="px-5 py-3 text-slate-400">${esc(i.tanggal)}</td>
                    <td class="px-5 py-3 text-slate-100">${esc(i.nama_barang)}</td>
                    <td class="px-5 py-3 text-right font-semibold text-brand">+${esc(i.jumlah)}</td>
                    <td class="px-5 py-3 text-slate-400">${esc(i.supplier)}</td>
                    <td class="px-5 py-3">
                        <button type="button" data-hapus="${i.id}"
                                class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                    </td>
                </tr>`).join('')
            : `<tr><td colspan="7" class="px-5 py-10 text-center text-muted">Belum ada data barang masuk.</td></tr>`;
    }

    async function muatData() {
        const res = await fetch('/barang-masuk/get-data', { headers: { 'Accept': 'application/json' } });
        semua = (await res.json()).data;
        render();
    }

    function bukaModal() {
        form.reset();
        pilihBarang.value = '';
        stokEl.value = '';
        satuanEl.textContent = '-';
        document.getElementById('tanggal_masuk').value = new Date().toISOString().slice(0, 10);
        bersihkanError();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function bersihkanError() {
        document.querySelectorAll('.alert-field').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    function pesanError(data) {
        if (data && data.errors) return Object.values(data.errors)[0][0];
        if (data && data.message) return data.message;
        return 'Terjadi kesalahan pada server.';
    }

    // Stok saat ini saat barang dipilih
    pilihBarang.addEventListener('change', async function () {
        if (!this.value) {
            stokEl.value = '';
            satuanEl.textContent = '-';
            return;
        }
        const res = await fetch('/barang-masuk/get-barang-detail?barang_id=' + this.value, {
            headers: { 'Accept': 'application/json' }
        });
        const d = await res.json();
        stokEl.value = d.stok;
        satuanEl.textContent = d.satuan;
    });

    // Simpan
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        bersihkanError();
        btnSimpan.disabled = true;
        btnSimpan.textContent = 'Menyimpan...';

        try {
            const res = await fetch('/barang-masuk', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: new FormData(form)
            });
            const data = await res.json();

            if (res.ok) {
                tutupModal();
                notif(data.message);
                muatData();
            } else if (res.status === 422 && data.errors) {
                Object.entries(data.errors).forEach(([field, msgs]) => {
                    const el = document.getElementById('alert-' + field);
                    if (el) {
                        el.textContent = msgs[0];
                        el.classList.remove('hidden');
                    }
                });
            } else {
                notif(pesanError(data), 'gagal');
            }
        } catch (err) {
            notif('Tidak bisa terhubung ke server.', 'gagal');
        } finally {
            btnSimpan.disabled = false;
            btnSimpan.textContent = 'Tambah';
        }
    });

    // Hapus
    tbody.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-hapus]');
        if (!btn) return;
        if (!confirm('Data akan dihapus. Lanjutkan?')) return;

        const res = await fetch('/barang-masuk/' + btn.dataset.hapus, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        });
        const data = await res.json();

        if (res.ok) {
            notif(data.message);
            muatData();
        } else {
            notif(pesanError(data), 'gagal');
        }
    });

    // Tutup modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal || e.target.closest('[data-close]')) tutupModal();
    });

    document.getElementById('btn-tambah').addEventListener('click', bukaModal);
    cari.addEventListener('input', render);

    // Dibuka dari dashboard: /barang-masuk?tambah=1
    if (new URLSearchParams(window.location.search).get('tambah') === '1') {
        bukaModal();
    }

    muatData();
})();
</script>
@endpush