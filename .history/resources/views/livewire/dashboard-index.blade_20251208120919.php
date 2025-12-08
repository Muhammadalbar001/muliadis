<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">

            <div class="flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
                    <p class="text-xs text-slate-500">Monitoring data real-time.</p>
                </div>

                <div class="flex p-1 bg-slate-100 rounded-xl">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fas fa-chart-pie"></i> Overview
                    </button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-white text-yellow-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fas fa-trophy"></i> Ranking
                    </button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fas fa-user-tie"></i> Kinerja Sales
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto items-center">
                <div class="flex items-center bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent">
                    <span class="text-slate-300">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold flex items-center justify-between shadow-sm">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto">
                        @foreach($optCabang as $cab)
                        <label class="flex items-center px-2 py-1.5 hover:bg-slate-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div wire:loading class="text-indigo-600">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-1">
            <div
                class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-chart-area text-6xl text-indigo-600"></i></div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 mt-1">Rp
                    {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-slate-400 mt-2">Real: {{ number_format($salesSum, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Retur</p>
                <h3 class="text-2xl font-extrabold text-red-500 mt-1">Rp
                    {{ number_format($returSum / 1000000, 1, ',', '.') }} Jt</h3>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold">Rasio:
                        {{ number_format($persenRetur, 2) }}%</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Piutang Baru (AR)</p>
                <h3 class="text-2xl font-extrabold text-orange-500 mt-1">Rp
                    {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-slate-400 mt-2">Terbentuk periode ini</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Uang Masuk (Coll)</p>
                <h3 class="text-2xl font-extrabold text-emerald-500 mt-1">Rp
                    {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-slate-400 mt-2">Diterima periode ini</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-700 text-sm mb-4">📈 Tren Omzet vs Retur</h4>
                <div id="chart-sales-retur" style="min-height: 300px;"></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-700 text-sm mb-4">💰 Tagihan vs Pembayaran</h4>
                <div id="chart-ar-coll" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms
        class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5"><i class="fas fa-box text-6xl"></i></div>
            <h4 class="font-bold text-slate-700 text-sm mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i
                        class="fas fa-trophy"></i></span>
                Top 10 Produk (Qty)
            </h4>
            <div id="chart-top-produk" style="min-height: 380px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5"><i class="fas fa-users text-6xl"></i></div>
            <h4 class="font-bold text-slate-700 text-sm mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><i
                        class="fas fa-crown"></i></span>
                Top 10 Customer (Value)
            </h4>
            <div id="chart-top-customer" style="min-height: 380px;"></div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms
        class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:ignore>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-sm text-indigo-900">🎯 Target vs Realisasi (IMS)</h4>
            </div>
            <div id="chart-ims" style="min-height: 350px;"></div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-sm text-orange-900">💸 Kualitas Piutang (AR)</h4>
            </div>
            <div id="chart-ar-quality" style="min-height: 350px;"></div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-sm text-emerald-900">🏪 Distribusi Toko (OA)</h4>
            </div>
            <div id="chart-oa" style="min-height: 350px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    let charts = {}; // Simpan semua instance chart
    const initData = @json($chartData);

    // Data Top 10 (Static Initial)
    const initTop = {
        pNames: @json($topProduk -> pluck('nama_item')),
        pQty: @json($topProduk -> pluck('total_qty')),
        cNames: @json($topCustomer -> pluck('nama_pelanggan')),
        cVal: @json($topCustomer - > pluck('total_beli'))
    };

    const renderAll = (data) => {
        const font = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        // 1. SALES VS RETUR
        if (charts.salesRetur) charts.salesRetur.destroy();
        charts.salesRetur = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                name: 'Penjualan',
                data: data.sales
            }, {
                name: 'Retur',
                data: data.retur
            }],
            chart: {
                type: 'area',
                height: 300,
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
                },
                tooltip: {
                    enabled: false
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
        charts.salesRetur.render();

        // 2. AR VS COLL
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
                height: 300,
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

        // 3. IMS CHART
        if (charts.ims) charts.ims.destroy();
        charts.ims = new ApexCharts(document.querySelector("#chart-ims"), {
            series: [{
                name: 'Realisasi',
                data: data.salesRealIMS
            }, {
                name: 'Target',
                data: data.salesTargetIMS
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#4f46e5', '#e2e8f0'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    formatter: (v) => (v / 1000000).toFixed(0) + "Jt"
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.ims.render();

        // 4. AR QUALITY
        if (charts.arQ) charts.arQ.destroy();
        charts.arQ = new ApexCharts(document.querySelector("#chart-ar-quality"), {
            series: [{
                name: 'Lancar',
                data: data.salesARLancar
            }, {
                name: 'Macet (>30)',
                data: data.salesARMacet
            }],
            chart: {
                type: 'bar',
                stacked: true,
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#10b981', '#ef4444'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    show: false
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.arQ.render();

        // 5. OA CHART
        if (charts.oa) charts.oa.destroy();
        charts.oa = new ApexCharts(document.querySelector("#chart-oa"), {
            series: [{
                name: 'Realisasi',
                data: data.salesRealOA
            }, {
                name: 'Target',
                data: data.salesTargetOA
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#059669', '#d1fae5'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames
            },
        });
        charts.oa.render();
    };

    // Render Top Charts (Sekali Saja)
    const renderTop = () => {
        const opts = {
            chart: {
                type: 'bar',
                height: 380,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (v) => v,
                style: {
                    fontSize: '10px'
                }
            },
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                }
            },
        };

        charts.topProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...opts,
            series: [{
                name: 'Qty',
                data: initTop.pQty
            }],
            xaxis: {
                categories: initTop.pNames
            },
            colors: ['#3b82f6']
        });
        charts.topProd.render();

        charts.topCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...opts,
            series: [{
                name: 'Total',
                data: initTop.cVal
            }],
            xaxis: {
                categories: initTop.cNames
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                ...opts.dataLabels,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt"
            },
            tooltip: {
                y: {
                    formatter: (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        });
        charts.topCust.render();
    }

    renderAll(initData);
    renderTop();

    Livewire.on('update-charts', (event) => {
        renderAll(event.data || event[0].data);
    });
});
</script>