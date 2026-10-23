<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun awal. Ganti password setelah login pertama.
        User::create([
            'name'     => 'Admin Gudang',
            'email'    => 'admin@gudang.com',
            'password' => 'admin123',
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Staff Gudang',
            'email'    => 'staff@gudang.com',
            'password' => 'staff123',
            'role'     => 'staff',
        ]);
        // Data contoh. Sesuaikan dengan hasil wawancara di CV Jaya Giry.
        $contoh = [
            ['PLT-304-2', 'Plat stainless 304 tebal 2 mm', 'Bahan baku', 'lembar', 10],
            ['PIP-1IN',   'Pipa stainless 1 inch',          'Bahan baku', 'batang', 8],
            ['BLT-M6',    'Baut stainless M6',              'Pengikat',   'pcs',    100],
            ['RDA-CST',   'Roda caster',                    'Komponen',   'pcs',    20],
        ];

        foreach ($contoh as [$kode, $nama, $kategori, $satuan, $min]) {
            Barang::create([
                'kode' => $kode, 'nama' => $nama, 'kategori' => $kategori,
                'satuan' => $satuan, 'stok_minimum' => $min,
            ]);
        }
    }
}
