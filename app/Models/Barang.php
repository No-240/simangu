<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    // 'stok' sengaja tidak dimasukkan: stok hanya berubah lewat transaksi masuk/keluar
    protected $fillable = ['kode', 'nama', 'kategori', 'satuan', 'stok_minimum'];

    public function masuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function keluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    // Barang yang stoknya sudah sama dengan atau di bawah batas minimum
    public function scopeHampirHabis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum');
    }
}
