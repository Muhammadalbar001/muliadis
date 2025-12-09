@extends('layouts.app')

@section('header', 'Executive Dashboard')

@section('content')
<div class="space-y-6 font-jakarta">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="flex gap-4 w-full md:w-auto items-center">
            <div class="flex items-center bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm">
                <input type="date" wire:model.live="startDate" class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent">
                <span class="text-slate-300">-</span>
                <input type="date" wire:model.live="endDate" class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent">
            </div>

            <div class="relative w-40" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="w-full bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold flex items-center justify-between shadow-sm">
                    <span class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto" style="display: none;">
                    @foreach($optCabang as $c) 
                    <label class="flex items-center px-2 py-1.5 hover:bg-slate-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang" class="rounded border-slate-300 text-indigo-600 mr-2 h-3 w-3">
                        <span class="text-xs text-slate-600">{{ $c }}</span>
                    </label> 
                    @endforeach
                </div>
            </div>

            <div class="relative w-40" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="w-full bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold flex items-center justify-between shadow-sm">
                    <span class="truncate">{{ empty($filterSales) ? 'Semua Sales' : count($filterSales).' Dipilih' }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto" style="display: none;">
                    @foreach($optSales as $s) 
                    <label class="flex items-center px-2 py-1.5 hover:bg-slate-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $s }}" wire:model.live="filterSales" class="rounded border-slate-300 text-indigo-600 mr-2 h-3 w-3">
                        <span class="text-xs text-slate-600">{{ $s }}</span>
                    </label> 
                    @endforeach
                </div>
            </div>

            <div wire:loading class="text-indigo-600 text-xs font-bold animate-pulse"><i class="fas fa-spinner fa-spin"></i></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 mt-1">Rp {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-gray-400 mt-1">Real: {{ number_format($salesSum, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600"><i class="fas fa-chart-line fa-lg"></i></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Retur</p>
                <h3 class="text-2xl font-extrabold text-red-500 mt-1">Rp {{ number_format($returSum / 1000000, 1, ',', '.') }} Jt</h3>
                <div class="mt-1"><span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">Rasio: {{ number_format($persenRetur, 2) }}%</span></div>
            </div>
            <div class="p-3 bg-red-50 rounded-lg text-red-600"><i class="fas fa-undo fa-lg"></i></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Piutang Baru (AR)</p>
                <h3 class="text-2xl font-extrabold text-orange-500 mt-1">Rp {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt</h3>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg text-orange-600"><i class="fas fa-file-invoice-dollar fa-lg"></i></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500 flex justify-between items-center transition hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Collection</p>
                <h3 class="text-2xl font-extrabold text-emerald-500 mt-1">Rp {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600"><i class="fas fa-wallet fa-lg"></i></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Tren Penjualan vs Retur</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesReturChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Piutang vs Pembayaran</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="arCollectionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2"><i class="fas fa-box text-blue-500"></i> Top 10 Produk (Qty)</h3>
            <div class="relative h-80 w-full">
                <canvas id="topProductChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2"><i class="fas fa-users text-purple-500"></i> Top 10 Pelanggan (Omzet)</h3>
            <div class="relative h-80 w-full">
                <canvas id="topCustomerChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2"><i class="fas fa-truck text-orange-500"></i> Top 10 Supplier (Omzet)</h3>
            <div class="relative h-80 w-full">
                <canvas id="topSupplierChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', () => {

    let charts = {};
    const initData = @json($chartData);

    const renderCharts = (data) => {
        
        const commonOptions = {
            indexAxis: 'y', // Horizontal Bar
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, grid: { display: false } }, y: { grid: { display: false } } }
        };

        // 1. Sales vs Retur (Line Chart)
        if(charts.salesRetur) charts.salesRetur.destroy();
        const ctxSR = document.getElementById('salesReturChart').getContext('2d');
        charts.salesRetur = new Chart(ctxSR, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [
                    { label: 'Penjualan', data: data.sales, borderColor: '#6366f1', backgroundColor: 'rgba(99, 102, 241, 0.1)', fill: true, tension: 0.4 },
                    { label: 'Retur', data: data.retur, borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', fill: true, tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
        });

        // 2. AR vs Coll (Bar Chart)
        if(charts.arColl) charts.arColl.destroy();
        const ctxAC = document.getElementById('arCollectionChart').getContext('2d');
        charts.arColl = new Chart(ctxAC, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [
                    { label: 'Piutang', data: data.ar, backgroundColor: '#f97316' },
                    { label: 'Collection', data: data.coll, backgroundColor: '#10b981' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
        });

        // 3. Top Produk (Horizontal Bar)
        if(charts.topProd) charts.topProd.destroy();
        charts.topProd = new Chart(document.getElementById('topProductChart'), {
            type: 'bar',
            data: {
                labels: data.topProdNames,
                datasets: [{ label: 'Qty', data: data.topProdVal, backgroundColor: '#3b82f6', borderRadius: 4 }]
            },
            options: commonOptions
        });

        // 4. Top Customer (Horizontal Bar)
        if(charts.topCust) charts.topCust.destroy();
        charts.topCust = new Chart(document.getElementById('topCustomerChart'), {
            type: 'bar',
            data: {
                labels: data.topCustNames,
                datasets: [{ label: 'Omzet', data: data.topCustVal, backgroundColor: '#8b5cf6', borderRadius: 4 }]
            },
            options: commonOptions
        });

        // 5. Top Supplier (Horizontal Bar)
        if(charts.topSupp) charts.topSupp.destroy();
        charts.topSupp = new Chart(document.getElementById('topSupplierChart'), {
            type: 'bar',
            data: {
                labels: data.topSuppNames,
                datasets: [{ label: 'Omzet', data: data.topSuppVal, backgroundColor: '#f59e0b', borderRadius: 4 }]
            },
            options: commonOptions
        });
    };

    // Render Awal
    if(initData) renderCharts(initData);

    // Render Ulang saat Filter Berubah
    Livewire.on('update-charts', (event) => {
        renderCharts(event.data || event[0].data);
    });
});
</script>
@endsection