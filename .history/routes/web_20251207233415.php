<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---
use App\Http\Controllers\DashboardController; // Untuk Grafik Dashboard
use App\Http\Controllers\ProfileController;   // Untuk Update Profile
use App\Http\Controllers\MasterController;    // Untuk Halaman Statis (Sales/User)
use App\Livewire\DashboardIndex;

// --- LIVEWIRE COMPONENTS (MASTER DATA) ---
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;

// --- LIVEWIRE COMPONENTS (TRANSAKSI - IMPORT) ---
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex;
use App\Livewire\Transaksi\CollectionIndex;

// --- LIVEWIRE COMPONENTS (LAPORAN - REKAP) ---
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapReturIndex;
use App\Livewire\Laporan\RekapArIndex;
use App\Livewire\Laporan\RekapCollectionIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman utama ke dashboard (jika sudah login) atau login (jika belum)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// --- GROUP ROUTE YANG MEMBUTUHKAN LOGIN ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. DASHBOARD
    // Menggunakan Controller agar data Penjualan/AR/Collection dihitung otomatis
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. PROFILE USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. MASTER DATA
    Route::prefix('master')->name('master.')->group(function () {
        // Halaman Produk & Supplier (Sudah Dinamis dengan Livewire)
        Route::get('/produk', ProdukIndex::class)->name('produk');
        Route::get('/supplier', SupplierIndex::class)->name('supplier');
        
        // Halaman Sales & Users (Masih menggunakan Controller biasa/Placeholder)
        // Pastikan method ini ada di MasterController Anda, atau buat function kosong dulu
        Route::get('/sales', [MasterController::class, 'indexSales'])->name('sales');
        Route::get('/users', [MasterController::class, 'indexUser'])->name('users');
    });

    // 4. TRANSAKSI (INPUT & IMPORT EXCEL)
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/penjualan', PenjualanIndex::class)->name('penjualan');
        Route::get('/retur', ReturIndex::class)->name('retur');
        Route::get('/ar', ArIndex::class)->name('ar');
        Route::get('/collection', CollectionIndex::class)->name('collection');
    });

    // 5. LAPORAN (REKAPITULASI DATA)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/rekap-penjualan', RekapPenjualanIndex::class)->name('rekap_penjualan');
        Route::get('/rekap-retur', RekapReturIndex::class)->name('rekap_retur');
        Route::get('/rekap-ar', RekapArIndex::class)->name('rekap_ar');
        Route::get('/rekap-collection', RekapCollectionIndex::class)->name('rekap_collection');
    });

});

// Memuat route autentikasi bawaan Laravel Breeze (Login, Register, dll)
require __DIR__.'/auth.php';