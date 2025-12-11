<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Executive Dashboard</h1>
                    <p class="text-xs text-slate-500">Monitoring kinerja bisnis real-time.</p>
                </div>
                <div class="flex p-1 bg-slate-100 rounded-xl overflow-x-auto">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-chart-pie"></i> Overview</button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-trophy"></i> Ranking</button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                            class="fas fa-user-tie"></i> Sales Performance</button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto items-center">
                <div class="flex items-center bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent cursor-pointer">
                    <span class="text-slate-300">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-1 bg-transparent cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold flex items-center justify-between shadow-sm hover:border-emerald-300">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto"
                        style="display: none;">
                        @foreach($optCabang as $cab)
                        <label class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-emerald-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div wire:loading class="text-emerald-600"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider">Total Penjualan</p>
                    <h3 class="text-2xl font-extrabold mt-1">Rp {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt
                    </h3>
                    <p class="text-xs mt-1 text-emerald-100 opacity-80">Real:
                        {{ number_format($salesSum, 0, ',', '.') }}</p>
                </div>
                <i
                    class="fas fa-chart-line absolute right-4 top-4 text-white/20 text-6xl group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm relative overflow-hidden group">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Retur</p>
                <h3 class="text-2xl font-extrabold text-rose-500 mt-1">Rp
                    {{ number_format($returSum / 1000000, 1, ',', '.') }} Jt</h3>
                <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded bg-rose-50 border border-rose-100">
                    <span class="text-[10px] font-bold text-rose-600">Rasio:
                        {{ number_format($persenRetur, 2) }}%</span>
                </div>
                <i
                    class="fas fa-undo absolute right-4 top-4 text-rose-100 text-6xl group-hover:rotate-[-12deg] transition-transform"></i>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm relative overflow-hidden">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Piutang Terbentuk</p>
                <h3 class="text-2xl font-extrabold text-orange-500 mt-1">Rp
                    {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-xs text-slate-400 mt-1">Tagihan Baru Periode Ini</p>
                <i class="fas fa-file-invoice-dollar absolute right-4 top-4 text-orange-100 text-6xl"></i>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-cyan-100 shadow-sm relative overflow-hidden">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Uang Masuk (Coll)</p>
                <h3 class="text-2xl font-extrabold text-cyan-600 mt-1">Rp
                    {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-xs text-slate-400 mt-1">Pembayaran Diterima</p>
                <i class="fas fa-wallet absolute right-4 top-4 text-cyan-100 text-6xl"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><i
                            class="fas fa-chart-area"></i></span>
                    Tren Omzet vs Retur
                </h4>
                <div id="chart-sales-retur" style="min-height: 350px;"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center"><i
                            class="fas fa-balance-scale"></i></span>
                    Tagihan vs Pembayaran
                </h4>
                <div id="chart-ar-coll" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-box text-blue-500"></i> Top 10 Produk (Qty)
            </h4>
            <div id="chart-top-produk" style="min-height: 400px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-users text-purple-500"></i> Top 10 Pelanggan (Omzet)
            </h4>
            <div id="chart-top-customer" style="min-height: 400px;"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-truck text-pink-500"></i> Top 10 Supplier (Omzet)
            </h4>
            <div id="chart-top-supplier" style="min-height: 400px;"></div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-lg text-indigo-900 mb-4 pb-2 border-b border-slate-50">
                🎯 Top 10 Sales Performance (Target vs Realisasi)
            </h4>
            <div id="chart-sales-perf" style="min-height: 500px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('livewire:init', () => {
    let charts = {};
    const initData = @json($chartData);

    const renderCharts = (data) => {
        const font = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        // 1. Chart Sales vs Retur
        if (charts.sr) charts.sr.destroy();
        charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                    name: 'Penjualan',
                    data: data.trend_sales
                },
                {
                    name: 'Retur',
                    data: data.trend_retur
                }
            ],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#10b981', '#f43f5e'], // Emerald & Rose
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
        charts.sr.render();

        // 2. Chart AR vs Coll
        if (charts.ac) charts.ac.destroy();
        charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                    name: 'Piutang Baru',
                    data: data.trend_ar
                },
                {
                    name: 'Pelunasan',
                    data: data.trend_coll
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#f97316', '#06b6d4'], // Orange & Cyan
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
        charts.ac.render();

        // Options Ranking (Full Width Horizontal)
        const rankingOpts = {
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
                    borderRadius: 2
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                style: {
                    fontSize: '10px',
                    colors: ['#fff']
                }
            },
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                }
            }
        };

        // 3. Top Produk
        if (charts.tp) charts.tp.destroy();
        charts.tp = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...rankingOpts,
            series: [{
                name: 'Qty',
                data: data.top_produk_val
            }],
            xaxis: {
                categories: data.top_produk_lbl
            },
            colors: ['#3b82f6'],
            tooltip: {
                y: {
                    formatter: (v) => new Intl.NumberFormat('id-ID').format(v) + " Unit"
                }
            }
        });
        charts.tp.render();

        // 4. Top Customer
        if (charts.tc) charts.tc.destroy();
        charts.tc = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...rankingOpts,
            series: [{
                name: 'Omzet',
                data: data.top_cust_val
            }],
            xaxis: {
                categories: data.top_cust_lbl
            },
            colors: ['#8b5cf6'],
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            },
            dataLabels: {
                ...rankingOpts.dataLabels,
                formatter: fmtJt
            }
        });
        charts.tc.render();

        // 5. Top Supplier
        if (charts.ts) charts.ts.destroy();
        charts.ts = new ApexCharts(document.querySelector("#chart-top-supplier"), {
            ...rankingOpts,
            series: [{
                name: 'Omzet',
                data: data.top_supp_val
            }],
            xaxis: {
                categories: data.top_supp_lbl
            },
            colors: ['#ec4899'],
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            },
            dataLabels: {
                ...rankingOpts.dataLabels,
                formatter: fmtJt
            }
        });
        charts.ts.render();

        // 6. Sales Performance
        if (charts.sp) charts.sp.destroy();
        charts.sp = new ApexCharts(document.querySelector("#chart-sales-perf"), {
            series: [{
                    name: 'Realisasi',
                    data: data.sales_real
                },
                {
                    name: 'Target',
                    data: data.sales_target
                }
            ],
            chart: {
                type: 'bar',
                height: 500,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: data.sales_names
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            colors: ['#4f46e5', '#cbd5e1'],
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.sp.render();
    };

    if (initData) renderCharts(initData);

    Livewire.on('update-charts', (event) => {
        const newData = event.data || event[0].data;
        renderCharts(newData);
    });
});
</script>
</div>