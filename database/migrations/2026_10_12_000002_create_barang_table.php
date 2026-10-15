<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 150);
            $table->string('kategori', 100)->nullable();
            $table->string('satuan', 30);                       // lembar, batang, pcs, dst.
            $table->unsignedInteger('stok')->default(0);        // hanya berubah lewat transaksi
            $table->unsignedInteger('stok_minimum')->default(0); // batas peringatan stok hampir habis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
