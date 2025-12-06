@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Pagi, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-500 text-sm mt-1">Berikut adalah ringkasan performa bisnis Anda hari ini.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('transaksi.penjualan') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Buat Penjualan
            </a>
            <button
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                <i class="fas fa-download mr-2"></i> Export Laporan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
            class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6 group hover:shadow-lg transition duration-300">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-blue-600"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">Rp 120.5M</h3>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span class="font-medium">12.5%</span>
                        <span class="text-gray-400 ml-1 text-xs">vs bulan lalu</span>
                    </div>
                </div>
                <div
                    class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6 group hover:shadow-lg transition duration-300">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-orange-400 to-orange-600"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Piutang (AR)</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">Rp 45.2M</h3>
                    <div class="flex items-center mt-2 text-sm text-red-500">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span class="font-medium">2.1%</span>
                        <span class="text-gray-400 ml-1 text-xs">Jatuh tempo dekat</span>
                    </div>
                </div>
                <div
                    class="p-3 bg-orange-50 rounded-lg text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition">
                    <i class="fas fa-file-invoice-dollar fa-lg"></i>
                </div>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6 group hover:shadow-lg transition duration-300">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Collection (Lunas)</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">Rp 88.0M</h3>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span class="font-medium">98%</span>
                        <span class="text-gray-400 ml-1 text-xs">Target tercapai</span>
                    </div>
                </div>
                <div
                    class="p-3 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6 group hover:shadow-lg transition duration-300">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-400 to-indigo-600"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total SKU Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">1,420</h3>
                    <div class="flex items-center mt-2 text-sm text-indigo-600">
                        <i class="fas fa-box-open mr-1"></i>
                        <span class="font-medium">5 New</span>
                        <span class="text-gray-400 ml-1 text-xs">minggu ini</span>
                    </div>
                </div>
                <div
                    class="p-3 bg-indigo-50 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                    <i class="fas fa-boxes-stacked fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Tren Penjualan & Collection</h3>
                <select
                    class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-600">
                    <option>Tahun Ini</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Komposisi Penjualan</h3>
            <div class="relative h-60 w-full flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></span>
                        <span class="text-gray-600">Pareto A</span>
                    </div>
                    <span class="font-bold text-gray-800">45%</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-400 mr-2"></span>
                        <span class="text-gray-600">Pareto B</span>
                    </div>
                    <span class="font-bold text-gray-800">30%</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span class="text-gray-600">Lainnya</span>
                    </div>
                    <span class="font-bold text-gray-800">25%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Terakhir</h3>
            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-800 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No Transaksi</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-indigo-600">INV-2025-001</td>
                        <td class="px-6 py-4">Apotek Sehat Selalu</td>
                        <td class="px-6 py-4">05 Des 2025</td>
                        <td class="px-6 py-4"><span
                                class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Lunas</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold">Rp 12.500.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-indigo-600">INV-2025-002</td>
                        <td class="px-6 py-4">RS Umum Daerah</td>
                        <td class="px-6 py-4">05 Des 2025</td>
                        <td class="px-6 py-4"><span
                                class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Belum
                                Lunas</span></td>
                        <td class="px-6 py-4 text-right font-bold">Rp 45.000.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-indigo-600">INV-2025-003</td>
                        <td class="px-6 py-4">Klinik Bersama</td>
                        <td class="px-6 py-4">04 Des 2025</td>
                        <td class="px-6 py-4"><span
                                class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Lunas</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold">Rp 8.250.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Sales Chart Configuration
    const ctxSales = document.getElementById('salesChart').getContext('2d');

    // Gradient Fill
    const gradientBlue = ctxSales.createLinearGradient(0, 0, 0, 400);
    gradientBlue.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo
    gradientBlue.addColorStop(1, 'rgba(79, 70, 229, 0)');

    const gradientGreen = ctxSales.createLinearGradient(0, 0, 0, 400);
    gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // Emerald
    gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                'Des'],
            datasets: [{
                    label: 'Penjualan',
                    data: [65, 78, 90, 85, 92, 105, 110, 125, 115, 130, 140, 150], // Dummy Data
                    borderColor: '#4f46e5', // Indigo-600
                    backgroundColor: gradientBlue,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6
                },
                {
                    label: 'Collection',
                    data: [50, 70, 80, 82, 88, 95, 100, 110, 105, 120, 130, 145], // Dummy Data
                    borderColor: '#10b981', // Emerald-500
                    backgroundColor: gradientGreen,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: '#f3f4f6'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // 2. Category Chart Configuration (Doughnut)
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: ['Pareto A', 'Pareto B', 'Pareto C', 'New Item'],
            datasets: [{
                data: [45, 30, 15, 10], // Dummy Data
                backgroundColor: [
                    '#4f46e5', // Indigo
                    '#60a5fa', // Blue
                    '#9ca3af', // Gray
                    '#e5e7eb' // Light Gray
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    display: false
                } // Kita pakai custom legend HTML
            }
        }
    });
});
</script>
@endsection