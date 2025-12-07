<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Livewire Components
use App\Livewire\DashboardIndex;
use App\Livewire\Master\ProdukIndex;
use App\Livewire\Master\SupplierIndex;
use App\Livewire\Transaksi\PenjualanIndex;
use App\Livewire\Transaksi\ReturIndex;
use App\Livewire\Transaksi\ArIndex;
use App\Livewire\Transaksi\CollectionIndex;
use App\Livewire\Laporan\RekapPenjualanIndex;
use App\Livewire\Laporan\RekapReturIndex;
use App\Livewire\Laporan\RekapArIndex;
use App\Livewire\Laporan\RekapCollectionIndex;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // MASTER DATA
    Route::get('/master/produk', ProdukIndex::class)->name('master.produk');
    Route::get('/master/supplier', SupplierIndex::class)->name('master.supplier');

    // TRANSAKSI
    Route::get('/transaksi/penjualan', PenjualanIndex::class)->name('transaksi.penjualan');
    Route::get('/transaksi/retur', ReturIndex::class)->name('transaksi.retur');
    Route::get('/transaksi/ar', ArIndex::class)->name('transaksi.ar');
    Route::get('/transaksi/collection', CollectionIndex::class)->name('transaksi.collection');

    // LAPORAN
    Route::get('/laporan/penjualan', RekapPenjualanIndex::class)->name('laporan.penjualan');
    Route::get('/laporan/retur', RekapReturIndex::class)->name('laporan.retur');
    Route::get('/laporan/ar', RekapArIndex::class)->name('laporan.ar');
    Route::get('/laporan/collection', RekapCollectionIndex::class)->name('laporan.collection');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';