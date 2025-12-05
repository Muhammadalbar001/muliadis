<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

use App\Livewire\Master\ProdukIndex;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('master')->name('master.')->group(function () {
    // Produk
    Route::get('/produk', [MasterController::class, 'indexProduk'])->name('produk');
    Route::get('/produk', ProdukIndex::class)->name('produk');
    // Supplier (Placeholder sementara)
    Route::get('/supplier', [MasterController::class, 'indexSupplier'])->name('supplier');
    // Sales (Placeholder sementara)
    Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
    // User (Placeholder sementara)
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
    Route::get('/rekap-collection', [LaporanController::class, 'rekapCollection'])->name('rekap_collection');
});