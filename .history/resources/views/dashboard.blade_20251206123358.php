@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Penjualan ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Retur ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalRetur, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3 bg-red-50 rounded-lg text-red-600">
                <i class="fas fa-undo fa-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Sisa Piutang (AR)</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalAR, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Collection ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalCollection, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Tren Penjualan vs Retur</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Tahun {{ date('Y') }}</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesReturChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Piutang vs Pembayaran</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Tahun {{ date('Y') }}</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="arCollectionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Produk Terlaris (Qty)</h3>
        <div class="relative h-80 w-full">
            <canvas id="productChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- DATA DARI CONTROLLER ---
    const salesData = @json($salesData);
    const returData = @json($returData);
    const arData = @json($arData);
    const colData = @json($collectionData);

    const productLabels = @json($topProductLabels);
    const productData = @json($topProductData);

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // --- 1. CONFIG CHART PENJUALAN VS RETUR ---
    const ctxSalesRetur = document.getElementById('salesReturChart').getContext('2d');
    new Chart(ctxSalesRetur, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'Penjualan',
                    data: salesData,
                    backgroundColor: '#6366f1', // Indigo
                    borderRadius: 4
                },
                {
                    label: 'Retur',
                    data: returData,
                    backgroundColor: '#ef4444', // Red
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5]
                    },
                    ticks: {
                        // Format Rupiah Singkat (K, M, B)
                        callback: function(value) {
                            if (value >= 1000000000) return 'Rp ' + (value / 1000000000).toFixed(
                                1) + 'M';
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) +
                            'Jt';
                            return value;
                        }
                    }
                }
            }
        }
    });

    // --- 2. CONFIG CHART AR VS COLLECTION ---
    const ctxArCol = document.getElementById('arCollectionChart').getContext('2d');
    new Chart(ctxArCol, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                    label: 'Piutang Baru (AR)',
                    data: arData,
                    borderColor: '#f97316', // Orange
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Pembayaran (Collection)',
                    data: colData,
                    borderColor: '#10b981', // Emerald
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5]
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000000) return (value / 1000000000).toFixed(1) + 'M';
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'Jt';
                            return value;
                        }
                    }
                }
            }
        }
    });

    // --- 3. CONFIG CHART PRODUK PENJUALAN ---
    const ctxProduct = document.getElementById('productChart').getContext('2d');
    new Chart(ctxProduct, {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Qty Terjual',
                data: productData,
                backgroundColor: [
                    '#4f46e5', '#6366f1', '#818cf8', '#3b82f6', '#60a5fa',
                    '#93c5fd', '#2563eb', '#1d4ed8', '#1e40af', '#1e3a8a'
                ],
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
            },
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

});
</script>
@endsection