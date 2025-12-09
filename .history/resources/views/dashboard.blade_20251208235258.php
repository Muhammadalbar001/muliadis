@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($salesSum, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600"><i class="fas fa-chart-line fa-lg"></i></div>
        </div>
        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Retur</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($returSum, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-red-50 rounded-lg text-red-600"><i class="fas fa-undo fa-lg"></i></div>
        </div>
        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Sisa Piutang (AR)</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($arSum, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg text-orange-600"><i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
        </div>
        <div
            class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500">Collection</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($collSum, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600"><i class="fas fa-wallet fa-lg"></i></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tren Penjualan vs Retur</h3>
            <div class="relative h-72 w-full">
                <canvas id="salesReturChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Piutang vs Pembayaran</h3>
            <div class="relative h-72 w-full">
                <canvas id="arCollectionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Produk (Qty)</h3>
            <div class="relative h-80 w-full">
                <canvas id="productChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Customer (Value)</h3>
            <div class="relative h-80 w-full">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', function() {

    let charts = {};

    // 1. DATA DARI CONTROLLER (Mapping Variable Benar)
    // $chartData dari Controller berisi array ['labels', 'sales', 'retur', 'ar', 'coll']
    const initChart = (data) => {
        const labels = data.labels; // Label Tanggal (Harian)

        // --- CHART 1: SALES VS RETUR ---
        if (charts.salesRetur) charts.salesRetur.destroy();
        const ctxSR = document.getElementById('salesReturChart').getContext('2d');
        charts.salesRetur = new Chart(ctxSR, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Penjualan',
                        data: data.sales,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Retur',
                        data: data.retur,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // --- CHART 2: AR VS COLLECTION ---
        if (charts.arColl) charts.arColl.destroy();
        const ctxAC = document.getElementById('arCollectionChart').getContext('2d');
        charts.arColl = new Chart(ctxAC, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Piutang Baru',
                        data: data.ar,
                        backgroundColor: '#f97316'
                    },
                    {
                        label: 'Collection',
                        data: data.coll,
                        backgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    };

    // --- CHART 3: TOP PRODUK (RANKING) ---
    // Data ini diambil dari variabel $topProduk yang dikirim via compact()
    const renderRanking = () => {
        const pLabels = @json($topProduk -> pluck('nama_item'));
        const pData = @json($topProduk -> pluck('total_qty'));

        const ctxProd = document.getElementById('productChart').getContext('2d');
        new Chart(ctxProd, {
            type: 'bar',
            data: {
                labels: pLabels,
                datasets: [{
                    label: 'Qty Terjual',
                    data: pData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // --- CHART 4: TOP CUSTOMER (RANKING) ---
        const cLabels = @json($topCustomer -> pluck('nama_pelanggan'));
        const cData = @json($topCustomer - > pluck('total_beli'));

        const ctxCust = document.getElementById('customerChart').getContext('2d');
        new Chart(ctxCust, {
            type: 'bar',
            data: {
                labels: cLabels,
                datasets: [{
                    label: 'Total Beli',
                    data: cData,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    };

    // Initial Render
    initChart(@json($chartData));
    renderRanking(); // Render ranking static (karena ranking tidak ikut update-charts di controller ini)

    // Listener Livewire (Untuk Trend Harian)
    Livewire.on('update-charts', (event) => {
        initChart(event.data || event[0].data);
    });
});
</script>
@endsection