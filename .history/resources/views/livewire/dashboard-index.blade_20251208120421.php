<div class="min-h-screen space-y-8 font-jakarta pb-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-1">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau performa distribusi secara realtime.</p>
        </div>
        <div
            class="mt-4 md:mt-0 flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-bold text-slate-600">Live Data</span>
        </div>
    </div>

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/80 p-4 rounded-2xl shadow-sm border border-white/50 transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">

            <div class="flex items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm w-full lg:w-auto">
                <div class="px-3 text-slate-400"><i class="far fa-calendar-alt"></i></div>
                <input type="date" wire:model.live="startDate"
                    class="border-none text-sm font-semibold text-slate-700 focus:ring-0 p-1.5 bg-transparent cursor-pointer">
                <span class="text-slate-300 mx-1">/</span>
                <input type="date" wire:model.live="endDate"
                    class="border-none text-sm font-semibold text-slate-700 focus:ring-0 p-1.5 bg-transparent cursor-pointer">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <div class="relative w-full sm:w-56" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm hover:border-indigo-300 transition-colors">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Terpilih' }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-2 w-full bg-white border border-slate-100 rounded-xl shadow-xl p-2 max-h-60 overflow-y-auto">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-3 py-2 hover:bg-indigo-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-xs font-semibold text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-56" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm hover:border-indigo-300 transition-colors">
                        <span
                            class="truncate">{{ empty($filterSales) ? 'Semua Salesman' : count($filterSales) . ' Terpilih' }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-2 w-full bg-white border border-slate-100 rounded-xl shadow-xl p-2 max-h-60 overflow-y-auto">
                        <div class="px-2 pb-2 mb-1 border-b border-slate-100 sticky top-0 bg-white">
                            <input type="text" placeholder="Cari Sales..."
                                class="w-full text-xs border-slate-200 rounded-lg bg-slate-50 focus:ring-0 px-2 py-1">
                        </div>
                        @foreach($optSales as $sales)
                        <label
                            class="flex items-center px-3 py-2 hover:bg-indigo-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $sales }}" wire:model.live="filterSales"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-xs font-semibold text-slate-600">{{ $sales }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div wire:loading class="lg:absolute lg:right-4 lg:-bottom-8">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Updating...
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        <div
            class="relative group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-chart-line text-6xl text-indigo-600"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Penjualan</p>
            <h3 class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($salesSum / 1000000, 1, ',', '.') }}
                <span class="text-lg text-slate-400">Jt</span></h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md font-bold">
                    Real: {{ number_format($salesSum, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div
            class="relative group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Retur</p>
            <h3 class="text-2xl font-extrabold text-red-600">Rp {{ number_format($returSum / 1000000, 1, ',', '.') }}
                <span class="text-lg text-red-300">Jt</span></h3>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min($persenRetur, 100) }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 text-right">Rasio Retur: <span
                    class="font-bold text-red-500">{{ number_format($persenRetur, 2) }}%</span></p>
        </div>

        <div
            class="relative group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Piutang Baru (AR)</p>
                    <h3 class="text-2xl font-extrabold text-orange-500">Rp
                        {{ number_format($arSum / 1000000, 1, ',', '.') }} <span
                            class="text-lg text-orange-300">Jt</span></h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4">Terbentuk dari penjualan periode ini.</p>
        </div>

        <div
            class="relative group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Uang Masuk (Coll)</p>
                    <h3 class="text-2xl font-extrabold text-emerald-500">Rp
                        {{ number_format($collSum / 1000000, 1, ',', '.') }} <span
                            class="text-lg text-emerald-300">Jt</span></h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4">Pelunasan diterima periode ini.</p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <span class="w-1 h-6 bg-indigo-600 rounded-full"></span>
                Rapor Kinerja Salesman
            </h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:ignore>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 h-full">
                <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-2">
                    <h4 class="font-bold text-sm text-slate-700">🎯 Target vs Realisasi (IMS)</h4>
                    <span class="text-[10px] px-2 py-1 bg-indigo-50 text-indigo-600 rounded font-bold">Omzet</span>
                </div>
                <div id="chart-ims" style="min-height: 280px;"></div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 h-full">
                <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-2">
                    <h4 class="font-bold text-sm text-slate-700">💰 Kualitas Piutang</h4>
                    <span class="text-[10px] px-2 py-1 bg-orange-50 text-orange-600 rounded font-bold">Lancar vs
                        Macet</span>
                </div>
                <div id="chart-ar-quality" style="min-height: 280px;"></div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 h-full">
                <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-2">
                    <h4 class="font-bold text-sm text-slate-700">🏪 Distribusi Toko (OA)</h4>
                    <span class="text-[10px] px-2 py-1 bg-emerald-50 text-emerald-600 rounded font-bold">Outlet
                        Active</span>
                </div>
                <div id="chart-oa" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-1 h-6 bg-orange-500 rounded-full"></span>
            Tren Harian
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-sm text-slate-700 mb-4">Grafik Penjualan vs Retur</h4>
                <div id="chart-sales-retur" style="min-height: 320px;"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-sm text-slate-700 mb-4">Grafik Tagihan vs Pembayaran</h4>
                <div id="chart-ar-coll" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-1 h-6 bg-yellow-500 rounded-full"></span>
            Top 10 Rankings
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <i class="fas fa-box text-6xl"></i>
                </div>
                <h4 class="font-bold text-sm text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-500"></i> Produk Terlaris (Qty)
                </h4>
                <div id="chart-top-produk" style="min-height: 350px;"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <i class="fas fa-users text-6xl"></i>
                </div>
                <h4 class="font-bold text-sm text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-crown text-purple-500"></i> Pelanggan Terbaik (Value)
                </h4>
                <div id="chart-top-customer" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    let chartIMS, chartARQ, chartOA, chartSalesRetur, chartARColl, chartTopProd, chartTopCust;
    const initData = @json($chartData);

    const initTopProdNames = @json($topProduk -> pluck('nama_item'));
    const initTopProdQty = @json($topProduk -> pluck('total_qty'));
    const initTopCustNames = @json($topCustomer -> pluck('nama_pelanggan'));
    const initTopCustVal = @json($topCustomer - > pluck('total_beli'));

    const renderCharts = (data) => {
        const fontStack = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val);
        const fmtJt = (val) => (val / 1000000).toFixed(1) + " Jt";

        // Common Options
        const commonBarOpts = {
            chart: {
                type: 'bar',
                height: 280,
                toolbar: {
                    show: false
                },
                fontFamily: fontStack
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '60%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
        };

        // 1. IMS Chart
        if (chartIMS) chartIMS.destroy();
        chartIMS = new ApexCharts(document.querySelector("#chart-ims"), {
            ...commonBarOpts,
            series: [{
                name: 'Realisasi',
                data: data.salesRealIMS
            }, {
                name: 'Target',
                data: data.salesTargetIMS
            }],
            colors: ['#6366f1', '#cbd5e1'],
            xaxis: {
                categories: data.salesNames,
                labels: {
                    formatter: (val) => (val / 1000000).toFixed(0) + "Jt",
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
        chartIMS.render();

        // 2. AR Quality
        if (chartARQ) chartARQ.destroy();
        chartARQ = new ApexCharts(document.querySelector("#chart-ar-quality"), {
            ...commonBarOpts,
            chart: {
                type: 'bar',
                stacked: true,
                height: 280,
                toolbar: {
                    show: false
                },
                fontFamily: fontStack
            },
            series: [{
                name: 'Lancar',
                data: data.salesARLancar
            }, {
                name: 'Macet (>30)',
                data: data.salesARMacet
            }],
            colors: ['#10b981', '#ef4444'],
            xaxis: {
                categories: data.salesNames,
                labels: {
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
        chartARQ.render();

        // 3. OA Chart
        if (chartOA) chartOA.destroy();
        chartOA = new ApexCharts(document.querySelector("#chart-oa"), {
            ...commonBarOpts,
            series: [{
                name: 'Realisasi',
                data: data.salesRealOA
            }, {
                name: 'Target',
                data: data.salesTargetOA
            }],
            colors: ['#059669', '#d1fae5'],
            xaxis: {
                categories: data.salesNames,
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
        });
        chartOA.render();

        // 4. Sales vs Retur (Area - Clean Style)
        if (chartSalesRetur) chartSalesRetur.destroy();
        chartSalesRetur = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                name: 'Penjualan',
                data: data.sales
            }, {
                name: 'Retur',
                data: data.retur
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: fontStack,
                zoom: {
                    enabled: false
                }
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
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '10px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
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
        chartSalesRetur.render();

        // 5. AR vs Coll (Bar - Clean Style)
        if (chartARColl) chartARColl.destroy();
        chartARColl = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                name: 'Tagihan Baru',
                data: data.ar
            }, {
                name: 'Bayar (Coll)',
                data: data.coll
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: fontStack
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 2,
                    columnWidth: '55%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.dates,
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt,
                    style: {
                        colors: '#94a3b8',
                        fontSize: '10px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        chartARColl.render();
    };

    // 6. Top Charts (Static Initial)
    const renderTop = (pN, pQ, cN, cV) => {
        const opts = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    barHeight: '65%'
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (val) => val,
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
            },
        };

        chartTopProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...opts,
            series: [{
                name: 'Qty',
                data: pQ
            }],
            xaxis: {
                categories: pN
            },
            colors: ['#3b82f6']
        });
        chartTopProd.render();

        chartTopCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...opts,
            series: [{
                name: 'Total',
                data: cV
            }],
            xaxis: {
                categories: cN
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                ...opts.dataLabels,
                formatter: (val) => (val / 1000000).toFixed(1) + " Jt"
            },
            tooltip: {
                y: {
                    formatter: (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val)
                }
            }
        });
        chartTopCust.render();
    }

    renderCharts(initData);
    renderTop(initTopProdNames, initTopProdQty, initTopCustNames, initTopCustVal);

    Livewire.on('update-charts', (event) => {
        renderCharts(event.data || event[0].data);
    });
});
</script>