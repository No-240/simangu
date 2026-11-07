<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'kode_transaksi', 'barang_id', 'user_id', 'jumlah', 'harga_satuan', 'tanggal',
        'keperluan', 'penerima', 'tanda_tangan', 'keterangan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
