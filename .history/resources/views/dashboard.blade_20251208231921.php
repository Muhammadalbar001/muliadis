<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }">

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
                <div wire:loading class="text-indigo-600 text-xs font-bold animate-pulse"><i
                        class="fas fa-spinner fa-spin"></i></div>
            </div>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">📈 Tren Omzet vs Retur</h4>
                <div id="chart-sales-retur" style="min-height: 350px;"></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">💰 Tagihan vs Pembayaran</h4>
                <div id="chart-ar-coll" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms
        class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:ignore>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i
                        class="fas fa-trophy"></i></span>Top Produk (Qty)
            </h4>
            <div id="chart-top-produk" style="min-height: 400px;"></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><i
                        class="fas fa-crown"></i></span>Top Pelanggan (Omzet)
            </h4>
            <div id="chart-top-customer" style="min-height: 400px;"></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center"><i
                        class="fas fa-truck"></i></span>Top Supplier (Omzet)
            </h4>
            <div id="chart-top-supplier" style="min-height: 400px;"></div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-lg text-indigo-900 mb-4 pb-2 border-b border-slate-50">🎯 Top 10 Sales Performance
                (Omzet)</h4>
            <div id="chart-ims" style="min-height: 400px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let charts = {};

    // PERBAIKAN: Ambil 'chartData' dari Controller
    // Di controller, kita kirim lewat compact('chartData')
    const initData = @json($chartData);

    const renderAll = (data) => {
        const font = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        const hBarOpts = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (v) => v,
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            xaxis: {
                labels: {
                    show: false
                }
            }
        };

        // --- 1. Top Produk ---
        if (charts.topProd) charts.topProd.destroy();
        charts.topProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...hBarOpts,
            series: [{
                name: 'Qty',
                data: data.topProdVal
            }],
            xaxis: {
                categories: data.topProdNames
            },
            colors: ['#3b82f6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => new Intl.NumberFormat('id-ID').format(v) + " Unit"
            }
        });
        charts.topProd.render();

        // --- 2. Top Customer ---
        if (charts.topCust) charts.topCust.destroy();
        charts.topCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...hBarOpts,
            series: [{
                name: 'Omzet',
                data: data.topCustVal
            }],
            xaxis: {
                categories: data.topCustNames
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt"
            }
        });
        charts.topCust.render();

        // --- 3. Top Supplier ---
        if (charts.topSupp) charts.topSupp.destroy();
        charts.topSupp = new ApexCharts(document.querySelector("#chart-top-supplier"), {
            ...hBarOpts,
            series: [{
                name: 'Omzet',
                data: data.topSuppVal
            }],
            xaxis: {
                categories: data.topSuppNames
            },
            colors: ['#ec4899'],
            dataLabels: {
                enabled: true,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt"
            }
        });
        charts.topSupp.render();

        // --- 4. Salesman Chart ---
        if (charts.ims) charts.ims.destroy();
        charts.ims = new ApexCharts(document.querySelector("#chart-ims"), {
            ...hBarOpts,
            series: [{
                name: 'Realisasi',
                data: data.salesRealIMS
            }, {
                name: 'Target',
                data: data.salesTargetIMS
            }],
            xaxis: {
                categories: data.salesNames
            },
            colors: ['#4f46e5', '#e2e8f0'],
            dataLabels: {
                enabled: false
            }
        });
        charts.ims.render();

        // --- 5. Tren Sales Retur ---
        if (charts.trend) charts.trend.destroy();
        charts.trend = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                name: 'Penjualan',
                data: data.sales
            }, {
                name: 'Retur',
                data: data.retur
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#6366f1', '#ef4444'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: data.dates,
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.4,
                    opacityTo: 0.05
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.trend.render();

        // --- 6. Tren AR Coll ---
        if (charts.arColl) charts.arColl.destroy();
        charts.arColl = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                name: 'Tagihan Baru',
                data: data.ar
            }, {
                name: 'Bayar',
                data: data.coll
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 3,
                    columnWidth: '60%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.dates,
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.arColl.render();
    };

    // Render Awal
    if (initData) renderAll(initData);

    // Update Saat Filter Berubah
    Livewire.on('update-charts', (event) => {
        renderAll(event.data || event[0].data);
    });
});
</script>