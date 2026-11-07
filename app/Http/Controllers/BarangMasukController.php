<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangMasukRequest;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangs   = Barang::orderBy('nama')->get(['id', 'kode', 'nama', 'stok', 'satuan']);
        $suppliers = BarangMasuk::whereNotNull('supplier')->distinct()->orderBy('supplier')->pluck('supplier');
        $kodeTransaksi = $this->buatKodeTransaksi();

        return view('barang-masuk.index', compact('barangs', 'suppliers', 'kodeTransaksi'));
    }

    public function getData()
    {
        $data = BarangMasuk::with('barang:id,kode,nama,satuan')
            ->latest('id')
            ->get()
            ->map(fn ($r) => [
                'id'             => $r->id,
                'kode_transaksi' => $r->kode_transaksi,
                'tanggal'        => $r->tanggal->format('d-m-Y'),
                'nama_barang'    => $r->barang->nama ?? '-',
                'jumlah'         => $r->jumlah,
                'supplier'       => $r->supplier ?? '-',
            ]);

        return response()->json(['data' => $data]);
    }

    public function getBarangDetail(Request $request)
    {
        $barang = Barang::find($request->barang_id);

        return response()->json([
            'stok'   => $barang->stok ?? 0,
            'satuan' => $barang->satuan ?? '-',
        ]);
    }

    public function store(StoreBarangMasukRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            BarangMasuk::create([
                'kode_transaksi' => $this->buatKodeTransaksi(),
                'barang_id'      => $barang->id,
                'user_id'        => auth()->id(),
                'jumlah'         => $data['jumlah'],
                'tanggal'        => $data['tanggal'],
                'supplier'       => $data['supplier'] ?? null,
                'keterangan'     => $data['keterangan'] ?? null,
            ]);

            $barang->increment('stok', $data['jumlah']);
        });

        return response()->json(['message' => 'Data barang masuk berhasil disimpan!']);
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::transaction(function () use ($barangMasuk) {
            $barang = Barang::lockForUpdate()->findOrFail($barangMasuk->barang_id);

            // Stok yang sudah dikeluarkan tidak boleh ditarik kembali
            if ($barang->stok < $barangMasuk->jumlah) {
                throw ValidationException::withMessages([
                    'hapus' => 'Stok sudah terpakai, transaksi ini tidak bisa dihapus.',
                ]);
            }

            $barang->decrement('stok', $barangMasuk->jumlah);
            $barangMasuk->delete();
        });

        return response()->json(['message' => 'Data barang masuk berhasil dihapus!']);
    }

    /**
     * Format: TRX-IN-2026-11-1-0001, sama seperti referensi.
     */
    private function buatKodeTransaksi(): string
    {
        $prefix = 'TRX-IN-' . now()->format('Y-n-j') . '-';
        $urutan = BarangMasuk::whereDate('created_at', today())->count() + 1;

        do {
            $kode = $prefix . str_pad($urutan++, 4, '0', STR_PAD_LEFT);
        } while (BarangMasuk::where('kode_transaksi', $kode)->exists());

        return $kode;
    }
}