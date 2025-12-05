<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// --- LIVEWIRE COMPONENTS ---
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex; 
use App\Livewire\Transaksi\PenjualanIndex; 
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Laporan\RekapPenjualanIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
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
        
        // Controller Placeholder (Belum dibuat Livewire-nya)
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // --- GROUP TRANSAKSI ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // [UPDATE] Menggunakan Livewire PenjualanIndex (Mode Order)
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        
        // [BARU] Menggunakan Livewire ReturIndex (Mode Input Retur)
        Route::get('/retur', ReturIndex::class)->name('retur'); 
        
        // Controller Placeholder
        Route::get('/ar', [TransaksiController::class, 'indexAR'])->name('ar');
        Route::get('/collection', [TransaksiController::class, 'indexCollection'])->name('collection');
    });

    // --- GROUP LAPORAN ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap_penjualan');
        
        // [BARU] Menggunakan Livewire ReturIndex (Mode Laporan/Rekap)
        Route::get('/rekap-retur', ReturIndex::class)->name('rekap_retur');
        
        // Controller Placeholder
        Route::get('/rekap-ar', [LaporanController::class, 'rekapAR'])->name('rekap_ar');
        Route::get('/rekap-collection', [LaporanController::class, 'rekapCollection'])->name('rekap_collection');
    });

});

// Auth Routes (Breeze)
require __DIR__.'/auth.php';