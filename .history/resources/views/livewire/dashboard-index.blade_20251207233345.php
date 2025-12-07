<div class="space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
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

            <div class="ml-auto flex items-center gap-2 text-sm text-gray-500">
                <div wire:loading class="flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i> Memuat Data...
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-5 text-white shadow-lg relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Total Penjualan</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-200 mt-2">Berdasarkan filter tanggal</p>
            </div>
            <i
                class="fas fa-chart-line absolute -right-2 -bottom-4 text-white/10 text-[80px] group-hover:scale-110 transition-transform"></i>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Total Retur</p>
                <h3 class="text-xl font-bold text-red-600">Rp {{ number_format($totalRetur, 0, ',', '.') }}</h3>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <span class="text-xs text-gray-400">Rasio Retur</span>
                <span
                    class="px-2 py-1 bg-red-50 text-red-600 rounded text-xs font-bold">{{ number_format($persenRetur, 2) }}%</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Total Uang Masuk</p>
                <h3 class="text-xl font-bold text-emerald-600">Rp {{ number_format($totalCollection, 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                @php $persenBayar = $totalPenjualan > 0 ? ($totalCollection / $totalPenjualan) * 100 : 0; @endphp
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min($persenBayar, 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 text-right">{{ number_format($persenBayar, 1) }}% dari Sales</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Sisa Piutang (AR)</p>
                <h3 class="text-xl font-bold text-orange-600">Rp {{ number_format($totalAR, 0, ',', '.') }}</h3>
            </div>
            <div class="mt-2 text-right">
                <a href="{{ route('transaksi.ar') }}" class="text-xs text-indigo-600 hover:underline">Lihat Detail <i
                        class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4">Tren Penjualan Harian</h4>
            <div id="chart-trend" style="min-height: 300px;"></div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100" x-data="{ tab: 'produk' }">
            <div class="flex gap-4 border-b border-gray-100 mb-4">
                <button @click="tab = 'produk'"
                    :class="{'text-indigo-600 border-b-2 border-indigo-600': tab === 'produk', 'text-gray-400': tab !== 'produk'}"
                    class="pb-2 text-sm font-bold transition">Top 5 Produk</button>
                <button @click="tab = 'customer'"
                    :class="{'text-indigo-600 border-b-2 border-indigo-600': tab === 'customer', 'text-gray-400': tab !== 'customer'}"
                    class="pb-2 text-sm font-bold transition">Top 5 Customer</button>
            </div>

            <div x-show="tab === 'produk'" class="space-y-4">
                @foreach($topProduk as $p)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <p class="text-sm text-gray-700 truncate" title="{{ $p->nama_item }}">{{ $p->nama_item }}</p>
                    </div>
                    <span class="text-xs font-bold text-gray-800">{{ number_format($p->total_qty) }} <span
                            class="text-[10px] text-gray-400 font-normal">Qty</span></span>
                </div>
                @endforeach
            </div>

            <div x-show="tab === 'customer'" class="space-y-4" style="display: none;">
                @foreach($topCustomer as $c)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <p class="text-sm text-gray-700 truncate" title="{{ $c->nama_pelanggan }}">
                            {{ $c->nama_pelanggan }}</p>
                    </div>
                    <span class="text-xs font-bold text-gray-800">Rp
                        {{ number_format($c->total_beli / 1000000, 1) }}M</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('livewire:init', () => {

    let chart;

    const initChart = (data, dates) => {
        const options = {
            series: [{
                name: 'Omzet',
                data: data
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#4f46e5'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: dates,
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return "Rp " + (val / 1000000).toFixed(1) + " Jt"
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: (val) => {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        if (chart) {
            chart.updateOptions(options);
        } else {
            chart = new ApexCharts(document.querySelector("#chart-trend"), options);
            chart.render();
        }
    };

    // Load data pertama kali
    const rawData = @json($dailySales);
    initChart(Object.values(rawData), Object.keys(rawData));

    // Update chart saat filter berubah (Livewire Event)
    Livewire.hook('morph.updated', ({
        el,
        component
    }) => {
        // Kita bisa ambil data baru dari property komponen jika perlu
        // Tapi cara paling mudah adalah me-render ulang script ini atau menggunakan event dispatch
    });
});
</script>