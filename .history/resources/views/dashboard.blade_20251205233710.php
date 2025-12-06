@extends('layouts.app')

@section('header', 'Dashboard Utama')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 0</h3>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500 flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Retur</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 0</h3>
            </div>
            <div class="p-3 bg-red-50 rounded-lg text-red-600">
                <i class="fas fa-undo fa-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Piutang (AR)</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 0</h3>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500 flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Collection (Lunas)</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 0</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Penjualan vs Retur</h3>
            <div class="relative h-72 w-full">
                <canvas id="salesReturChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Piutang (AR) vs Collection</h3>
            <div class="relative h-72 w-full">
                <canvas id="arCollectionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Produk Penjualan</h3>
        <div class="relative h-80 w-full">
            <canvas id="productChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. CONFIG CHART PENJUALAN VS RETUR ---
    const ctxSalesRetur = document.getElementById('salesReturChart').getContext('2d');
    new Chart(ctxSalesRetur, {
        type: 'bar', // Bisa diganti 'line'
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                'Des'],
            datasets: [{
                    label: 'Penjualan',
                    data: [120, 150, 180, 200, 170, 210, 230, 250, 240, 280, 300,
                    320], // Data Dummy
                    backgroundColor: '#6366f1', // Indigo
                    borderRadius: 4
                },
                {
                    label: 'Retur',
                    data: [10, 15, 20, 18, 12, 25, 30, 20, 15, 28, 30, 25], // Data Dummy
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
                    }
                }
            }
        }
    });

    // --- 2. CONFIG CHART AR VS COLLECTION ---
    const ctxArCol = document.getElementById('arCollectionChart').getContext('2d');
    new Chart(ctxArCol, {
        type: 'line', // Line chart lebih cocok untuk melihat tren cashflow
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                'Des'],
            datasets: [{
                    label: 'Piutang (AR)',
                    data: [80, 90, 85, 100, 110, 105, 120, 130, 140, 135, 150, 160], // Data Dummy
                    borderColor: '#f97316', // Orange
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Collection (Lunas)',
                    data: [75, 85, 80, 95, 100, 100, 115, 120, 135, 130, 145, 155], // Data Dummy
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
                    }
                }
            }
        }
    });

    // --- 3. CONFIG CHART PRODUK PENJUALAN ---
    const ctxProduct = document.getElementById('productChart').getContext('2d');
    new Chart(ctxProduct, {
        type: 'bar', // Bar horizontal bagus untuk ranking produk
        data: {
            // Nama Produk
            labels: ['Paracetamol 500mg', 'Amoxicillin Sirup', 'Vitamin C 1000mg', 'Masker Medis',
                'Hand Sanitizer', 'Betadine 30ml', 'Minyak Kayu Putih', 'Suplemen Anak',
                'Termometer Digital', 'Perban Gulung'
            ],
            datasets: [{
                label: 'Qty Terjual',
                data: [1500, 1200, 1100, 950, 900, 850, 800, 750, 600, 500], // Data Dummy
                backgroundColor: [
                    '#4f46e5', '#6366f1', '#818cf8', '#3b82f6', '#60a5fa',
                    '#93c5fd', '#2563eb', '#1d4ed8', '#1e40af', '#1e3a8a'
                ],
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }, // Hide legend karena cuma 1 dataset
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Unit';
                        }
                    }
                }
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