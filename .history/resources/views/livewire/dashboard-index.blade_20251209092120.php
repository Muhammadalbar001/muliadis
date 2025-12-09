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
                <select wire:model.live="startDate"
                    class="border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2">
                    <option value="{{ date('Y-m-01') }}">Bulan Ini</option>
                </select>
                <div wire:loading class="text-indigo-600 text-xs font-bold animate-pulse"><i
                        class="fas fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'overview'" x-transition class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-1">
            <div
                class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><i
                        class="fas fa-coins text-6xl text-indigo-600"></i></div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 mt-1">Rp
                    {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt</h3>
                <p class="text-[10px] text-slate-400 mt-2">Real: {{ number_format($salesSum, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Retur</p>
                <h3 class="text-2xl font-extrabold text-red-500 mt-1">Rp
                    {{ number_format($returSum / 1000000, 1, ',', '.') }} Jt</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Piutang Baru (AR)</p>
                <h3 class="text-2xl font-extrabold text-orange-500 mt-1">Rp
                    {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Uang Masuk (Coll)</p>
                <h3 class="text-2xl font-extrabold text-emerald-500 mt-1">Rp
                    {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">📈 Tren Penjualan Harian</h4>
                <div id="chart-sales-trend" style="min-height: 350px;"></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-lg mb-4">💰 AR vs Collection</h4>
                <div id="chart-ar-coll" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ranking'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:ignore>

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
                        class="fas fa-crown"></i></span>Top Customer (Omzet)
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

    <div x-show="activeTab === 'salesman'" x-transition class="grid grid-cols-1 gap-6" wire:ignore>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-lg text-indigo-900 mb-4 pb-2 border-b border-slate-50">🎯 Target vs Realisasi
                (IMS)</h4>
            <div id="chart-ims" style="min-height: 400px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    let charts = {};
    // PENTING: Ambil data lengkap dari controller (tidak parsial)
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

        // --- 1. RENDER TOP RANKING (Menggunakan Data dari Controller Baru) ---

        // Top Produk
        if (charts.topProd) charts.topProd.destroy();
        // Cek dulu apakah element ada (karena x-show menyembunyikannya di awal)
        // ApexCharts bisa render ke div tersembunyi asalkan ukurannya tidak 0
        charts.topProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...hBarOpts,
            series: [{
                name: 'Qty',
                data: data.topProdVal ?? []
            }], // Ambil dari array data yang dikirim controller
            xaxis: {
                categories: data.topProdNames ?? []
            },
            colors: ['#3b82f6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => new Intl.NumberFormat('id-ID').format(v) + " Unit"
            }
        });
        charts.topProd.render();

        // Top Customer
        if (charts.topCust) charts.topCust.destroy();
        charts.topCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...hBarOpts,
            series: [{
                name: 'Omzet',
                data: data.topCustVal ?? []
            }],
            xaxis: {
                categories: data.topCustNames ?? []
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => fmtJt(v)
            }
        });
        charts.topCust.render();

        // Top Supplier
        if (charts.topSupp) charts.topSupp.destroy();
        charts.topSupp = new ApexCharts(document.querySelector("#chart-top-supplier"), {
            ...hBarOpts,
            series: [{
                name: 'Omzet',
                data: data.topSuppVal ?? []
            }],
            xaxis: {
                categories: data.topSuppNames ?? []
            },
            colors: ['#ec4899'],
            dataLabels: {
                enabled: true,
                formatter: (v) => fmtJt(v)
            }
        });
        charts.topSupp.render();


        // --- 2. RENDER TREND (OVERVIEW) ---

        if (charts.salesTrend) charts.salesTrend.destroy();
        charts.salesTrend = new ApexCharts(document.querySelector("#chart-sales-trend"), {
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
                    formatter: fmtJt
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.salesTrend.render();

        if (charts.arColl) charts.arColl.destroy();
        charts.arColl = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                name: 'Piutang',
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
                    formatter: fmtJt
                }
            }
        });
        charts.arColl.render();


        // --- 3. RENDER SALESMAN ---
        if (charts.ims) charts.ims.destroy();
        charts.ims = new ApexCharts(document.querySelector("#chart-ims"), {
            ...hBarOpts,
            series: [{
                name: 'Target',
                data: data.salesTargetIMS ?? []
            }, {
                name: 'Realisasi',
                data: data.salesRealIMS ?? []
            }],
            xaxis: {
                categories: data.salesNames ?? []
            },
            colors: ['#e2e8f0', '#4f46e5'],
            dataLabels: {
                enabled: false
            }
        });
        charts.ims.render();
    };

    // Initial Render
    if (initData) renderAll(initData);

    // Re-render saat ada update filter dari Livewire
    Livewire.on('update-charts', (event) => {
        // Handle struktur data event yang mungkin berbeda (array vs object langsung)
        const newData = event.data || event[0].data;
        renderAll(newData);
    });
});
</script>
@endsection