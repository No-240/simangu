<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:admin'])->get('/admin/inventory', [InventoryController::class, 'admin']);
Route::middleware(['auth', 'role:staff'])->get('/staff/inventory', [InventoryController::class, 'staff']);

Route::get('/laporan', fn () => view('placeholder', [
    'judul'     => 'Laporan',
    'deskripsi' => 'Rekap stok dan transaksi barang masuk dan keluar.',
]))->name('laporan');

Route::middleware('role:admin')->group(function () {
    Route::get('/pengguna', fn () => view('placeholder', [
        'judul'     => 'Manajemen User',
        'deskripsi' => 'Kelola akun admin dan staff gudang.',
    ]))->name('pengguna');
});

Route::middleware('auth')->group(function () {

    Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getData']);
    Route::get('/barang-masuk/get-barang-detail', [BarangMasukController::class, 'getBarangDetail']);
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'store', 'destroy']);

    Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getData']);
    Route::get('/barang-keluar/get-barang-detail', [BarangKeluarController::class, 'getBarangDetail']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'store', 'destroy']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/barang/get-data', [BarangController::class, 'getData']);
        Route::get('/barang/{barang}/detail', [BarangController::class, 'detail']);
        Route::resource('barang', BarangController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});