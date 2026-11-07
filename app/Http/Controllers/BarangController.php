<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BarangController extends Controller
{
    public function index()
    {
        $kategori   = Barang::whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $satuanList = ['lembar', 'batang', 'pcs', 'kg', 'meter', 'rol', 'lainnya'];

        return view('barang.index', compact('kategori', 'satuanList'));
    }

    public function getData()
    {
        $data = Barang::orderBy('nama')->get()->map(fn ($b) => [
            'id'       => $b->id,
            'kode'     => $b->kode,
            'nama'     => $b->nama,
            'kategori' => $b->kategori ?? '-',
            'satuan'   => $b->satuan,
            'stok'     => $b->stok,
            'harga'    => $b->harga,
        ]);

        return response()->json(['data' => $data]);
    }

    public function detail(Barang $barang)
    {
        return response()->json($barang->only(['id', 'kode', 'nama', 'kategori', 'satuan', 'lokasi', 'stok_minimum', 'stok', 'harga']));
    }

    public function store(Request $request)
    {
        Barang::create($this->validasi($request));

        return response()->json(['message' => 'Data barang berhasil ditambahkan!']);
    }

    public function update(Request $request, Barang $barang)
    {
        $barang->update($this->validasi($request, $barang->id));

        return response()->json(['message' => 'Data barang berhasil diubah!']);
    }

    public function destroy(Barang $barang)
    {
        // Barang yang sudah punya transaksi tidak boleh dihapus, supaya riwayat tetap utuh
        if ($barang->masuk()->exists() || $barang->keluar()->exists()) {
            throw ValidationException::withMessages([
                'hapus' => 'Barang ini sudah punya transaksi, tidak bisa dihapus.',
            ]);
        }

        $barang->delete();

        return response()->json(['message' => 'Data barang berhasil dihapus!']);
    }

    private function validasi(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'kode'         => ['required', 'string', 'max:50', Rule::unique('barang', 'kode')->ignore($id)],
            'nama'         => ['required', 'string', 'max:150'],
            'kategori'     => ['nullable', 'string', 'max:100'],
            'satuan'       => ['required', 'string', 'max:30'],
            'lokasi'       => ['nullable', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga'        => ['required', 'integer', 'min:0'],
        ], [
            'kode.unique' => 'Kode barang sudah digunakan.',
        ]);
    }
}