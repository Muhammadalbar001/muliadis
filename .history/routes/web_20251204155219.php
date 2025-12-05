<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;
use App\Livewire\Transaksi\PenjualanIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan: Jika buka website, langsung arahkan ke Dashboard (nanti dicek login-nya)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// --- GROUP KHUSUS YANG SUDAH LOGIN ---
// Semua route di dalam sini hanya bisa diakses jika user sudah Login
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- GROUP MASTER DATA ---
    Route::prefix('master')->name('master.')->group(function () {
        // Panggil Livewire Component langsung
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier');
        
        // Controller Biasa
        // Route::get('/supplier', [MasterController::class, 'indexSupplier'])->name('supplier');
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // --- GROUP TRANSAKSI ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', [TransaksiController::class, 'indexPenjualan'])->name('penjualan');
        // Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/rekap-penjualan', PenjualanIndex::class)->name('rekap_penjualan');
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

});

// --- PENTING: Jangan Hapus Baris Ini ---
// Ini memuat route login, register, logout bawaan Breeze
require __DIR__.'/auth.php';