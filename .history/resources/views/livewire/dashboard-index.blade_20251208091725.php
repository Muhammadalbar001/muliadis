<div class="space-y-6">

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
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
                        <span class="truncate">
                            {{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Cabang Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto">
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
                        <span class="truncate">
                            {{ empty($filterSales) ? 'Semua Sales' : count($filterSales) . ' Sales Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto">
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

            <div class="ml-auto pb-2" wire:loading>
                <span class="text-xs font-bold text-indigo-600 animate-pulse">
                    <i class="fas fa-sync-alt fa-spin mr-1"></i> Memproses Data...
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="bg-indigo-600 rounded-xl p-5 text-white shadow-lg shadow-indigo-200 transform hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mb-1">Total Penjualan</p>
                    <h3 class="text-2xl font-bold tracking-tight">Rp
                        {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt</h3>
                    <p class="text-[10px] text-indigo-200 mt-1 opacity-80">Rp
                        {{ number_format($salesSum, 0, ',', '.') }}</p>
                </div>
                <div class="p-2 bg-indigo-500/30 rounded-lg text-white">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-xl p-5 border border-red-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Total Retur</p>
                    <h3 class="text-xl font-bold text-red-600">Rp {{ number_format($returSum, 0, ',', '.') }}</h3>
                    <span
                        class="inline-block mt-2 text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold border border-red-100">
                        Rasio: {{ number_format($persenRetur, 2) }}%
                    </span>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-500">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-xl p-5 border border-orange-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Tagihan Baru (AR)</p>
                    <h3 class="text-xl font-bold text-orange-600">Rp {{ number_format($arSum / 1000000, 1, ',', '.') }}
                        Jt</h3>
                    <p class="text-[10px] text-gray-400 mt-1">Terbentuk periode ini</p>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-xl p-5 border border-emerald-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Uang Masuk (Coll)</p>
                    <h3 class="text-xl font-bold text-emerald-600">Rp
                        {{ number_format($collSum / 1000000, 1, ',', '.') }} Jt</h3>
                    <p class="text-[10px] text-gray-400 mt-1">Diterima periode ini</p>
                </div>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-500">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Tren Penjualan vs Retur</h4>
                    <p class="text-xs text-gray-400">Perbandingan omzet kotor dan retur barang</p>
                </div>
            </div>
            <div id="chart-sales-retur" style="min-height: 320px;"></div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Tagihan vs Pembayaran</h4>
                    <p class="text-xs text-gray-400">Cashflow harian perusahaan</p>
                </div>
            </div>
            <div id="chart-ar-coll" style="min-height: 320px;"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i> Top 10 Produk (Qty)
            </h4>
            <div id="chart-top-produk" style="min-height: 380px;"></div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-crown text-purple-500"></i> Top 10 Customer (Value)
            </h4>
            <div id="chart-top-customer" style="min-height: 380px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    // --- 1. INISIALISASI VARIABEL CHART ---
    let chartSales, chartAR, chartProd, chartCust;

    // Data Awal dari Controller
    const initData = @json($chartData);
    const initTopProdNames = @json($topProduk -> pluck('nama_item'));
    const initTopProdQty = @json($topProduk -> pluck('total_qty'));
    const initTopCustNames = @json($topCustomer -> pluck('nama_pelanggan'));
    const initTopCustVal = @json($topCustomer - > pluck('total_beli'));

    // --- FUNGSI RENDER CHART ---
    const renderCharts = (data, prodNames, prodQty, custNames, custVal) => {

        // A. CHART SALES VS RETUR (Area)
        const optSales = {
            series: [{
                    name: 'Penjualan',
                    data: data.sales
                },
                {
                    name: 'Retur',
                    data: data.retur
                }
            ],
            chart: {
                type: 'area',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#4f46e5', '#ef4444'],
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
                }, // Sembunyikan label tanggal biar rapi
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return (val / 1000000).toFixed(1) + " Jt"
                    },
                    style: {
                        fontSize: '10px'
                    }
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
                    formatter: (val) => {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };

        // B. CHART AR VS COLLECTION (Column)
        const optAR = {
            series: [{
                    name: 'Tagihan (AR)',
                    data: data.ar
                },
                {
                    name: 'Bayar (Coll)',
                    data: data.coll
                }
            ],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 3,
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
                }
            },
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return (val / 1000000).toFixed(1) + " Jt"
                    },
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: (val) => {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };

        // C. TOP PRODUK (Bar Horizontal)
        const optProd = {
            series: [{
                name: 'Qty',
                data: prodQty
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    barHeight: '60%'
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: function(val) {
                    return val
                }
            },
            xaxis: {
                categories: prodNames,
                labels: {
                    show: false
                }
            }, // Sembunyikan sumbu X bawah
            colors: ['#3b82f6'],
            grid: {
                show: false
            } // Hilangkan garis grid agar bersih
        };

        // D. TOP CUSTOMER (Bar Horizontal)
        const optCust = {
            series: [{
                name: 'Total',
                data: custVal
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    barHeight: '60%'
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: function(val) {
                    return (val / 1000000).toFixed(1) + " Jt"
                }
            },
            xaxis: {
                categories: custNames,
                labels: {
                    show: false
                }
            },
            colors: ['#8b5cf6'],
            grid: {
                show: false
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        // Render atau Update
        if (chartSales) {
            chartSales.updateOptions(optSales);
            chartAR.updateOptions(optAR);
            // Untuk Bar chart yang kategorinya berubah total, lebih aman destroy & create ulang
            chartProd.destroy();
            chartProd = new ApexCharts(document.querySelector("#chart-top-produk"), optProd);
            chartProd.render();
            chartCust.destroy();
            chartCust = new ApexCharts(document.querySelector("#chart-top-customer"), optCust);
            chartCust.render();
        } else {
            chartSales = new ApexCharts(document.querySelector("#chart-sales-retur"), optSales);
            chartSales.render();
            chartAR = new ApexCharts(document.querySelector("#chart-ar-coll"), optAR);
            chartAR.render();
            chartProd = new ApexCharts(document.querySelector("#chart-top-produk"), optProd);
            chartProd.render();
            chartCust = new ApexCharts(document.querySelector("#chart-top-customer"), optCust);
            chartCust.render();
        }
    };

    // Render Pertama Kali
    renderCharts(initData, initTopProdNames, initTopProdQty, initTopCustNames, initTopCustVal);

    // --- LISTENER: UPDATE DATA SAAT FILTER BERUBAH ---
    Livewire.on('update-charts', (event) => {
        // Karena event.detail berisi data array, kita ambil index 0
        // (Tergantung versi livewire, kadang event[0], kadang event.detail)
        const newData = event.data || event[0].data;

        // Top Produk & Customer belum dikirim via event JSON di controller sebelumnya
        // Untuk hasil sempurna, kita reload halaman saja jika data top 10 berubah drastis
        // TAPI, untuk chart garis (trend), data sudah ada di 'newData'.

        // Update Chart Garis saja yang realtime, Top 10 biar ikut refresh livewire (DOM replace)
        // Atau refresh page

        // Sederhananya untuk sekarang:
        window.location.reload();
    });
});
</script>