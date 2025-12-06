<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // <--- WAJIB DITAMBAHKAN
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

// --- LIVEWIRE COMPONENTS ---

// 1. Master Data
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;

// 2. Transaksi (Input & Import)
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex; 
use App\Livewire\Transaksi\CollectionIndex; 

// 3. Laporan (Read Only / Rekap)
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapArIndex;
use App\Livewire\Laporan\RekapReturIndex;
use App\Livewire\Laporan\RekapCollectionIndex; 

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

    // --- ROUTE PROFILE (PENTING: Tambahkan ini) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GROUP MASTER DATA ---
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier'); 
        
        // Placeholder Controller
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // --- GROUP TRANSAKSI (INPUT) ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/ar', ArIndex::class)->name('ar');
        Route::get('/collection', CollectionIndex::class)->name('collection');
    });

    // --- GROUP LAPORAN (REKAP) ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap_penjualan');
        Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap_retur');
        Route::get('/rekap-ar', RekapArIndex::class)->name('rekap_ar');
        Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap_collection');
    });

});

require __DIR__.'/auth.php';