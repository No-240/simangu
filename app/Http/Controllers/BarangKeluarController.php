<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangKeluarRequest;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangKeluarController extends Controller
{
    /**
     * Riwayat barang keluar, terbaru di atas.
     */
    public function index()
    {
        $riwayat = BarangKeluar::with(['barang', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);

        return view('barang_keluar.index', compact('riwayat'));
    }

    /**
     * Form pencatatan barang keluar.
     */
    public function create()
    {
        $barang = Barang::orderBy('nama')->get(['id', 'kode', 'nama', 'stok', 'satuan']);

        return view('barang_keluar.create', compact('barang'));
    }

    /**
     * Cek stok, lalu kurangi stok dan catat riwayat dalam satu transaksi.
     */
    public function store(StoreBarangKeluarRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // lockForUpdate memastikan dua transaksi tidak mengambil stok yang sama bersamaan
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            if ($barang->stok < $data['jumlah']) {
                // Melempar exception membatalkan transaksi dan mengembalikan pesan ke form
                throw ValidationException::withMessages([
                    'jumlah' => "Stok tidak cukup. Sisa stok {$barang->stok} {$barang->satuan}.",
                ]);
            }

            BarangKeluar::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => $data['jumlah'],
                'tanggal'    => $data['tanggal'],
                'keperluan'  => $data['keperluan'] ?? null,
                'penerima'   => $data['penerima'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $barang->decrement('stok', $data['jumlah']);
        });

        return redirect()
            ->route('barang-keluar.index')
            ->with('success', 'Barang keluar berhasil dicatat.');
    }
}