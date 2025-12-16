<div class="min-h-screen space-y-6 font-jakarta pb-10" x-data="{ activeTab: 'overview' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-slate-50/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex flex-col md:flex-row items-center gap-6 w-full xl:w-auto">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-slate-200 rounded-lg text-slate-600 shadow-sm"><i
                            class="fas fa-chart-pie text-xl"></i></div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Executive Dashboard</h1>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Monitoring kinerja bisnis real-time.</p>
                    </div>
                </div>

                <div class="flex p-1 bg-white border border-slate-200 rounded-lg shadow-sm">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'bg-slate-100 text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-home"></i> Overview</button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-trophy"></i> Ranking</button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-2"><i
                            class="fas fa-user-tie"></i> Sales</button>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div
                    class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm h-[38px]">
                    <input type="date" wire:model="startDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model="endDate"
                        class="border-none text-xs font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-24 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:border-slate-300 transition-all h-[38px]">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto right-0"
                        style="display: none;">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-slate-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model="filterCabang"
                                class="rounded border-slate-300 text-slate-600 mr-2 h-3 w-3 focus:ring-slate-500">
                            <span class="text-xs text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="applyFilter"
                    class="h-[38px] px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
                    <i class="fas fa-search" wire:loading.remove target="applyFilter"></i>
                    <i class="fas fa-circle-notch fa-spin" wire:loading target="applyFilter"></i>
                    <span>Cari</span>
                </button>

            </div>
        </div>
    </div>

    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <div
                class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Penjualan
                    </p>
                    <h3 class="text-xl font-extrabold tracking-tight">Rp {{ $this->formatCompact($salesSum) }}</h3>
                    <p class="text-[10px] mt-0.5 text-emerald-100 opacity-80">Real:
                        {{ number_format($salesSum, 0, ',', '.') }}</p>
                </div>
                <i
                    class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl group-hover:scale-110 transition-transform"></i>
            </div>

            <div
                class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm relative overflow-hidden group hover:border-rose-300 transition-colors">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Retur</p>
                <h3 class="text-xl font-extrabold text-rose-500 tracking-tight">Rp {{ $this->formatCompact($returSum) }}
                </h3>
                <div class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded bg-rose-50 border border-rose-100">
                    <span class="text-[9px] font-bold text-rose-600">Rasio: {{ number_format($persenRetur, 2) }}%</span>
                </div>
                <i
                    class="fas fa-undo absolute right-3 top-3 text-rose-100 text-5xl group-hover:rotate-[-12deg] transition-transform"></i>
            </div>

            <div
                class="bg-white p-4 rounded-2xl border border-orange-100 shadow-sm relative overflow-hidden hover:border-orange-300 transition-colors">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Piutang Baru</p>
                <h3 class="text-xl font-extrabold text-orange-500 tracking-tight">Rp {{ $this->formatCompact($arSum) }}
                </h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Tagihan Terbentuk</p>
                <i class="fas fa-file-invoice-dollar absolute right-3 top-3 text-orange-100 text-5xl"></i>
            </div>

            <div
                class="bg-white p-4 rounded-2xl border border-cyan-100 shadow-sm relative overflow-hidden hover:border-cyan-300 transition-colors">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Uang Masuk (Coll)</p>
                <h3 class="text-xl font-extrabold text-cyan-600 tracking-tight">Rp {{ $this->formatCompact($collSum) }}
                </h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Pembayaran Diterima</p>
                <i class="fas fa-wallet absolute right-3 top-3 text-cyan-100 text-5xl"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6" wire:ignore>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Tren Penjualan vs Retur (Harian)</h4>
                </div>
                <div id="chart-sales-retur" style="min-height: 350px;"></div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Tagihan vs Pembayaran (Harian)</h4>
                </div>
                <div id="chart-ar-coll" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2"><i
                    class="fas fa-box text-blue-500"></i> Top 10 Produk (Qty)</h4>
            <div id="chart-top-produk" style="min-height: 400px;"></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2"><i
                    class="fas fa-users text-purple-500"></i> Top 10 Pelanggan (Omzet)</h4>
            <div id="chart-top-customer" style="min-height: 400px;"></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2"><i
                    class="fas fa-truck text-pink-500"></i> Top 10 Supplier (Omzet)</h4>
            <div id="chart-top-supplier" style="min-height: 400px;"></div>
        </div>
    </div>

    <div x-show="activeTab === 'salesman'" x-transition.opacity.duration.300ms class="grid grid-cols-1 gap-6"
        wire:ignore>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h4 class="font-bold text-lg text-indigo-900 mb-4 pb-2 border-b border-slate-50 flex items-center gap-2"><i
                    class="fas fa-bullseye text-indigo-500"></i> Top 10 Sales Performance (Target vs Realisasi)</h4>
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

        // --- SALES VS RETUR ---
        if (charts.sr) charts.sr.destroy();
        charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                name: 'Penjualan',
                data: data.trend_sales
            }, {
                name: 'Retur',
                data: data.trend_retur
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#10b981', '#f43f5e'],
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

        // --- AR VS COLL ---
        if (charts.ac) charts.ac.destroy();
        charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                name: 'Piutang Baru',
                data: data.trend_ar
            }, {
                name: 'Pelunasan',
                data: data.trend_coll
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#f97316', '#06b6d4'],
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

        // --- TOP PRODUK ---
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

        // --- TOP CUSTOMER ---
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

        // --- TOP SUPPLIER ---
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

        // --- SALES PERFORMANCE ---
        if (charts.sp) charts.sp.destroy();
        charts.sp = new ApexCharts(document.querySelector("#chart-sales-perf"), {
            series: [{
                name: 'Realisasi',
                data: data.sales_real
            }, {
                name: 'Target',
                data: data.sales_target
            }],
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
            xaxis: {
                categories: data.sales_names
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            colors: ['#4f46e5', '#cbd5e1'],
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.sp.render();
    };

    // Render awal saat data tersedia
    if (initData) renderCharts(initData);

    // Event listener untuk update saat filter ditekan
    Livewire.on('update-charts', (event) => {
        const newData = event.data || event[0].data;
        renderCharts(newData);
    });
});
</script>