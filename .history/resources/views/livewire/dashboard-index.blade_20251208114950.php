<div class="space-y-6">

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 relative z-30">
        <div class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="w-full lg:w-auto">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Periode</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate"
                        class="w-full lg:w-40 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <span class="text-gray-400 font-bold">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="w-full lg:w-40 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                </div>
            </div>

            <div class="w-full lg:w-64" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm flex items-center justify-between hover:bg-gray-50">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Cabang Terpilih' }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto"
                        style="display: none;">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-indigo-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-gray-300 text-indigo-600 mr-2 focus:ring-indigo-500">
                            <span class="text-xs text-gray-700 font-medium">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-64" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Salesman</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm flex items-center justify-between hover:bg-gray-50">
                        <span
                            class="truncate">{{ empty($filterSales) ? 'Semua Sales' : count($filterSales) . ' Sales Terpilih' }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto"
                        style="display: none;">
                        <div class="px-2 pb-2 mb-1 border-b border-gray-100 sticky top-0 bg-white">
                            <input type="text" placeholder="Cari Sales..."
                                class="w-full text-xs border-gray-100 rounded bg-gray-50 focus:ring-0">
                        </div>
                        @foreach($optSales as $sales)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-indigo-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $sales }}" wire:model.live="filterSales"
                                class="rounded border-gray-300 text-indigo-600 mr-2 focus:ring-indigo-500">
                            <span class="text-xs text-gray-700 font-medium">{{ $sales }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="ml-auto" wire:loading>
                <span class="text-xs text-indigo-600 font-bold"><i class="fas fa-spinner fa-spin mr-2"></i> Update
                    Data...</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-indigo-600 rounded-xl p-5 text-white shadow-lg shadow-indigo-200">
            <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mb-1">Total Penjualan</p>
            <h3 class="text-2xl font-bold tracking-tight">Rp {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt
            </h3>
            <p class="text-[10px] text-indigo-200 mt-1 opacity-80">Rp {{ number_format($salesSum, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-red-100 shadow-sm">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Total Retur</p>
            <h3 class="text-xl font-bold text-red-600">Rp {{ number_format($returSum, 0, ',', '.') }}</h3>
            <span
                class="inline-block mt-2 text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold border border-red-100">Rasio:
                {{ number_format($persenRetur, 2) }}%</span>
        </div>
        <div class="bg-white rounded-xl p-5 border border-orange-100 shadow-sm">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Tagihan Baru (AR)</p>
            <h3 class="text-xl font-bold text-orange-600">Rp {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt</h3>
        </div>
        <div class="bg-white rounded-xl p-5 border border-emerald-100 shadow-sm">
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Uang Masuk (Coll)</p>
            <h3 class="text-xl font-bold text-emerald-600">Rp {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt
            </h3>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-users text-indigo-600"></i> Kinerja Salesman
        </h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100" wire:ignore>
                <h4 class="font-bold text-sm text-indigo-800 mb-4 border-b pb-2">🎯 IMS: Target vs Realisasi</h4>
                <div id="chart-ims" style="min-height: 300px;"></div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100" wire:ignore>
                <h4 class="font-bold text-sm text-orange-800 mb-4 border-b pb-2">💰 Kualitas AR (Piutang)</h4>
                <div id="chart-ar-quality" style="min-height: 300px;"></div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100" wire:ignore>
                <h4 class="font-bold text-sm text-emerald-800 mb-4 border-b pb-2">🏪 OA: Outlet Active</h4>
                <div id="chart-oa" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" wire:ignore>
            <h4 class="font-bold text-gray-800 text-lg mb-4">Tren Penjualan Harian</h4>
            <div id="chart-sales-retur" style="min-height: 300px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let chartSales, chartIMS, chartARQ, chartOA;
    const initData = @json($chartData);

    const renderCharts = (data) => {
        // Format Rupiah Helper
        const fmtRp = (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val);
        const fmtJt = (val) => (val / 1000000).toFixed(1) + " Jt";

        // 1. IMS Chart (Target vs Real)
        if (chartIMS) chartIMS.destroy();
        const optIMS = {
            series: [{
                name: 'Realisasi',
                data: data.salesRealIMS
            }, {
                name: 'Target',
                data: data.salesTargetIMS
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#4f46e5', '#e5e7eb'], // Biru, Abu
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 2
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    formatter: (val) => (val / 1000000).toFixed(0) + "Jt"
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        };
        chartIMS = new ApexCharts(document.querySelector("#chart-ims"), optIMS);
        chartIMS.render();

        // 2. AR Quality Chart (Stacked)
        if (chartARQ) chartARQ.destroy();
        const optARQ = {
            series: [{
                name: 'Lancar',
                data: data.salesARLancar
            }, {
                name: 'Macet (>30 Hari)',
                data: data.salesARMacet
            }],
            chart: {
                type: 'bar',
                stacked: true,
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#10b981', '#ef4444'], // Hijau, Merah
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 2
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
        };
        chartARQ = new ApexCharts(document.querySelector("#chart-ar-quality"), optARQ);
        chartARQ.render();

        // 3. OA Chart
        if (chartOA) chartOA.destroy();
        const optOA = {
            series: [{
                name: 'Realisasi',
                data: data.salesRealOA
            }, {
                name: 'Target',
                data: data.salesTargetOA
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#059669', '#d1fae5'], // Emerald Tua, Muda
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 2
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames
            },
        };
        chartOA = new ApexCharts(document.querySelector("#chart-oa"), optOA);
        chartOA.render();

        // 4. Trend Harian (Sales Only)
        if (chartSales) chartSales.destroy();
        const optSales = {
            series: [{
                name: 'Penjualan',
                data: data.sales
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#4f46e5'],
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
                    formatter: fmtJt
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.5,
                    opacityTo: 0.05
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        };
        chartSales = new ApexCharts(document.querySelector("#chart-sales-retur"), optSales);
        chartSales.render();
    };

    renderCharts(initData);

    Livewire.on('update-charts', (event) => {
        const newData = event.data || event[0].data;
        renderCharts(newData);
    });
});
</script>