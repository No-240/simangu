<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Halaman inventaris untuk admin (lihat, tambah, ubah, hapus).
     */
    public function admin()
    {
        $barang = Barang::orderBy('nama')->get();

        return view('admin_inventory', [
            'barang'     => $barang,
            'totalAset'  => $barang->sum(fn ($b) => $b->stok * ($b->harga ?? 0)),
        ]);
    }

    /**
     * Halaman stok untuk staff (lihat dan cek stok harian).
     */
    public function staff()
    {
        $barang = Barang::orderBy('nama')->get();

        return view('staff_inventory', [
            'barang' => $barang,
        ]);
    }
}