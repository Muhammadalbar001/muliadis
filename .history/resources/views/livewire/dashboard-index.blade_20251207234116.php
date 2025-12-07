<div class="space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 sticky top-0 z-30">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Periode</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate"
                        class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <span class="text-gray-400">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="all">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Salesman</label>
                <select wire:model.live="filterSales"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="all">Semua Sales</option>
                    @foreach($optSales as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
            </div>

            <div class="ml-auto" wire:loading>
                <span class="text-xs text-indigo-600 font-bold"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat
                    Data...</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-indigo-600 rounded-xl p-4 text-white shadow-lg">
            <p class="text-indigo-200 text-xs font-bold uppercase">Total Penjualan</p>
            <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($salesSum / 1000000, 1, ',', '.') }} Jt</h3>
            <p class="text-[10px] text-indigo-200 mt-1">Real: {{ number_format($salesSum, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-red-100 shadow-sm">
            <p class="text-gray-500 text-xs font-bold uppercase">Total Retur</p>
            <h3 class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($returSum, 0, ',', '.') }}</h3>
            <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold">Rasio:
                {{ number_format($persenRetur, 1) }}%</span>
        </div>
        <div class="bg-white rounded-xl p-4 border border-orange-100 shadow-sm">
            <p class="text-gray-500 text-xs font-bold uppercase">Tagihan Baru (AR)</p>
            <h3 class="text-xl font-bold text-orange-600 mt-1">Rp {{ number_format($arSum / 1000000, 1, ',', '.') }} Jt
            </h3>
            <p class="text-[10px] text-gray-400 mt-1">Total Tagihan Periode Ini</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
            <p class="text-gray-500 text-xs font-bold uppercase">Uang Masuk (Coll)</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($collSum / 1000000, 1, ',', '.') }}
                Jt</h3>
            <p class="text-[10px] text-gray-400 mt-1">Total Bayar Periode Ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-gray-800">Tren Penjualan vs Retur</h4>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Harian</span>
            </div>
            <div id="chart-sales-retur" style="min-height: 300px;"></div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-gray-800">Tagihan vs Pembayaran</h4>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Harian</span>
            </div>
            <div id="chart-ar-coll" style="min-height: 300px;"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">🏆 Top 10 Produk Terlaris (Qty)</h4>
            <div id="chart-top-produk" style="min-height: 350px;"></div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">👑 Top 10 Customer (Value)</h4>
            <div id="chart-top-customer" style="min-height: 350px;"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    // --- 1. CHART SALES VS RETUR ---
    const optionsSales = {
        series: [{
            name: 'Penjualan',
            data: @json($dataSales)
        }, {
            name: 'Retur',
            data: @json($dataRetur)
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            }
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
            categories: @json($chartDates),
            labels: {
                show: false
            }
        }, // Hide labels if too many
        yaxis: {
            labels: {
                formatter: (val) => {
                    return (val / 1000000).toFixed(0) + " Jt"
                }
            }
        },
        fill: {
            opacity: 0.3
        }
    };
    new ApexCharts(document.querySelector("#chart-sales-retur"), optionsSales).render();

    // --- 2. CHART AR VS COLLECTION ---
    const optionsAR = {
        series: [{
            name: 'Tagihan Baru (AR)',
            data: @json($dataAR)
        }, {
            name: 'Bayar (Coll)',
            data: @json($dataColl)
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            },
            stacked: false
        },
        colors: ['#f97316', '#10b981'],
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '60%'
            }
        },
        xaxis: {
            categories: @json($chartDates),
            labels: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: (val) => {
                    return (val / 1000000).toFixed(0) + " Jt"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#chart-ar-coll"), optionsAR).render();

    // --- 3. CHART TOP PRODUK (BAR HORIZONTAL) ---
    const topProdNames = @json($topProduk -> pluck('nama_item'));
    const topProdQty = @json($topProduk -> pluck('total_qty'));

    const optionsProd = {
        series: [{
            name: 'Qty Terjual',
            data: topProdQty
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                barHeight: '70%'
            }
        },
        dataLabels: {
            enabled: true,
            textAnchor: 'start',
            style: {
                colors: ['#fff']
            },
            formatter: function(val, opt) {
                return val + " Unit"
            }
        },
        xaxis: {
            categories: topProdNames
        },
        colors: ['#3b82f6']
    };
    new ApexCharts(document.querySelector("#chart-top-produk"), optionsProd).render();

    // --- 4. CHART TOP CUSTOMER (BAR HORIZONTAL) ---
    const topCustNames = @json($topCustomer -> pluck('nama_pelanggan'));
    const topCustVal = @json($topCustomer - > pluck('total_beli'));

    const optionsCust = {
        series: [{
            name: 'Total Pembelian',
            data: topCustVal
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                barHeight: '70%'
            }
        },
        dataLabels: {
            enabled: true,
            textAnchor: 'start',
            formatter: function(val, opt) {
                return (val / 1000000).toFixed(1) + " Jt"
            }
        },
        xaxis: {
            categories: topCustNames
        },
        colors: ['#8b5cf6'],
        tooltip: {
            y: {
                formatter: function(val) {
                    return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#chart-top-customer"), optionsCust).render();

});

// Refresh chart saat filter berubah (Opsional: butuh re-init lebih kompleks)
// Cara termudah adalah wire:navigate atau reload page saat filter tanggal berubah
</script>