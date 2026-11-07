<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangKeluarRequest;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangs  = Barang::orderBy('nama')->get(['id', 'kode', 'nama', 'stok', 'satuan']);
        $penerima = BarangKeluar::whereNotNull('penerima')->distinct()->orderBy('penerima')->pluck('penerima');
        $kodeTransaksi = $this->buatKodeTransaksi();

        return view('barang-keluar.index', compact('barangs', 'penerima', 'kodeTransaksi'));
    }

    public function getData()
    {
        $data = BarangKeluar::with('barang:id,kode,nama,satuan')
            ->latest('id')
            ->get()
            ->map(fn ($r) => [
                'id'             => $r->id,
                'kode_transaksi' => $r->kode_transaksi,
                'tanggal'        => $r->tanggal->format('d-m-Y'),
                'nama_barang'    => $r->barang->nama ?? '-',
                'jumlah'         => $r->jumlah,
                'penerima'       => $r->penerima ?? '-',
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

    public function store(StoreBarangKeluarRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            if ($barang->stok < $data['jumlah']) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok tidak cukup. Sisa stok {$barang->stok} {$barang->satuan}.",
                ]);
            }

            BarangKeluar::create([
                'kode_transaksi' => $this->buatKodeTransaksi(),
                'barang_id'      => $barang->id,
                'user_id'        => auth()->id(),
                'jumlah'         => $data['jumlah'],
                'harga_satuan'   => $barang->harga,
                'tanggal'        => $data['tanggal'],
                'keperluan'      => $data['keperluan'] ?? null,
                'penerima'       => $data['penerima'] ?? null,
                'keterangan'     => $data['keterangan'] ?? null,
            ]);

            $barang->decrement('stok', $data['jumlah']);
        });

        return response()->json(['message' => 'Data barang keluar berhasil disimpan!']);
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            $barang = Barang::lockForUpdate()->findOrFail($barangKeluar->barang_id);

            // Stok yang dikembalikan ke gudang
            $barang->increment('stok', $barangKeluar->jumlah);
            $barangKeluar->delete();
        });

        return response()->json(['message' => 'Data barang keluar berhasil dihapus!']);
    }

    /**
     * Format: TRX-OUT-2026-11-1-0001, sama seperti referensi.
     */
    private function buatKodeTransaksi(): string
    {
        $prefix = 'TRX-OUT-' . now()->format('Y-n-j') . '-';
        $urutan = BarangKeluar::whereDate('created_at', today())->count() + 1;

        do {
            $kode = $prefix . str_pad($urutan++, 4, '0', STR_PAD_LEFT);
        } while (BarangKeluar::where('kode_transaksi', $kode)->exists());

        return $kode;
    }
}