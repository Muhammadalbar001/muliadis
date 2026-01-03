<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT DASHBOARD ---
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Staff\Dashboard as StaffDashboard;

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

// --- 5. LAPORAN (ANALISA KINERJA & PIMPINAN) ---
use App\Livewire\Laporan\KinerjaSalesIndex;
use App\Livewire\Pimpinan\StockAnalysis;
use App\Livewire\Pimpinan\ProfitAnalysis;

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

// GROUP UTAMA: HARUS LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 1. LOGIKA REDIRECT DASHBOARD (Role Baru)
    // ====================================================
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        // Superadmin, Pimpinan, dan Supervisor diarahkan ke Admin Dashboard (Analitik)
        if (in_array($role, ['superadmin', 'pimpinan', 'supervisor'])) {
            return redirect()->route('admin.dashboard');
        } 
        // Admin (Staff Data Entry) diarahkan ke Staff Dashboard
        return redirect()->route('staff.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================
    // 2. AKSES MENU (DIBUKA UNTUK SEMUA ROLE BARU)
    // ====================================================
    Route::middleware(['role:superadmin,pimpinan,supervisor,admin'])->group(function () {
        
        // --- AREA ADMIN/ANALISA ---
        Route::prefix('admin')->group(function() {
            Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
            
            // Master Data
            Route::prefix('master')->name('master.')->group(function () {
                Route::get('/sales', SalesIndex::class)->name('sales');
                Route::get('/produk', ProdukIndex::class)->name('produk');
                Route::get('/supplier', SupplierIndex::class)->name('supplier');
                Route::get('/user', UserIndex::class)->name('user'); 
            });

            // Laporan & Analisa
            Route::prefix('laporan')->name('laporan.')->group(function () {
                Route::get('/kinerja-sales', KinerjaSalesIndex::class)->name('kinerja-sales');
                Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap-penjualan');
                Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap-retur');
                Route::get('/rekap-ar', RekapArIndex::class)->name('rekap-ar');
                Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap-collection');
            });

            // Analisa Executive
            Route::get('/stock-analysis', StockAnalysis::class)->name('pimpinan.stock-analysis');
            Route::get('/profit-analysis', ProfitAnalysis::class)->name('pimpinan.profit-analysis');
        });

        // --- AREA TRANSAKSI ---
        Route::prefix('staff')->group(function() {
            Route::get('/dashboard', StaffDashboard::class)->name('staff.dashboard');

            Route::prefix('transaksi')->name('transaksi.')->group(function () {
                Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
                Route::get('/retur', ReturIndex::class)->name('retur');
                Route::get('/ar', ArIndex::class)->name('ar');
                Route::get('/collection', CollectionIndex::class)->name('collection');
            });
        });
    });

});

require __DIR__.'/auth.php';