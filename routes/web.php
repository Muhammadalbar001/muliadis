<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT DASHBOARD BARU (DIPISAH) ---
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

// GROUP UTAMA: HARUS LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 1. LOGIKA REDIRECT DASHBOARD (PENTING)
    // ====================================================
    // Route ini mencegat user yang mengakses '/dashboard' biasa
    // dan melempar mereka ke halaman khusus role masing-masing.
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        if (in_array($role, ['admin', 'pimpinan'])) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('staff.dashboard');
        }
    })->name('dashboard');

    // Profile bisa diakses semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ====================================================
    // 2. AREA KHUSUS ADMIN & PIMPINAN
    // ====================================================
    // Hanya role 'admin' dan 'pimpinan' yang bisa akses URL berawalan /admin
    Route::middleware(['role:admin,pimpinan'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Berat (Chart, Statistik, dll)
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        // Master Data (Hanya Admin yang boleh edit data master)
        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/sales', SalesIndex::class)->name('sales');
            Route::get('/produk', ProdukIndex::class)->name('produk');
            Route::get('/supplier', SupplierIndex::class)->name('supplier');
            Route::get('/user', UserIndex::class)->name('user'); 
        });

        // Laporan (Analisa berat hanya untuk Pimpinan/Admin)
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/kinerja-sales', KinerjaSalesIndex::class)->name('kinerja-sales');
            Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap-penjualan');
            Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap-retur');
            Route::get('/rekap-ar', RekapArIndex::class)->name('rekap-ar');
            Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap-collection');
        });
    });


    // ====================================================
    // 3. AREA KHUSUS STAFF / PENGGUNA
    // ====================================================
    // Role 'pengguna' akan bekerja di sini
    // Note: Jika Admin juga butuh input transaksi, tambahkan role admin di middleware bawah ini
    Route::middleware(['role:pengguna,admin'])->prefix('staff')->name('staff.')->group(function () {
        
        // Dashboard Ringan (Tanpa Chart Berat)
        Route::get('/dashboard', StaffDashboard::class)->name('dashboard');

        // Transaksi Operasional Harian
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
            Route::get('/retur', ReturIndex::class)->name('retur');
            Route::get('/ar', ArIndex::class)->name('ar');
            Route::get('/collection', CollectionIndex::class)->name('collection');
        });
    });

});

require __DIR__.'/auth.php';