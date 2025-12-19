<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ activeTab: 'overview' }"
    x-init="$watch('activeTab', value => {
         if (value === 'ranking' || value === 'salesman') {
             setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 200);
         }
     })">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row items-center gap-8 w-full lg:w-auto">
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Executive <span class="text-blue-500">Dashboard</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1 dark:text-slate-400 text-slate-500">
                        Mulia Distribution System</p>
                </div>

                <div
                    class="flex p-1 rounded-2xl border transition-all dark:bg-neutral-900/50 dark:border-white/5 bg-slate-100 border-slate-200">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-chart-pie text-xs"></i> Overview
                    </button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-trophy text-xs"></i> Ranking
                    </button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-user-tie text-xs"></i> Sales
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full lg:w-auto justify-end font-jakarta">
                <div
                    class="flex items-center gap-2 border rounded-xl px-3 py-1.5 transition-all dark:bg-neutral-900 dark:border-white/10 bg-white border-slate-200 shadow-sm">
                    <input type="date" wire:model.live="startDate"
                        class="bg-transparent border-none text-[11px] font-black uppercase tracking-widest focus:ring-0 p-0 text-blue-500 cursor-pointer">
                    <span class="opacity-30">/</span>
                    <input type="date" wire:model.live="endDate"
                        class="bg-transparent border-none text-[11px] font-black uppercase tracking-widest focus:ring-0 p-0 text-blue-500 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px]
                        dark:bg-neutral-900 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Regional' : count($filterCabang).' Selected' }}</span>
                        <i class="fas fa-chevron-down opacity-40 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-2 w-full border rounded-xl shadow-2xl p-2 max-h-48 overflow-y-auto right-0"
                        :class="darkMode ? 'bg-slate-900 border-slate-800 shadow-black' : 'bg-white border-slate-200'">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-2 hover:bg-blue-500/10 rounded-lg cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 h-3 w-3">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-blue-400 dark:text-slate-400 text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="applyFilter"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all h-[38px]">
                    <i class="fas fa-sync-alt mr-1"></i> Terapkan
                </button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

        <div x-show="activeTab === 'overview'" x-transition.opacity class="space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div
                    class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden
                     dark:bg-blue-600/5 dark:border-blue-500/10 dark:shadow-2xl dark:shadow-blue-900/10 bg-white border-blue-100 shadow-sm hover:border-blue-300">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-widest dark:text-blue-400 text-slate-400">
                            Gross Revenue</p>
                        <h3 class="text-3xl font-black mt-2 tracking-tighter dark:text-white text-blue-600">Rp
                            {{ $this->formatCompact($salesSum) }}</h3>
                        <p class="text-[10px] mt-4 font-bold opacity-40 italic uppercase tracking-tighter">Real:
                            {{ number_format($salesSum, 0, ',', '.') }}</p>
                    </div>
                    <i
                        class="fas fa-chart-line absolute -right-4 -bottom-4 text-7xl opacity-[0.03] dark:text-blue-500 text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>

                <div
                    class="p-6 rounded-[2.5rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-red-100 shadow-sm hover:border-red-300">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500 opacity-80">Total Return</p>
                    <h3 class="text-3xl font-black mt-2 tracking-tighter text-red-500">Rp
                        {{ $this->formatCompact($returSum) }}</h3>
                    <div
                        class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest dark:bg-red-500/10 dark:text-red-400 bg-red-50 text-red-600">
                        Ratio: {{ number_format($persenRetur, 2) }}%
                    </div>
                </div>

                <div
                    class="p-6 rounded-[2.5rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-blue-100 shadow-sm hover:border-blue-300">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 opacity-80">Outlet Active
                    </p>
                    <h3 class="text-3xl font-black mt-2 tracking-tighter dark:text-white text-blue-600">
                        {{ number_format($totalOa) }} <span class="text-xs opacity-40">Toko</span></h3>
                </div>

                <div
                    class="p-6 rounded-[2.5rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-emerald-100 shadow-sm hover:border-emerald-300">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 opacity-80">Effective
                        Call</p>
                    <h3 class="text-3xl font-black mt-2 tracking-tighter dark:text-white text-emerald-600">
                        {{ number_format($totalEc) }} <span class="text-xs opacity-40">Nota</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8" wire:ignore>
                <div
                    class="p-8 rounded-[3rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-slate-100 shadow-sm">
                    <h4
                        class="font-black text-xs uppercase tracking-[0.2em] mb-8 flex items-center gap-3 dark:text-white text-slate-800">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Daily Sales Trend
                    </h4>
                    <div id="chart-sales-retur" style="min-height: 350px;"></div>
                </div>
                <div
                    class="p-8 rounded-[3rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-slate-100 shadow-sm">
                    <h4
                        class="font-black text-xs uppercase tracking-[0.2em] mb-8 flex items-center gap-3 dark:text-white text-slate-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tagihan vs Pembayaran
                    </h4>
                    <div id="chart-ar-coll" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'ranking'" x-transition.opacity class="grid grid-cols-1 gap-8" wire:ignore>
            @foreach([
            ['id' => 'chart-top-produk', 'label' => 'Top Products (Qty)', 'color' => 'blue', 'icon' => 'fa-box'],
            ['id' => 'chart-top-customer', 'label' => 'Top Customers (Revenue)', 'color' => 'purple', 'icon' =>
            'fa-users'],
            ['id' => 'chart-top-supplier', 'label' => 'Top Suppliers (Revenue)', 'color' => 'pink', 'icon' =>
            'fa-truck']
            ] as $rank)
            <div
                class="p-8 rounded-[3rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-slate-100 shadow-sm">
                <h4
                    class="font-black text-[10px] uppercase tracking-[0.2em] mb-8 flex items-center gap-3 dark:text-white text-slate-700">
                    <span
                        class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{$rank['color']}}-500/10 text-{{$rank['color']}}-500">
                        <i class="fas {{$rank['icon']}}"></i>
                    </span> {{$rank['label']}}
                </h4>
                <div id="{{ $rank['id'] }}" style="min-height: 400px;"></div>
            </div>
            @endforeach
        </div>

        <div x-show="activeTab === 'salesman'" x-transition.opacity class="max-w-5xl mx-auto" wire:ignore>
            <div
                class="p-8 rounded-[3rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-slate-100 shadow-sm">
                <h4
                    class="font-black text-xs uppercase tracking-[0.2em] mb-10 flex items-center gap-3 dark:text-blue-300 text-indigo-900">
                    <i class="fas fa-bullseye text-lg"></i> Top 10 Sales Performance
                </h4>
                <div id="chart-sales-perf" style="min-height: 500px;"></div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let charts = {};
    const initData = @json($chartData);

    const getThemeConfig = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            text: isDark ? '#94a3b8' : '#64748b',
            grid: isDark ? 'rgba(255, 255, 255, 0.03)' : 'rgba(0, 0, 0, 0.03)',
            tooltip: isDark ? 'dark' : 'light',
            font: "'Plus Jakarta Sans', sans-serif"
        };
    };

    const renderCharts = (data) => {
        const config = getThemeConfig();
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        const baseOptions = {
            chart: {
                fontFamily: config.font,
                foreColor: config.text,
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            grid: {
                borderColor: config.grid
            },
            theme: {
                mode: config.tooltip
            },
            dataLabels: {
                enabled: false
            }, // MEMATIKAN ANGKA LANGSUNG PADA GRAFIK
        };

        // 1. Sales vs Retur (Area)
        if (charts.sr) charts.sr.destroy();
        charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            ...baseOptions,
            series: [{
                name: 'Penjualan',
                data: data.trend_sales
            }, {
                name: 'Retur',
                data: data.trend_retur
            }],
            chart: {
                ...baseOptions.chart,
                type: 'area',
                height: 350
            },
            colors: ['#3b82f6', '#f43f5e'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.3,
                    opacityTo: 0.01
                }
            },
            xaxis: {
                categories: data.dates,
                type: 'datetime', // MENGGUNAKAN TIPE DATETIME AGAR TANGGAL TAMPIL
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 600
                    },
                    format: 'dd MMM' // FORMAT TANGGAL PADA SUMBU X
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMMM yyyy'
                }, // TANGGAL LENGKAP SAAT HOVER
                y: {
                    formatter: fmtRp
                } // NILAI ASLI SAAT HOVER
            }
        });
        charts.sr.render();

        // 2. AR vs Collection (Bar)
        if (charts.ac) charts.ac.destroy();
        charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            ...baseOptions,
            series: [{
                name: 'Piutang',
                data: data.trend_ar
            }, {
                name: 'Pelunasan',
                data: data.trend_coll
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 350
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%'
                }
            },
            xaxis: {
                categories: data.dates,
                type: 'datetime',
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 600
                    },
                    format: 'dd/MM'
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMMM yyyy'
                },
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.ac.render();

        // Ranking Helper
        const rankingOpts = (id, seriesName, seriesData, categories, color) => ({
            ...baseOptions,
            series: [{
                name: seriesName,
                data: seriesData
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '60%'
                }
            },
            colors: [color],
            xaxis: {
                categories: categories
            },
            dataLabels: {
                enabled: true,
                formatter: (v) => seriesName === 'Qty' ? v : fmtJt(v)
            },
            tooltip: {
                y: {
                    formatter: (v) => seriesName === 'Qty' ? v + ' Unit' : fmtRp(v)
                }
            }
        });

        if (charts.tp) charts.tp.destroy();
        charts.tp = new ApexCharts(document.querySelector("#chart-top-produk"), rankingOpts(
            "#chart-top-produk", 'Qty', data.top_produk_val, data.top_produk_lbl, '#3b82f6'));
        charts.tp.render();

        if (charts.tc) charts.tc.destroy();
        charts.tc = new ApexCharts(document.querySelector("#chart-top-customer"), rankingOpts(
            "#chart-top-customer", 'Omzet', data.top_cust_val, data.top_cust_lbl, '#8b5cf6'));
        charts.tc.render();

        if (charts.ts) charts.ts.destroy();
        charts.ts = new ApexCharts(document.querySelector("#chart-top-supplier"), rankingOpts(
            "#chart-top-supplier", 'Omzet', data.top_supp_val, data.top_supp_lbl, '#ec4899'));
        charts.ts.render();

        // 6. Sales Performance
        if (charts.sp) charts.sp.destroy();
        charts.sp = new ApexCharts(document.querySelector("#chart-sales-perf"), {
            ...baseOptions,
            series: [{
                name: 'Realisasi',
                data: data.sales_real
            }, {
                name: 'Target',
                data: data.sales_target
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 500
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%'
                }
            },
            colors: ['#3b82f6', config.grid],
            xaxis: {
                categories: data.sales_names
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
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
        const newData = event.data || (event[0] && event[0].data) || event;
        if (newData) renderCharts(newData);
    });

    const observer = new MutationObserver(() => {
        if (initData) renderCharts(initData);
    });
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

.font-jakarta {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 10px;
}
</style>