<?php

use Illuminate\Support\Facades\Route;

// --- 1. DASHBOARD ---
use App\Livewire\DashboardIndex;

// --- 2. MASTER DATA ---
use App\Livewire\Master\SalesIndex;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;
use App\Livewire\Master\UserIndex;

// --- 3. TRANSAKSI (OPERASIONAL) ---
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex;
use App\Livewire\Transaksi\CollectionIndex;

// --- 4. LAPORAN (REKAPITULASI TABEL FULL) ---
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapReturIndex;
use App\Livewire\Laporan\RekapArIndex;
use App\Livewire\Laporan\RekapCollectionIndex;

// --- 5. LAPORAN (ANALISA KINERJA) ---
use App\Livewire\Laporan\KinerjaSalesIndex;

// --- PROFILE (Bawaan Laravel) ---
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// GROUP UTAMA: HANYA PERLU LOGIN (AUTH)
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 1. DASHBOARD & PROFILE
    // ====================================================
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ====================================================
    // 2. MASTER DATA (SEMUA ROLE BISA AKSES)
    // ====================================================
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/sales', SalesIndex::class)->name('sales');
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier');
        Route::get('/user', UserIndex::class)->name('user'); 
    });


    // ====================================================
    // 3. OPERASIONAL / TRANSAKSI (SEMUA ROLE BISA AKSES)
    // ====================================================
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/ar', ArIndex::class)->name('ar');
        Route::get('/collection', CollectionIndex::class)->name('collection');
    });


    // ====================================================
    // 4. LAPORAN & ANALISA (SEMUA ROLE BISA AKSES)
    // ====================================================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        
        // A. Analisa Kinerja (Rapor Sales)
        Route::get('/kinerja-sales', KinerjaSalesIndex::class)->name('kinerja-sales');

        // B. Rekapitulasi (Tabel Full Excel)
        Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap-penjualan');
        Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap-retur');
        Route::get('/rekap-ar', RekapArIndex::class)->name('rekap-ar');
        Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap-collection');
    });

});

require __DIR__.'/auth.php';