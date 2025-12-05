<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// --- LIVEWIRE COMPONENTS ---
// Master Data
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;

// Transaksi (Input)
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex; // [BARU] Komponen Input AR

// Laporan (Rekap)
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapArIndex; // [BARU] Komponen Laporan AR

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
        
        // Controller Placeholder (Belum dibuat Livewire-nya)
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // --- GROUP TRANSAKSI (INPUT DATA) ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // Order Penjualan (Input & Import)
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        
        // Retur Penjualan (Input & Import)
        Route::get('/retur', ReturIndex::class)->name('retur'); 
        
        // AR / Piutang (Input & Import) - [UPDATED]
        Route::get('/ar', ArIndex::class)->name('ar');
        
        // Collection (Masih Controller Biasa)
        Route::get('/collection', [TransaksiController::class, 'indexCollection'])->name('collection');
    });

    // --- GROUP LAPORAN (READ ONLY) ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Rekap Penjualan (Tabel Lengkap 51 Kolom)
        Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap_penjualan');
        
        // Rekap Retur (Menggunakan Component ReturIndex dengan mode Laporan)
        Route::get('/rekap-retur', ReturIndex::class)->name('rekap_retur');
        
        // Rekap AR (Tabel Lengkap Piutang) - [UPDATED]
        Route::get('/rekap-ar', RekapArIndex::class)->name('rekap_ar');
        
        // Rekap Collection (Masih Controller Biasa)
        Route::get('/rekap-collection', [LaporanController::class, 'rekapCollection'])->name('rekap_collection');
    });

});

require __DIR__.'/auth.php';