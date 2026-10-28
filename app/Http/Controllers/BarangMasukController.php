<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangMasukRequest;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Riwayat barang masuk, terbaru di atas.
     */
    public function index()
    {
        $riwayat = BarangMasuk::with(['barang', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);

        return view('barang_masuk.index', compact('riwayat'));
    }

    /**
     * Form pencatatan barang masuk.
     */
    public function create()
    {
        $barang = Barang::orderBy('nama')->get(['id', 'kode', 'nama', 'stok', 'satuan']);

        return view('barang_masuk.create', compact('barang'));
    }

    /**
     * Simpan transaksi dan tambah stok dalam satu transaksi database.
     * Kalau salah satu gagal, dua-duanya dibatalkan.
     */
    public function store(StoreBarangMasukRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // lockForUpdate mencegah dua transaksi mengubah stok barang yang sama bersamaan
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            BarangMasuk::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => $data['jumlah'],
                'tanggal'    => $data['tanggal'],
                'supplier'   => $data['supplier'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $barang->increment('stok', $data['jumlah']);
        });

        return redirect()
            ->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat.');
    }
}