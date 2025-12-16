<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }" x-init="$watch('activeTab', value => {
         if (value === 'ranking') {
             setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 200);
         }
     })">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-6">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
                    <p class="text-xs text-slate-500">Monitoring data real-time.</p>
                </div>
                <div class="flex p-1 bg-slate-100 rounded-xl overflow-x-auto">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-chart-pie"></i> Overview</button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-white text-yellow-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-trophy"></i> Ranking</button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-user-tie"></i> Salesman</button>
                </div>
            </div>

            <div class="flex gap-2 items-center">
                <select wire:model.live="filterBulan"
                    class="border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2">
                    @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                        {{ date('F', mktime(0,0,0,$i,10)) }}</option> @endfor
                </select>
                <select wire:model.live="filterTahun"
                    class="border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2">
                    @for($y=date('Y'); $y>=2023; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                </select>
                <select wire:model.live="filterCabang"
                    class="border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2 w-32">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
                <!-- <div wire:loading class="text-indigo-600 text-xs font-bold animate-pulse"><i
                        class="fas fa-spinner fa-spin"></i></div>
            </div> -->
        </div>
    </div>

    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-1">

            <div
                class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-coins text-6xl text-indigo-600"></i></div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 mt-1">Rp
                    {{ number_format($totalOmzet / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-slate-400 mt-2">Real: {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Retur</p>
                <h3 class="text-2xl font-extrabold text-red-500 mt-1">Rp
                    {{ number_format($returSum / 1000000, 1, ',', '.') }} Jt</h3>
                <div class="mt-2 flex items-center gap-2"><span
                        class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold">Rasio:
                        {{ number_format($persenRetur, 2) }}%</span></div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Outlet Active (OA)</p>
                <h3 class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($totalOa) }} Toko</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Effective Call (EC)</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalEc) }} Nota</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">📈 Tren Omzet vs Retur</h4>
                <div class="relative h-72 w-full">
                    <canvas id="salesReturChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">💰 Tagihan vs Pembayaran</h4>
                <div class="relative h-72 w-full">
                    <canvas id="arCollectionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms
        class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i
                        class="fas fa-trophy"></i></span>Top Produk (Qty)
            </h4>
            <div class="relative h-80 w-full">
                <canvas id="topProductChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><i
                        class="fas fa-crown"></i></span>Top Pelanggan (Omzet)
            </h4>
            <div class="relative h-80 w-full">
                <canvas id="topCustomerChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center"><i
                        class="fas fa-truck"></i></span>Top Supplier (Omzet)
            </h4>
            <div class="relative h-80 w-full">
                <canvas id="topSupplierChart"></canvas>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-lg text-indigo-900 mb-4 pb-2 border-b border-slate-50">🎯 Top 10 Sales Performance
                (Omzet)</h4>
            <div class="relative h-96 w-full">
                <canvas id="imsChart"></canvas>
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
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        };

        // 1. Sales vs Retur (Line Chart)
        if (charts.salesRetur) charts.salesRetur.destroy();
        const ctxSR = document.getElementById('salesReturChart').getContext('2d');
        charts.salesRetur = new Chart(ctxSR, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [{
                        label: 'Penjualan',
                        data: data.sales,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Retur',
                        data: data.retur,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. AR vs Coll (Bar Chart)
        if (charts.arColl) charts.arColl.destroy();
        const ctxAC = document.getElementById('arCollectionChart').getContext('2d');
        charts.arColl = new Chart(ctxAC, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [{
                        label: 'Piutang',
                        data: data.ar,
                        backgroundColor: '#f97316',
                        borderRadius: 3
                    },
                    {
                        label: 'Collection',
                        data: data.coll,
                        backgroundColor: '#10b981',
                        borderRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 3. Top Produk (Horizontal Bar)
        if (charts.topProd) charts.topProd.destroy();
        charts.topProd = new Chart(document.getElementById('topProductChart'), {
            type: 'bar',
            data: {
                labels: data.topProdNames,
                datasets: [{
                    label: 'Qty',
                    data: data.topProdVal,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // 4. Top Customer (Horizontal Bar)
        if (charts.topCust) charts.topCust.destroy();
        charts.topCust = new Chart(document.getElementById('topCustomerChart'), {
            type: 'bar',
            data: {
                labels: data.topCustNames,
                datasets: [{
                    label: 'Omzet',
                    data: data.topCustVal,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // 5. Top Supplier (Horizontal Bar)
        if (charts.topSupp) charts.topSupp.destroy();
        charts.topSupp = new Chart(document.getElementById('topSupplierChart'), {
            type: 'bar',
            data: {
                labels: data.topSuppNames,
                datasets: [{
                    label: 'Omzet',
                    data: data.topSuppVal,
                    backgroundColor: '#f59e0b',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // 6. Salesman Chart (Vertical Bar)
        if (charts.ims) charts.ims.destroy();
        charts.ims = new Chart(document.getElementById('imsChart'), {
            type: 'bar',
            data: {
                labels: data.salesNames,
                datasets: [{
                        label: 'Target',
                        data: data.salesTargetIMS,
                        backgroundColor: '#e2e8f0',
                        borderRadius: 4
                    },
                    {
                        label: 'Realisasi',
                        data: data.salesRealIMS,
                        backgroundColor: '#4f46e5',
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    };

    // Render Awal
    if (initData) renderCharts(initData);

    // Render Ulang saat Filter Berubah
    Livewire.on('update-charts', (event) => {
        renderCharts(event.data || event[0].data);
    });
});
</script>
@endsection