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


Route::middleware('auth')->group(function () {
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'create', 'store']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'create', 'store']);
    
    Route::middleware('role:admin')->group(function () {
        Route::resource('barang', BarangController::class)->only(['create', 'store']);
    });
});