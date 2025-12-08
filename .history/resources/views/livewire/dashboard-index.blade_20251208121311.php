<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">

            <div class="flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
                    <p class="text-xs text-slate-500">Monitoring data real-time.</p>
                </div>

                <div class="flex p-1 bg-slate-100 rounded-xl overflow-x-auto max-w-full">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-chart-pie"></i> Overview
                    </button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-white text-yellow-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-trophy"></i> Ranking
                    </button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
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

                <div wire:loading class="text-indigo-600 text-xs font-bold animate-pulse">
                    <i class="fas fa-spinner fa-spin mr-1"></i>
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

        <div class="grid grid-cols-1 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">📈 Tren Omzet vs Retur</h4>
                        <p class="text-xs text-slate-400">Perbandingan penjualan kotor dan barang kembali harian.</p>
                    </div>
                </div>
                <div id="chart-sales-retur" style="min-height: 350px;"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">💰 Tagihan vs Pembayaran</h4>
                        <p class="text-xs text-slate-400">Analisa cashflow harian (Piutang terbentuk vs Uang masuk).</p>
                    </div>
                </div>
                <div id="chart-ar-coll" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><i
                        class="fas fa-trophy"></i></span>
                Top 10 Produk Terlaris (Qty)
            </h4>
            <div id="chart-top-produk" style="min-height: 400px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <h4 class="font-bold text-slate-700 text-lg mb-6 flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center"><i
                        class="fas fa-crown"></i></span>
                Top 10 Pelanggan Terbaik (Value)
            </h4>
            <div id="chart-top-customer" style="min-height: 400px;"></div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-lg text-indigo-900">🎯 Target vs Realisasi (IMS)</h4>
                <span class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-bold">Omzet</span>
            </div>
            <div id="chart-ims" style="min-height: 400px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-lg text-orange-900">💸 Kualitas Piutang (AR)</h4>
                <span class="text-xs bg-orange-50 text-orange-600 px-3 py-1 rounded-full font-bold">Lancar vs
                    Macet</span>
            </div>
            <div id="chart-ar-quality" style="min-height: 400px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-50">
                <h4 class="font-bold text-lg text-emerald-900">🏪 Distribusi Toko (OA)</h4>
                <span class="text-xs bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full font-bold">Outlet
                    Active</span>
            </div>
            <div id="chart-oa" style="min-height: 400px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    let charts = {}; // Simpan instance chart
    const initData = @json($chartData);

    const initTopProdNames = @json($topProduk -> pluck('nama_item'));
    const initTopProdQty = @json($topProduk -> pluck('total_qty'));
    const initTopCustNames = @json($topCustomer - > pluck('nama_pelanggan'));
    const initTopCustVal = @json($topCustomer - > pluck('total_beli'));

    const renderAll = (data) => {
        const font = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        // Common Options for Horizontal Bar
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
            } // Hide X axis labels to look cleaner
        };

        // 1. SALES VS RETUR (Area)
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

        // 2. AR VS COLL (Bar)
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

        // 3. IMS CHART
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
            colors: ['#4f46e5', '#e2e8f0'],
            dataLabels: {
                enabled: false
            }, // Disable data labels for cleaner look or customize
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
            ...hBarOpts,
            chart: {
                type: 'bar',
                stacked: true,
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            series: [{
                name: 'Lancar',
                data: data.salesARLancar
            }, {
                name: 'Macet (>30)',
                data: data.salesARMacet
            }],
            colors: ['#10b981', '#ef4444'],
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
            ...hBarOpts,
            series: [{
                name: 'Realisasi',
                data: data.salesRealOA
            }, {
                name: 'Target',
                data: data.salesTargetOA
            }],
            colors: ['#059669', '#d1fae5'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames
            },
        });
        charts.oa.render();
    };

    // Render Top Charts (Static)
    const renderTop = () => {
        const hBarTop = {
            chart: {
                type: 'bar',
                height: 400,
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
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                }
            },
        };

        // Top Prod
        charts.topProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...hBarTop,
            series: [{
                name: 'Qty',
                data: initTopProdQty
            }],
            xaxis: {
                categories: initTopProdNames
            },
            colors: ['#3b82f6'],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (v) => new Intl.NumberFormat('id-ID').format(v) + " Unit",
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            }
        });
        charts.topProd.render();

        // Top Cust
        charts.topCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...hBarTop,
            series: [{
                name: 'Total',
                data: initTopCustVal
            }],
            xaxis: {
                categories: initTopCustNames
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt",
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
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