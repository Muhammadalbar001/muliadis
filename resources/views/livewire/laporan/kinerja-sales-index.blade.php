<div class="min-h-screen space-y-6 pb-20 transition-colors duration-300 font-jakarta"
    x-data="{ activeTab: @entangle('activeTab').live }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-2.5 rounded-xl shadow-lg dark:bg-amber-500/20 bg-amber-500 text-white dark:text-amber-400">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Rapor <span class="text-amber-500">Kinerja Sales</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Sales KPI & Productivity Analytics</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-amber-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-amber-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="Cari Sales...">
                </div>

                <div x-show="activeTab === 'produktifitas'" x-transition
                    class="flex items-center dark:bg-black/40 bg-white border dark:border-white/10 border-amber-200 rounded-xl px-3 shadow-inner group">
                    <span
                        class="text-[9px] font-black text-amber-600 uppercase pr-2 border-r dark:border-white/5 border-amber-100 pl-1">Min.
                        OA</span>
                    <input type="number" wire:model.live.debounce.500ms="minNominal"
                        class="w-20 border-none focus:ring-0 text-xs font-black text-slate-700 dark:text-white py-2 bg-transparent">
                </div>

                <input type="month" wire:model.live="bulan"
                    class="w-full sm:w-40 border px-4 py-2 rounded-xl text-[11px] font-black uppercase dark:bg-black/40 bg-white dark:border-white/10 border-slate-200 dark:text-white transition-all shadow-sm">

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-amber-400">
                        <span class="truncate"
                            x-text="{{ count($filterCabang) }} > 0 ? '{{ count($filterCabang) }} Regional' : 'Regional Hub'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute z-[100] mt-2 w-56 border rounded-2xl shadow-2xl p-2 max-h-72 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-amber-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded-full border-slate-500 text-amber-600 focus:ring-amber-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight dark:text-slate-400 text-slate-600 group-hover:text-amber-400">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="resetFilter"
                        class="px-4 py-2 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button wire:click="export"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-600/20 transition-all transform active:scale-95">
                        <i class="fas fa-file-excel mr-1"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-4 sm:px-6 lg:px-8">
        <div
            class="p-5 rounded-[2rem] border transition-all dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-100 shadow-xl flex flex-col justify-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 opacity-60">Revenue Target
            </p>
            <h3 class="text-xl font-black dark:text-white text-slate-800 tracking-tighter">Rp
                {{ $this->formatCompact($globalSummary['total_target']) }}</h3>
        </div>
        <div
            class="relative p-5 rounded-[2rem] border transition-all overflow-hidden dark:bg-emerald-500/10 dark:border-emerald-500/20 bg-emerald-600 text-white shadow-xl shadow-emerald-600/20">
            <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest mb-1 opacity-80">Net Achievement
            </p>
            <h3 class="text-2xl font-black tracking-tighter">Rp {{ $this->formatCompact($globalSummary['total_real']) }}
            </h3>
            <i class="fas fa-arrow-trend-up absolute -right-2 -bottom-2 text-5xl opacity-10 rotate-12"></i>
        </div>
        <div
            class="p-5 rounded-[2rem] border transition-all dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-100 shadow-xl flex flex-col justify-center">
            <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Accounts Receivable</p>
            <h3 class="text-xl font-black dark:text-white text-slate-800 tracking-tighter">Rp
                {{ $this->formatCompact($globalSummary['total_ar']) }}</h3>
        </div>
        <div
            class="p-5 rounded-[2rem] border transition-all dark:bg-rose-500/5 dark:border-rose-500/20 bg-white border-rose-100 shadow-xl flex flex-col justify-center">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Bad Debt Risk</p>
            <h3 class="text-xl font-black dark:text-rose-400 text-rose-600 tracking-tighter">Rp
                {{ $this->formatCompact($globalSummary['total_macet']) }}</h3>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div
            class="flex space-x-2 dark:bg-white/5 bg-slate-100 p-1.5 rounded-2xl w-fit overflow-x-auto border dark:border-white/5 border-slate-200">
            <button @click="activeTab = 'penjualan'"
                :class="activeTab === 'penjualan' ? 'bg-white dark:bg-emerald-600 text-emerald-600 dark:text-white shadow-lg' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-chart-line"></i> Sales Performance
            </button>
            <button @click="activeTab = 'ar'"
                :class="activeTab === 'ar' ? 'bg-white dark:bg-orange-600 text-orange-600 dark:text-white shadow-lg' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-money-bill-wave"></i> Credit Monitor
            </button>
            <button @click="activeTab = 'supplier'"
                :class="activeTab === 'supplier' ? 'bg-white dark:bg-purple-600 text-purple-600 dark:text-white shadow-lg' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-boxes-stacked"></i> Supplier Mix
            </button>
            <button @click="activeTab = 'produktifitas'"
                :class="activeTab === 'produktifitas' ? 'bg-white dark:bg-blue-600 text-blue-600 dark:text-white shadow-lg' : 'text-slate-500 hover:text-slate-700'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-users-viewfinder"></i> Productivity
            </button>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col min-h-[80vh] mb-10
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl dark:shadow-black/60"
            wire:loading.class="opacity-50 transition-opacity">

            <div x-show="activeTab === 'penjualan'" x-transition>
                <div class="p-6 border-b dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-emerald-50/20">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-600">Sales Achievement
                        Matrix</span>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Sales Representative
                                </th>
                                <th class="px-6 py-5 text-right border-r dark:border-white/5 border-slate-100">Monthly
                                    Target (Rp)</th>
                                <th
                                    class="px-6 py-5 text-right border-r dark:border-white/5 border-slate-100 dark:bg-emerald-600/10 bg-emerald-50/50 text-emerald-600">
                                    Realization (Rp)</th>
                                <th class="px-6 py-5 text-center">Efficiency %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                            @foreach($laporan as $row)
                            <tr class="hover:bg-emerald-500/[0.02] transition-colors group">
                                <td
                                    class="px-6 py-4 dark:text-white text-slate-800 font-black text-xs tracking-tight border-r dark:border-white/5 border-slate-50">
                                    {{ $row['nama'] }}</td>
                                <td
                                    class="px-6 py-4 text-right font-mono text-slate-500 border-r dark:border-white/5 border-slate-50">
                                    {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                                <td
                                    class="px-6 py-4 text-right font-black dark:text-white text-emerald-700 border-r dark:border-white/5 border-slate-50 bg-emerald-500/[0.01]">
                                    {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-[9px] font-black tracking-widest border {{ $row['persen_ims'] >= 100 ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20' }}">
                                        {{ number_format($row['persen_ims'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'ar'" x-transition style="display: none;">
                <div class="p-6 border-b dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-orange-50/20">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-orange-600">Credit Risk &
                        Accounts Receivable Aging</span>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Sales Representative
                                </th>
                                <th class="px-6 py-5 text-right border-r dark:border-white/5 border-slate-100">Total
                                    Outstanding (Rp)</th>
                                <th
                                    class="px-6 py-5 text-right border-r dark:border-white/5 border-slate-100 bg-rose-500/5 text-rose-600">
                                    Bad Debt (>30 Days)</th>
                                <th class="px-6 py-5 text-center">Bad Debt Ratio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                            @foreach($laporan as $row)
                            <tr class="hover:bg-orange-500/[0.02] transition-colors group">
                                <td
                                    class="px-6 py-4 dark:text-white text-slate-800 font-black text-xs tracking-tight border-r dark:border-white/5 border-slate-50">
                                    {{ $row['nama'] }}</td>
                                <td
                                    class="px-6 py-4 text-right font-black dark:text-slate-300 text-slate-600 border-r dark:border-white/5 border-slate-50">
                                    {{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                                <td
                                    class="px-6 py-4 text-right font-black text-rose-600 border-r dark:border-white/5 border-slate-50 bg-rose-500/[0.02]">
                                    {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-[9px] font-black tracking-widest border {{ $row['ar_persen_macet'] > 20 ? 'bg-rose-500/10 text-rose-500 border-rose-500/20 animate-pulse' : 'bg-slate-50 text-slate-500 border-slate-100' }}">
                                        {{ number_format($row['ar_persen_macet'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'supplier'" x-transition style="display: none;">
                <div class="p-6 border-b dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-purple-50/20">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-purple-600">Supplier Penetration
                        & Sales Mix Matrix</span>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table
                        class="w-full text-[10px] text-left whitespace-nowrap border-collapse uppercase font-jakarta">
                        <thead>
                            <tr
                                class="dark:bg-[#070707] bg-slate-50 text-slate-500 dark:text-slate-400 font-black tracking-[0.1em] border-b dark:border-white/5 border-slate-100">
                                <th
                                    class="px-6 py-5 sticky left-0 dark:bg-[#0a0a0a] bg-slate-50 border-r dark:border-white/5 border-slate-100 z-30">
                                    Sales Personnel</th>
                                @foreach($topSuppliers as $supp)
                                <th class="px-6 py-5 text-right border-r dark:border-white/5 border-slate-100">
                                    {{ $supp }}</th>
                                @endforeach
                                <th
                                    class="px-6 py-5 text-center dark:bg-purple-600/20 bg-purple-50 text-purple-600 border-l dark:border-white/10 sticky right-24 z-30">
                                    Brand Count</th>
                                <th
                                    class="px-6 py-5 text-right dark:bg-purple-600 bg-purple-100 dark:text-white text-purple-900 sticky right-0 z-30">
                                    Gross Val</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                            @foreach($laporan as $row)
                            <tr class="hover:bg-purple-500/[0.02] transition-colors group">
                                <td
                                    class="px-6 py-4 font-black dark:text-white text-slate-700 sticky left-0 dark:bg-[#0a0a0a] bg-white border-r dark:border-white/5 border-slate-50 z-10 uppercase">
                                    {{ $row['nama'] }}</td>
                                @foreach($topSuppliers as $supp)
                                <td
                                    class="px-6 py-4 text-right border-r dark:border-white/5 border-slate-50 font-mono opacity-60">
                                    @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                                    {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                                </td>
                                @endforeach
                                <td
                                    class="px-6 py-4 text-center font-black dark:bg-purple-900/20 bg-purple-50 border-l dark:border-white/5 sticky right-24 z-10">
                                    {{ $row['jml_supplier'] }}</td>
                                <td
                                    class="px-6 py-4 text-right font-black dark:bg-purple-950 dark:text-purple-400 bg-purple-100 sticky right-0 z-10">
                                    {{ number_format($row['total_supplier_val'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'produktifitas'" x-transition style="display: none;">
                <div class="p-6 border-b dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-blue-50/20">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-blue-600">Effective Call &
                        Outlet Engagement Analysis</span>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                        <thead>
                            <tr
                                class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                                <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Sales Representative
                                </th>
                                <th
                                    class="px-6 py-5 text-center border-r dark:border-white/5 border-slate-100 text-blue-500">
                                    Outlet Aktif (OA)</th>
                                <th class="px-6 py-5 text-center">Effective Call (EC)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                            @foreach($laporan as $row)
                            <tr class="hover:bg-blue-500/[0.02] transition-colors group">
                                <td
                                    class="px-6 py-4 dark:text-white text-slate-800 font-black text-xs tracking-tight border-r dark:border-white/5 border-slate-50 uppercase">
                                    {{ $row['nama'] }}</td>
                                <td
                                    class="px-6 py-4 text-center font-black text-blue-600 border-r dark:border-white/5 border-slate-50 font-mono">
                                    {{ $row['real_oa'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="font-black dark:text-white text-slate-800 font-mono">{{ $row['ec'] }}</span>
                                        <span class="text-[8px] opacity-40 font-bold tracking-widest uppercase">Invoice
                                            ≥ Rp {{ number_format((float)($minNominal ?: 0), 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="mt-auto px-6 py-6 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $laporan->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(245, 158, 11, 0.3);
    border-radius: 10px;
}
</style>