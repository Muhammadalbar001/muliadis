<div class="space-y-6 font-jakarta" x-data="{ activeTab: @entangle('activeTab').live }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-amber-50/90 p-4 rounded-b-2xl shadow-sm border-b border-amber-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-amber-100 rounded-lg text-amber-600 shadow-sm"><i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-amber-900 tracking-tight">Rapor Kinerja Sales</h1>
                    <p class="text-xs text-amber-700 font-medium mt-0.5">Analisa KPI & Produktivitas.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div
                    class="flex items-center bg-white border border-amber-200 rounded-lg px-3 shadow-sm mr-1 group focus-within:ring-2 focus-within:ring-amber-500">
                    <i class="fas fa-search text-amber-300 text-xs mr-2"></i>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="w-32 md:w-48 border-none focus:ring-0 text-xs font-bold text-slate-700 py-2 bg-transparent"
                        placeholder="Cari Sales...">
                </div>

                <div x-show="activeTab === 'produktifitas'" x-transition
                    class="flex items-center bg-white border border-amber-200 rounded-lg px-2 shadow-sm mr-1">
                    <span
                        class="text-[9px] font-black text-amber-600 uppercase pr-2 border-r border-amber-100 pl-1">Min.
                        OA</span>
                    <input type="number" wire:model.live.debounce.500ms="minNominal"
                        class="w-20 border-none focus:ring-0 text-xs font-bold text-slate-700 py-2 bg-transparent">
                </div>

                <input type="month" wire:model.live="bulan"
                    class="w-full sm:w-36 border-white rounded-lg text-xs font-bold text-slate-700 py-2 shadow-sm cursor-pointer">

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-amber-50">
                        <span class="truncate"
                            x-text="{{ count($filterCabang) }} > 0 ? '{{ count($filterCabang) }} Cabang' : 'Semua Cabang'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open" x-cloak
                        class="absolute right-0 z-[100] mt-1 w-56 bg-white border border-slate-200 rounded-xl shadow-2xl p-2 max-h-72 overflow-y-auto">
                        @foreach($optCabang as $c)
                        <label class="flex items-center px-3 py-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                            <input type="checkbox" wire:model.live="filterCabang" value="{{ $c }}"
                                class="w-4 h-4 rounded border-slate-300 text-amber-500">
                            <span class="ml-3 text-xs font-bold text-slate-600 truncate">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="resetFilter"
                    class="p-2 bg-white border border-amber-200 text-amber-600 rounded-lg shadow-sm hover:bg-rose-50 transition-colors"><i
                        class="fas fa-undo"></i></button>
                <button wire:click="export"
                    class="px-3 py-2 bg-amber-500 text-white rounded-lg text-xs font-bold shadow-md hover:bg-amber-600"><i
                        class="fas fa-file-excel"></i> Export</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Target</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ $this->formatCompact($globalSummary['total_target']) }}
            </h3>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg text-white">
            <p class="text-emerald-100 text-[10px] font-bold uppercase mb-0.5">Total Realisasi</p>
            <h3 class="text-2xl font-extrabold">Rp {{ $this->formatCompact($globalSummary['total_real']) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-orange-100 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-orange-400 uppercase mb-0.5">Total Piutang</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ $this->formatCompact($globalSummary['total_ar']) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-red-400 uppercase mb-0.5">Macet (>30 Hari)</p>
            <h3 class="text-xl font-bold text-red-600">Rp {{ $this->formatCompact($globalSummary['total_macet']) }}</h3>
        </div>
    </div>

    <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl w-fit overflow-x-auto">
        <button @click="activeTab = 'penjualan'"
            :class="activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap"><i
                class="fas fa-chart-line"></i> Penjualan</button>
        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-white text-orange-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap"><i
                class="fas fa-money-bill-wave"></i> Piutang (AR)</button>
        <button @click="activeTab = 'supplier'"
            :class="activeTab === 'supplier' ? 'bg-white text-purple-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap"><i
                class="fas fa-boxes-stacked"></i> Per Supplier</button>
        <button @click="activeTab = 'produktifitas'"
            :class="activeTab === 'produktifitas' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap"><i
                class="fas fa-users-viewfinder"></i> Produktifitas</button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px]"
        wire:loading.class="opacity-50 transition-opacity">

        <div x-show="activeTab === 'penjualan'" x-transition>
            <div class="p-4 border-b border-slate-200 bg-emerald-50/30 font-bold text-emerald-800 text-sm italic">
                Penjualan Target vs Realisasi</div>
            <div class="overflow-auto custom-scrollbar h-[60vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase border-b sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3.5 border-r">Nama Sales</th>
                            <th class="px-6 py-3.5 text-right border-r">Target (Rp)</th>
                            <th class="px-6 py-3.5 text-right bg-emerald-50/50">Pencapaian (Rp)</th>
                            <th class="px-6 py-3.5 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan as $row)
                        <tr class="hover:bg-emerald-50/10 odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 border-r font-bold text-slate-700 uppercase text-xs">{{ $row['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-right font-mono text-slate-600">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-bold text-emerald-700 font-mono bg-emerald-50/10">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center font-black">
                                <span
                                    class="px-2 py-1 rounded text-[10px] {{ $row['persen_ims'] >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($row['persen_ims'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center font-bold text-slate-400">Nama Sales Tidak
                                Ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'supplier'" x-transition style="display: none;">
            <div class="p-4 border-b border-slate-200 bg-purple-50/30 font-bold text-purple-800 text-sm italic">Product
                Mix & Supplier Penetration</div>
            <div class="overflow-auto custom-scrollbar h-[60vh]">
                <table class="w-full text-[11px] text-left whitespace-nowrap border-collapse min-w-max">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 sticky left-0 bg-slate-50 border-r z-30">Nama Sales</th>
                            @foreach($topSuppliers as $supp)
                            <th class="px-4 py-3 text-right border-r bg-slate-50">{{ $supp }}</th>
                            @endforeach
                            <th
                                class="px-4 py-3 text-center bg-purple-100 text-purple-700 border-l z-30 sticky right-24">
                                Jml Brand</th>
                            <th class="px-4 py-3 text-right bg-purple-200 text-purple-900 z-30 sticky right-0">Total
                                Jual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-purple-50/10 odd:bg-white even:bg-slate-50/30 group">
                            <td
                                class="px-4 py-2 font-bold text-slate-700 sticky left-0 bg-inherit border-r z-10 uppercase">
                                {{ $row['nama'] }}</td>
                            @foreach($topSuppliers as $supp)
                            <td class="px-4 py-2 text-right border-r text-slate-600 font-mono">
                                @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                            @endforeach
                            <td
                                class="px-4 py-2 text-center font-black bg-purple-50 group-hover:bg-purple-100 border-l sticky right-24 z-10">
                                {{ $row['jml_supplier'] }}</td>
                            <td
                                class="px-4 py-2 text-right font-black text-purple-900 bg-purple-100 group-hover:bg-purple-200 sticky right-0 z-10">
                                {{ number_format($row['total_supplier_val'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'produktifitas'" x-transition style="display: none;">
            <div
                class="p-4 border-b border-slate-200 bg-blue-50/30 flex justify-between items-center font-bold text-blue-800 text-sm italic">
                <span>OA & Effective Call Analysis</span>
                <span class="text-[9px] bg-blue-100 text-blue-700 px-2 py-1 rounded">Syarat EC: ≥ Rp
                    {{ number_format((float)($minNominal ?: 0), 0, ',', '.') }}</span>
            </div>
            <div class="overflow-auto custom-scrollbar h-[60vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase border-b sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3.5 border-r">Nama Sales</th>
                            <th class="px-6 py-3.5 text-center border-r text-blue-600">Outlet Aktif (OA)</th>
                            <th class="px-6 py-3.5 text-center">Efektif Call (EC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-blue-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 border-r font-bold text-slate-700 uppercase text-xs">{{ $row['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-center font-bold text-blue-600 font-mono">{{ $row['real_oa'] }}
                            </td>
                            <td class="px-6 py-3 text-center font-bold text-slate-700 font-mono">
                                {{ $row['ec'] }}
                                <div class="text-[9px] font-normal text-slate-400 mt-0.5">Nota ≥
                                    {{ number_format((float)($minNominal ?: 0), 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
</div>