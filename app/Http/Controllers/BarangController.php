<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function create()
    {
       $kategoriSaran = Barang::whereNotNull('kategori')
           ->distinct()
           ->orderBy('kategori')
           ->pluck('kategori');

        return view('barang.create', compact('kategoriSaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'         => ['required', 'string', 'max:50', 'unique:barang,kode'],
            'nama'         => ['required', 'string', 'max:150'],
            'kategori'     => ['nullable', 'string', 'max:100'],
            'satuan'       => ['required', 'string', 'max:30'],
            'lokasi'       => ['nullable', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'stok_awal'    => ['nullable', 'integer', 'min:0'],
        ]);

        $stokAwal = (int) ($data['stok_awal'] ?? 0);
        unset($data['stok_awal']);

        DB::transaction(function () use ($data, $stokAwal) {
            $barang = Barang::create($data);

            if ($stokAwal > 0) {
                BarangMasuk::create([
                    'barang_id'  => $barang->id,
                    'user_id'    => auth()->id(),
                    'jumlah'     => $stokAwal,
                    'tanggal'    => now()->toDateString(),
                    'keterangan' => 'Stok awal',
                ]);

                $barang->increment('stok', $stokAwal);
            }
        });

        return redirect('/admin/inventory')->with('success', 'Barang baru berhasil ditambahkan.');
    }    
}