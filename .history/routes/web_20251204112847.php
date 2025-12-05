<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

use App\Livewire\Master\ProdukIndex;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// --- GROUP MASTER DATA ---
Route::prefix('master')->name('master.')->group(function () {
    // Panggil Livewire Component langsung
    Route::get('/produk', ProdukIndex::class)->name('produk');
    
    // Sisa menu lain biarkan pakai controller dulu sementara
    Route::get('/supplier', [MasterController::class, 'indexSupplier'])->name('supplier');
    Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
    Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
});

// --- GROUP TRANSAKSI ---
Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/penjualan', [TransaksiController::class, 'indexPenjualan'])->name('penjualan');
    Route::get('/retur', [TransaksiController::class, 'indexRetur'])->name('retur');
    Route::get('/ar', [TransaksiController::class, 'indexAR'])->name('ar');
    Route::get('/collection', [TransaksiController::class, 'indexCollection'])->name('collection');
});

// --- GROUP LAPORAN ---
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/rekap-penjualan', [LaporanController::class, 'rekapPenjualan'])->name('rekap_penjualan');
    Route::get('/rekap-retur', [LaporanController::class, 'rekapRetur'])->name('rekap_retur');
    Route::get('/rekap-ar', [LaporanController::class, 'rekapAR'])->name('rekap_ar');
    Route
require __DIR__.'/auth
