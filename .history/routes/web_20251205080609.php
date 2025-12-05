<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex; 
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\Re; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// --- GROUP KHUSUS YANG SUDAH LOGIN ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- GROUP MASTER DATA ---
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier'); 
        
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // --- GROUP TRANSAKSI ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // Order Penjualan (Tampilkan Tombol Import)
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan'); 
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/retur', [TransaksiController::class, 'indexRetur'])->name('retur');
        Route::get('/ar', [TransaksiController::class, 'indexAR'])->name('ar');
        Route::get('/collection', [TransaksiController::class, 'indexCollection'])->name('collection');
    });

    // --- GROUP LAPORAN ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Rekap Penjualan (Sama dengan PenjualanIndex, tapi tanpa Tombol Import)
        Route::get('/rekap-penjualan', PenjualanIndex::class)->name('rekap_penjualan');
        
        Route::get('/rekap-retur', [LaporanController::class, 'rekapRetur'])->name('rekap_retur');
        Route::get('/rekap-ar', [LaporanController::class, 'rekapAR'])->name('rekap_ar');
        Route::get('/rekap-collection', [LaporanController::class, 'rekapCollection'])->name('rekap_collection');
    });

});

require __DIR__.'/auth.php';