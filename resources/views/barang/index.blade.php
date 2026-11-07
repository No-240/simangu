@extends('layouts.app')

@section('title', 'Data Barang')
@section('subtitle', 'Daftar barang yang bisa dicatat masuk dan keluar')

@section('content')
<div id="notif" class="fixed right-6 top-20 z-[60] hidden rounded-lg px-4 py-3 text-sm font-medium shadow-lg"></div>

<div class="mb-4 flex justify-end">
    <button type="button" id="btn-tambah"
            class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-app hover:bg-brand-dark hover:text-white">
        <i class="fa-solid fa-plus"></i> Tambah Barang
    </button>
</div>

<div class="overflow-hidden rounded-xl border border-line bg-surface">
    <div class="flex flex-col gap-3 border-b border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <input id="cari" type="search" placeholder="Cari kode atau nama barang..."
               class="w-full rounded-lg border border-line bg-surface-2 px-3 py-2 text-sm text-slate-100 placeholder:text-muted focus:border-brand focus:outline-none sm:w-80">
        <span id="jumlah-data" class="text-sm text-muted"></span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-surface-2 text-left text-xs uppercase tracking-wide text-muted">
                <tr>
                    <th class="px-5 py-3 font-semibold">No</th>
                    <th class="px-5 py-3 font-semibold">Kode Barang</th>
                    <th class="px-5 py-3 font-semibold">Nama Barang</th>
                    <th class="px-5 py-3 font-semibold">Kategori</th>
                    <th class="px-5 py-3 font-semibold">Satuan</th>
                    <th class="px-5 py-3 text-right font-semibold">Stok</th>
                    <th class="px-5 py-3 font-semibold">Opsi</th>
                </tr>
            </thead>
            <tbody id="tbody-data" class="divide-y divide-line"></tbody>
        </table>
    </div>
</div>

@include('barang.create', [
    'kategori'   => $kategori,
    'satuanList' => $satuanList,
])
@include('barang.detail')
@endsection

@push('scripts')
<script>
(function () {
    const csrf        = document.querySelector('meta[name="csrf-token"]').content;
    const modal       = document.getElementById('modal-barang');
    const modalDetail = document.getElementById('modal-detail-barang');
    const form        = document.getElementById('form-barang');
    const btnSimpan   = document.getElementById('btn-simpan');
    const tbody       = document.getElementById('tbody-data');
    const cari        = document.getElementById('cari');
    const judulModal  = document.getElementById('modal-barang-judul');
    const idEdit      = document.getElementById('barang_id_edit');
    let semua = [];

    function esc(s) {
        return String(s ?? '-').replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function notif(pesan, jenis) {
        const el = document.getElementById('notif');
        el.textContent = pesan;
        el.className = 'fixed right-6 top-20 z-[60] rounded-lg px-4 py-3 text-sm font-medium shadow-lg ' +
            (jenis === 'gagal' ? 'bg-red-600 text-white' : 'bg-brand text-app');
        setTimeout(function () { el.classList.add('hidden'); }, 2500);
    }

    function pesanError(data) {
        if (data && data.errors) return Object.values(data.errors)[0][0];
        if (data && data.message) return data.message;
        return 'Terjadi kesalahan pada server.';
    }

    function bukaModal(modalEl) {
        modalEl.classList.remove('hidden');
        modalEl.classList.add('flex');
    }

    function tutupModal(modalEl) {
        modalEl.classList.add('hidden');
        modalEl.classList.remove('flex');
    }

    function bersihkanError() {
        document.querySelectorAll('.alert-field').forEach(function (el) {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    function render() {
        const kata  = cari.value.toLowerCase();
        const hasil = semua.filter(function (i) {
            return (i.kode + ' ' + i.nama).toLowerCase().includes(kata);
        });

        document.getElementById('jumlah-data').textContent = hasil.length + ' barang';

        if (hasil.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-5 py-10 text-center text-muted">Belum ada data barang.</td></tr>';
            return;
        }

        tbody.innerHTML = hasil.map(function (i, n) {
            return '<tr class="hover:bg-surface-2">' +
                '<td class="px-5 py-3 text-muted">' + (n + 1) + '</td>' +
                '<td class="px-5 py-3 font-medium text-slate-300">' + esc(i.kode) + '</td>' +
                '<td class="px-5 py-3 text-slate-100">' + esc(i.nama) + '</td>' +
                '<td class="px-5 py-3 text-slate-400">' + esc(i.kategori) + '</td>' +
                '<td class="px-5 py-3 text-slate-400">' + esc(i.satuan) + '</td>' +
                '<td class="px-5 py-3 text-right font-semibold text-slate-100">' + esc(i.stok) + '</td>' +
                '<td class="px-5 py-3">' +
                    '<div class="flex gap-1.5">' +
                        '<button type="button" data-detail="' + i.id + '" title="Detail" class="rounded-lg bg-indigo-500/20 px-2.5 py-1.5 text-xs text-indigo-300 hover:bg-indigo-500/30"><i class="fa-solid fa-eye"></i></button>' +
                        '<button type="button" data-edit="' + i.id + '" title="Edit" class="rounded-lg bg-amber-500/20 px-2.5 py-1.5 text-xs text-amber-300 hover:bg-amber-500/30"><i class="fa-solid fa-pen"></i></button>' +
                        '<button type="button" data-hapus="' + i.id + '" title="Hapus" class="rounded-lg bg-red-600 px-2.5 py-1.5 text-xs text-white hover:bg-red-700"><i class="fa-solid fa-trash"></i></button>' +
                    '</div>' +
                '</td>' +
                '</tr>';
        }).join('');
    }

    async function muatData() {
        const res = await fetch('/barang/get-data', { headers: { 'Accept': 'application/json' } });
        semua = (await res.json()).data;
        render();
    }

    function isiKategori(nilai) {
        const sel = document.getElementById('barang_kategori');
        if (nilai && !Array.from(sel.options).some(function (o) { return o.value === nilai; })) {
            sel.add(new Option(nilai, nilai, true, true));
        }
        sel.value = nilai || '';
    }

    function bukaTambah() {
        form.reset();
        idEdit.value = '';
        isiKategori('');
        judulModal.textContent = 'Tambah Barang';
        bersihkanError();
        bukaModal(modal);
    }

    async function bukaEdit(id) {
        const res = await fetch('/barang/' + id + '/detail', { headers: { 'Accept': 'application/json' } });
        const b = await res.json();

        form.reset();
        idEdit.value = b.id;
        document.getElementById('barang_kode').value = b.kode ?? '';
        document.getElementById('barang_nama').value = b.nama ?? '';
        isiKategori(b.kategori);
        document.getElementById('barang_satuan').value = b.satuan ?? '';
        document.getElementById('barang_lokasi').value = b.lokasi ?? '';
        document.getElementById('barang_stok_minimum').value = b.stok_minimum ?? 0;
        judulModal.textContent = 'Edit Barang';
        bersihkanError();
        bukaModal(modal);
    }

    async function bukaDetail(id) {
        const res = await fetch('/barang/' + id + '/detail', { headers: { 'Accept': 'application/json' } });
        const b = await res.json();

        document.getElementById('detail_kode').textContent = b.kode;
        document.getElementById('detail_nama').textContent = b.nama;
        document.getElementById('detail_kategori').textContent = b.kategori || '-';
        document.getElementById('detail_satuan').textContent = b.satuan;
        document.getElementById('detail_lokasi').textContent = b.lokasi || '-';
        document.getElementById('detail_stok_minimum').textContent = b.stok_minimum;
        document.getElementById('detail_stok').textContent = b.stok;
        bukaModal(modalDetail);
    }

    // Simpan (tambah atau edit)
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        bersihkanError();

        const id = idEdit.value;
        const body = new FormData(form);
        if (id) body.append('_method', 'PUT');

        btnSimpan.disabled = true;
        btnSimpan.textContent = 'Menyimpan...';

        try {
            const res = await fetch(id ? '/barang/' + id : '/barang', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: body
            });
            const data = await res.json();

            if (res.ok) {
                tutupModal(modal);
                notif(data.message);
                muatData();
            } else if (res.status === 422 && data.errors) {
                Object.entries(data.errors).forEach(function (entry) {
                    const el = document.getElementById('alert-' + entry[0]);
                    if (el) {
                        el.textContent = entry[1][0];
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
            btnSimpan.textContent = 'Simpan';
        }
    });

    // Aksi di tabel
    tbody.addEventListener('click', async function (e) {
        const btnDetail = e.target.closest('[data-detail]');
        const btnEdit   = e.target.closest('[data-edit]');
        const btnHapus  = e.target.closest('[data-hapus]');

        if (btnDetail) return bukaDetail(btnDetail.dataset.detail);
        if (btnEdit)   return bukaEdit(btnEdit.dataset.edit);

        if (btnHapus) {
            if (!confirm('Data barang akan dihapus. Lanjutkan?')) return;

            const res = await fetch('/barang/' + btnHapus.dataset.hapus, {
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
        }
    });

    // Tutup modal saat klik latar atau tombol tutup
    [modal, modalDetail].forEach(function (m) {
        m.addEventListener('click', function (e) {
            if (e.target === m || e.target.closest('[data-close]')) tutupModal(m);
        });
    });

    document.getElementById('btn-tambah').addEventListener('click', bukaTambah);
    cari.addEventListener('input', render);

    muatData();
})();
</script>
@endpush