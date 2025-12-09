<?php

use Illuminate\Support\Facades\Route;

// --- 1. DASHBOARD ---
use App\Livewire\DashboardIndex;

// --- 2. MASTER DATA ---
use App\Livewire\Master\SalesIndex;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;
// Note: UserIndex belum dibuat, jadi belum di-import

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
}   );
// Redirect halaman depan langsung ke Login
// Route::get('/', function () {
//     return redirect()->route('login');
// });

// GROUP MIDDLEWARE: Hanya bisa diakses jika sudah LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 1. DASHBOARD
    // ====================================================
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');


    // ====================================================
    // 2. MASTER DATA
    // ====================================================
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/sales', SalesIndex::class)->name('sales');
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier');
        
        // Placeholder untuk User jika nanti dibuat
        // Route::get('/user', UserIndex::class)->name('user');
    });


    // ====================================================
    // 3. OPERASIONAL (MENU INPUT & IMPORT)
    // ====================================================
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/ar', ArIndex::class)->name('ar');
        Route::get('/collection', CollectionIndex::class)->name('collection');
    });


    // ====================================================
    // 4. LAPORAN & ANALISA
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


    // ====================================================
    // 5. PROFILE USER
    // ====================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';