<div class="space-y-6 font-jakarta">

    <div
        class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="flex gap-4 w-full md:w-auto">
            <div class="w-full md:w-40">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
                <select wire:model.live="filterBulan"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
                    @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                        {{ date('F', mktime(0, 0, 0, $i, 10)) }}</option> @endfor
                </select>
            </div>
            <div class="w-full md:w-32">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tahun</label>
                <select wire:model.live="filterTahun"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
                    @for($y=date('Y'); $y>=2023; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400">Update Terakhir</p>
            <p class="text-sm font-bold text-slate-700">{{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">🏆 Top 10 Produk (Qty)</h3>
                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded-lg">Terlaris</span>
            </div>
            <div id="chart-produk" style="min-height: 300px;"></div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">👑 Top 10 Customer</h3>
                <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-1 rounded-lg">Omzet Tertinggi</span>
            </div>
            <div id="chart-customer" style="min-height: 300px;"></div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700">🏭 Top 10 Supplier</h3>
                <span class="text-[10px] bg-orange-50 text-orange-600 px-2 py-1 rounded-lg">Kontribusi</span>
            </div>
            <div id="chart-supplier" style="min-height: 300px;"></div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {

    // Fungsi Helper untuk Render Chart
    const renderBarChart = (elementId, data, color) => {
        const labels = data.map(item => item.label);
        const values = data.map(item => item.value);

        const options = {
            series: [{
                name: 'Total',
                data: values
            }],
            chart: {
                type: 'bar',
                height: 320,
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
                enabled: false
            },
            xaxis: {
                categories: labels,
                labels: {
                    formatter: function(val) {
                        // Format angka ribuan (K) atau Juta (M) biar rapi
                        if (val >= 1000000) return (val / 1000000).toFixed(1) + "M";
                        if (val >= 1000) return (val / 1000).toFixed(0) + "K";
                        return val;
                    }
                }
            },
            colors: [color],
            grid: {
                borderColor: '#f1f5f9'
            }
        };

        const chart = new ApexCharts(document.querySelector("#" + elementId), options);
        chart.render();

        // Return chart instance agar bisa di-update nanti
        return chart;
    };

    // 1. Render Awal
    let chartProduk = renderBarChart('chart-produk', @json($topProducts), '#6366f1'); // Indigo
    let chartCust = renderBarChart('chart-customer', @json($topCustomers), '#10b981'); // Emerald
    let chartSupp = renderBarChart('chart-supplier', @json($topSuppliers), '#f59e0b'); // Orange

    // 2. Listener saat Filter Berubah (Re-Render)
    Livewire.hook('morph.updated', ({
        component,
        el
    }) => {
        // Kita perlu reload data chart saat livewire update
        // Cara paling gampang di Livewire 3 adalah emit event dari PHP, 
        // tapi untuk simpelnya, kita bisa refresh halaman atau gunakan $dispatch 'update-charts'
    });
});
</script>