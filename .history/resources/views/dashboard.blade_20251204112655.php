@extends('layouts.app')

@section('header', 'Dashboard Utama')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Produk</p>
                <h3 class="text-2xl font-bold text-gray-800">0</h3>
            </div>
            <div class="text-blue-500">
                <i class="fas fa-box fa-2x"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Penjualan Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp 0</h3>
            </div>
            <div class="text-green-500">
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Piutang (AR)</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp 0</h3>
            </div>
            <div class="text-red-500">
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collection (Lunas)</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp 0</h3>
            </div>
            <div class="text-yellow-500">
                <i class="fas fa-wallet fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Selamat Datang di Sistem PT. Mulia Anugerah Distribusindo</h3>
    <p class="text-gray-600">Sistem ini telah siap digunakan. Silakan akses menu di sebelah kiri untuk mengelola data
        Produk, Penjualan, Retur, dan Laporan.</p>
</div>
@endsection